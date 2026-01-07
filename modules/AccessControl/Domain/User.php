<?php

namespace Modules\AccessControl\Domain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens; // <--- 1. ADICIONE ESTE IMPORT

class User extends Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable;

    // --- MUDANÇA CRÍTICA AQUI ---
    // Importamos a Trait, mas renomeamos o método original dela para 'spatieHasPermissionTo'
    // Isso nos permite criar o nosso próprio 'hasPermissionTo' e chamar o original depois.
    use HasRoles {
        hasPermissionTo as public spatieHasPermissionTo;
    }

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // No arquivo Modules\AccessControl\Domain\User.php

    // Relação com a tabela de bloqueios
    // --- LÓGICA DE BLOQUEIO (Adicione no final do User.php) ---

    // 1. Relacionamento com a tabela de bloqueios que criamos
    // =========================================================================
    // LÓGICA DE BLOQUEIO (NEGATIVE PERMISSIONS)
    // =========================================================================

    /**
     * Relacionamento com as permissões explicitamente proibidas.
     */
    public function forbiddenPermissions()
    {
        return $this->belongsToMany(
            \Spatie\Permission\Models\Permission::class, 
            'user_forbidden_permissions', // Nome da tabela pivot
            'user_id', 
            'permission_id'
        );
    }

    /**
     * Verifica se uma permissão está na lista negra deste usuário.
     */
    public function isPermissionForbidden(string $permissionName): bool
    {
        // Verifica se existe alguma permissão com esse nome na lista de bloqueados
        return $this->forbiddenPermissions()
            ->where('name', $permissionName)
            ->exists();
    }

    /**
     * Bloqueia uma permissão para este usuário (Adiciona à lista negra).
     * Use isso quando desmarcar o checkbox no Frontend.
     */
    public function forbidPermission(string $permissionName)
    {
        $permission = \Spatie\Permission\Models\Permission::findByName($permissionName);
        if ($permission) {
            $this->forbiddenPermissions()->syncWithoutDetaching([$permission->id]);
        }
    }

    /**
     * Desbloqueia a permissão (Remove da lista negra).
     * A regra do Cargo volta a valer.
     */
    public function unforbidPermission(string $permissionName)
    {
        $permission = \Spatie\Permission\Models\Permission::findByName($permissionName);
        if ($permission) {
            $this->forbiddenPermissions()->detach($permission->id);
        }
    }

    /**
     * Sobrescreve a verificação de permissão do Spatie.
     * Isso garante que o BLOQUEIO seja verificado antes de qualquer coisa.
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        // 1. Regra de Super Admin (Opcional, se quiser garantir aqui também)
        if (is_null($this->tenant_id)) {
            return true;
        }

        // Normaliza o nome (caso passem o objeto Permission em vez da string)
        $permissionName = is_string($permission) ? $permission : $permission->name;

        // 2. O BLOQUEIO IRREVOGÁVEL
        // Se estiver na lista negra, retorna FALSE imediatamente.
        if ($this->isPermissionForbidden($permissionName)) {
            return false;
        }

        // 3. Se não estiver bloqueado, chama a lógica original do Spatie (Cargos/Permissões)
        return $this->spatieHasPermissionTo($permission, $guardName);
    }

    
}
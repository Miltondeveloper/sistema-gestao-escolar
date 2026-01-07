<?php

namespace Modules\AccessControl\Infrastructure\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\AccessControl\Domain\User;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class UserPermissionController extends Controller
{
    public function edit(User $user)
    {
        // Carrega todas as permissões e formata para o Vue
        $permissions = Permission::all()->map(function ($permission) use ($user) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                // Verifica se o usuário tem a permissão pelo CARGO (ignora o bloqueio)
                // Lembra que renomeamos o método original no User.php? Usamos ele aqui.
                'granted_by_role' => $user->spatieHasPermissionTo($permission->name),
            ];
        });

        // Pega os IDs que já estão bloqueados
        $forbiddenIds = $user->forbiddenPermissions->pluck('id');

        return Inertia::render('Admin/UserPermissions', [
            'user' => $user,
            'permissions' => $permissions,
            'currentForbidden' => $forbiddenIds,
        ]);
    }

    public function update(Request $request, User $user)
    {
        // Pega os IDs marcados no checkbox
        $permissionsToForbid = $request->input('forbidden_permissions', []);
        
        // Salva no banco
        $user->forbiddenPermissions()->sync($permissionsToForbid);

        return redirect()->back()->with('success', 'Permissões atualizadas!');
    }
}
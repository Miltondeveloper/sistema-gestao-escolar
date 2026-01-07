<?php

namespace Modules\AccessControl\Domain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'document',
        'domain',
        'is_active',
    ];

    // Relacionamento: Uma empresa tem muitos usuários
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
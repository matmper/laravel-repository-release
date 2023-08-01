<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users';

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'id',
        'name',
        'email',
    ];
}

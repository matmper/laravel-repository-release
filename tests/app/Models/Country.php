<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected $hidden = [];

    protected $fillable = [
        'id',
        'name',
        'code',
    ];
}

<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\Repositories;

use Matmper\Repositories\BaseRepository;
use App\Models\Country;

final class CountryRepository extends BaseRepository
{
    /**
     * @var Country
     */
    protected $model = Country::class;
}

<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\Repositories;

use Matmper\Repositories\BaseRepository;

final class CountryRepository extends BaseRepository
{
    /**
     * @var \App\Models\Country;
     */
    protected $model = \App\Models\Country::class;
}

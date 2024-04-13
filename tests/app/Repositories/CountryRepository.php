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
    protected $model;

    public function __construct()
    {
        $this->model = new \App\Models\Country();
        parent::__construct();
    }
}

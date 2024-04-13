<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\Repositories;

use Matmper\Repositories\BaseRepository;

final class UserRepository extends BaseRepository
{
    /**
     * @var \App\Models\User;
     */
    protected $model;

    public function __construct()
    {
        $this->model = new \App\Models\User();
        parent::__construct();
    }
}

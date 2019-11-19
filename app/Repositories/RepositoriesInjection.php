<?php


namespace App\Repositories;

use App\Repositories\Concretes\AdminRepository;
use App\Repositories\Contracts\IAdminRepository;
use App\Repositories\Concretes\UserRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Concretes\BookRepository;
use App\Repositories\Contracts\IBookRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesInjection extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );

        $this->app->bind(
            IBookRepository::class,
            BookRepository::class
        );

        $this->app->bind(
            IAdminRepository::class,
            AdminRepository::class
        );
    }
}

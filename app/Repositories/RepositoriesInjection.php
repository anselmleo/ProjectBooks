<?php


namespace App\Repositories;

use App\Repositories\Concretes\AdminRepository;
use App\Repositories\Contracts\IAdminRepository;
use App\Repositories\Concretes\UserRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Concretes\PhotoRepository;
use App\Repositories\Contracts\IPhotoRepository;
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
            IPhotoRepository::class,
            PhotoRepository::class
        );
        
        $this->app->bind(
            IPaystackRepository::class,
            PaystackRepository::class
        );

        $this->app->bind(
            IAdminRepository::class,
            AdminRepository::class
        );
    }
}

<?php

namespace App\Providers;
// Repository

use App\Repository\Eloquent\BaseRepository;use App\Repository\EloquentRepositoryInterface;
use App\Repository\Eloquent\CategoryRepository;use App\Repository\CategoryRepositoryInterface;
use App\Repository\Eloquent\CartRepository;use App\Repository\CartRepositoryInterface;
use App\Repository\Eloquent\ItemRepository;
use App\Repository\Eloquent\OrderRepository;
use App\Repository\Eloquent\PaymentRepository;
use App\Repository\ItemRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\PaymentRepositoryInterface;
// Role  Permission
        // use App\Repository\Eloquent\RoleRepository;

        // use App\Repository\Eloquent\RolePermissionRepository\PermissionRepository;
        // // use App\Repository\Eloquent\RolePermissionRepository\RoleRepository;
        // use App\Repository\Eloquent\RolePermissionRepository\ModelHasPermissionRepository;
        // use App\Repository\Eloquent\RolePermissionRepository\ModelHasRoleRepository;
        // use App\Repository\Eloquent\RolePermissionRepository\RoleHasPermissionRepository;
    // Role  Permission





    // Role  Permission
    // use App\Repository\RoleRepositoryInterface;

    // use App\Repository\RolePermissionInterface\PermissionRepositoryInterface;
    // // use App\Repository\RolePermissionInterface\RoleRepositoryInterface;
    // use App\Repository\RolePermissionInterface\ModelHasPermissionRepositoryInterface;
    // use App\Repository\RolePermissionInterface\ModelHasRoleRepositoryInterface;
    // use App\Repository\RolePermissionInterface\RoleHasPermissionRepositoryInterface;
    // Role  Permission


use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Basic
        $this->app->bind(EloquentRepositoryInterface::class,BaseRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
        $this->app->bind(CartRepositoryInterface::class,CartRepository::class);
        $this->app->bind(ItemRepositoryInterface::class,ItemRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);


        // Role  Permission
        // $this->app->bind(PermissionRepositoryInterface::class,PermissionRepository::class);
        // $this->app->bind(RoleRepositoryInterface::class,RoleRepository::class);
        // $this->app->bind(ModelHasPermissionRepositoryInterface::class,ModelHasPermissionRepository::class);
        // $this->app->bind(ModelHasRoleRepositoryInterface::class,ModelHasRoleRepository::class);
        // $this->app->bind(RoleHasPermissionRepositoryInterface::class,RoleHasPermissionRepository::class);


    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

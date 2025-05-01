<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['users.create', 'users.edit'], function ($view) {
            return $view->with(
                'roles',
                Role::select('id', 'name')->get()
            );
        });


				View::composer(['siswa.create', 'siswa.edit'], function ($view) {
            return $view->with(
                'jurusans',
                \App\Models\Jurusan::select('id', 'nama_jurusan')->get()
            );
        });

		View::composer(['siswa.create', 'siswa.edit'], function ($view) {
            return $view->with(
                'kelas',
                \App\Models\Kelas::select('id', 'nama_kelas')->get()
            );
        });

	}
}

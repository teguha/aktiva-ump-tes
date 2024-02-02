<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class BaseAppServiceProvider extends ServiceProvider
{
    public function bootBaseApp()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        $this->commonFields();
        $this->customCollection();
    }

    public function commonFields()
    {
        Blueprint::macro('commonFields', function ($fields = 'created_by|updated_by|created_at|updated_at') {
            if (is_string($fields)) {
                $fields = explode('|', $fields);
            }

            foreach ($fields as $field) {
                switch (trim($field)) {
                    case 'created_by':
                        $this->unsignedBigInteger('created_by')->nullable();
                        break;
                    case 'updated_by':
                        $this->unsignedBigInteger('updated_by')->nullable();
                        break;
                    case 'created_at':
                        $this->timestamp('created_at')->nullable();
                        break;
                    case 'updated_at':
                        $this->timestamp('updated_at')->nullable();
                        break;
                }
            }
        });
    }

    public function customCollection()
    {
        Collection::macro('paginate', function($perPage = 10, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            $perPage = $perPage == 'all' ? $this->count() : $perPage;

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    public function resourceGrid()
    {
        Route::macro('resourceAndActive', function ($uri, $controller) {
            Route::get("{$uri}/active", "{$controller}@active")->name("{$uri}.active");
            Route::resource($uri, $controller);
        });
    }
}

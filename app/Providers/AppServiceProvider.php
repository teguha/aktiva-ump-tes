<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try{
            if (\Cache::has('userLocale')) {
                \App::setLocale(\Cache::get('userLocale', 'id'));
            }
        } catch(\Exception $e){
            \App::setLocale('id');    
        }
        
        $this->newBaseMacro();
    }

    public function newBaseMacro()
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

        Route::macro('grid', function ($uri, $controller, $params = []) {
            if (!empty($params['with']) && is_array($params['with'])) {
                foreach ($params['with'] as $action) {
                    switch ($action) {
                        case 'submit':
                            Route::get("{$uri}/{record}/submit", "{$controller}@submit")->name("{$uri}.submit");
                            Route::post("{$uri}/{record}/submit", "{$controller}@submitSave")->name("{$uri}.submitSave");
                            break;
                        case 'approval':
                            Route::get("{$uri}/{record}/approval", "{$controller}@approval")->name("{$uri}.approval");
                            Route::post("{$uri}/{record}/approve", "{$controller}@approve")->name("{$uri}.approve");
                            Route::post("{$uri}/{record}/reject", "{$controller}@reject")->name("{$uri}.reject");
                            break;
                        case 'print':
                            Route::get("{$uri}/{record}/print", "{$controller}@print")->name("{$uri}.print");
                            break;
                        case 'history':
                            Route::get("{$uri}/{record}/history", "{$controller}@history")->name("{$uri}.history");
                            break;
                        case 'tracking':
                            Route::get("{$uri}/{record}/tracking", "{$controller}@tracking")->name("{$uri}.tracking");
                            break;
                    }
                }
            }
            Route::post("{$uri}/grid", "{$controller}@grid")->name("{$uri}.grid");
            Route::resource($uri, $controller, $params)->parameters([$uri => 'record']);
        });

        Builder::macro('whereLike', function ($attributes, string $keyword) {
            $this->where(function (Builder $query) use ($attributes, $keyword) {
                foreach (\Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'), 
                        function (Builder $query) use ($attribute, $keyword) {
                            $buffer = explode('.', $attribute);
                            $attributeField = array_pop($buffer);
                            $relationPath = implode('.', $buffer);
                            $query->orWhereHas($relationPath, function (Builder $query) use ($attributeField, $keyword) {
                                $query->where($attributeField, 'LIKE', "%{$keyword}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $keyword) {
                            $query->orWhere($attribute, 'LIKE', "%{$keyword}%");
                        }
                    );
                }
            });
            return $this;
        });
    }
}

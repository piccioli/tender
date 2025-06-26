<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Permission extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Spatie\Permission\Models\Permission>
     */
    public static $model = \Spatie\Permission\Models\Permission::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable(),
            BelongsToMany::make('Roles', 'roles', 'App\Nova\Role'),
        ];
    }

    /**
     * Get the group that the resource belongs to.
     *
     * @return string
     */
    public static function group()
    {
        return 'Admin';
    }

    /**
     * Determine if the current user can view any models.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        return $request->user() && $request->user()->hasRole('admin');
    }

    /**
     * Determine if the current user can view the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return $request->user() && $request->user()->hasRole('admin');
    }

    /**
     * Determine if the current user can create new models.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return $request->user() && $request->user()->hasRole('admin');
    }

    /**
     * Determine if the current user can update the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return $request->user() && $request->user()->hasRole('admin');
    }

    /**
     * Determine if the current user can delete the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $request->user() && $request->user()->hasRole('admin');
    }

    /**
     * Determine if the current user can restore the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToRestore(Request $request)
    {
        return $request->user() && $request->user()->hasRole('admin');
    }

    /**
     * Determine if the current user can permanently delete the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToForceDelete(Request $request)
    {
        return $request->user() && $request->user()->hasRole('admin');
    }
} 
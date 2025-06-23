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
} 
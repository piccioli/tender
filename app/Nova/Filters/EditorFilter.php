<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\User;

class EditorFilter extends Filter
{
    public $component = 'select-filter';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('user_editor_id', $value);
    }

    public function options(NovaRequest $request): array
    {
        return User::pluck('id', 'name')->toArray();
    }
} 
<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Laravel\Nova\Panel;

class Tender extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Tender>
     */
    public static $model = \App\Models\Tender::class;

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
        'id', 'name', 'manager', 'program', 'funding_agency'
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
            ID::make()->sortable()->onlyOnIndex(),

            Badge::make('Stato', 'status')
                ->map([
                    'draft' => 'info',
                    'submitted' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                ])
                ->onlyOnIndex(),

            Select::make('Tipo Bando', 'tender_type')
                ->options(\App\Models\Tender::getTenderTypeOptions())
                ->onlyOnIndex(),

            Text::make('Nome Bando', 'name')
                ->onlyOnIndex(),

            Text::make('Programma', 'program')
                ->onlyOnIndex(),

            Date::make('Scadenza', 'deadline')
                ->onlyOnIndex()
                ->sortable(),

            Text::make('Giorni alla Scadenza', function () {
                if (!$this->deadline) {
                    return '-';
                }
                $today = now()->startOfDay();
                $deadline = \Carbon\Carbon::parse($this->deadline)->startOfDay();
                $daysDiff = $today->diffInDays($deadline, false);
            
                if ($daysDiff < 0) {
                    return '<span style="color: #fff; background: #e3342f; border-radius: 0.25rem; padding: 0.2em 0.6em; font-size: 0.9em;">Scaduto</span>';
                } elseif ($daysDiff == 0) {
                    return '<span style="color: #fff; background: #f59e42; border-radius: 0.25rem; padding: 0.2em 0.6em; font-size: 0.9em;">Scade oggi</span>';
                } elseif ($daysDiff <= 7) {
                    return '<span style="color: #fff; background: #f59e42; border-radius: 0.25rem; padding: 0.2em 0.6em; font-size: 0.9em;">' . $daysDiff . ' giorni</span>';
                } else {
                    return '<span style="color: #fff; background: #38c172; border-radius: 0.25rem; padding: 0.2em 0.6em; font-size: 0.9em;">' . $daysDiff . ' giorni</span>';
                }
            })->onlyOnIndex()->asHtml(),

            Number::make('Investimento Beneficiario', 'beneficiary_investment')
                ->step(0.01)
                ->onlyOnIndex()
                ->displayUsing(fn($value) => is_null($value) ? null : number_format($value, 2, ',', '.') . ' €'),

            Number::make('Stima Budget MS', 'ms_budget_estimate')
                ->step(0.01)
                ->onlyOnIndex()
                ->displayUsing(fn($value) => is_null($value) ? null : number_format($value, 2, ',', '.') . ' €'),

            BelongsTo::make('Creatore', 'userCreator', User::class)
                ->readonly()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->rules('required'),

            BelongsTo::make('Redattore', 'userEditor', User::class)
                ->nullable(),

            // Tutti gli altri campi solo su form/detail
            ID::make()->sortable()->hideFromIndex(),
            Text::make('Nome Bando', 'name')->sortable()->rules('required', 'max:255')->hideFromIndex(),
            Select::make('Tipo Bando', 'tender_type')
                ->options(\App\Models\Tender::getTenderTypeOptions())
                ->rules('required')
                ->hideFromIndex(),
            Select::make('Stato', 'status')
                ->options(\App\Models\Tender::getStatusOptions())
                ->rules('required')
                ->hideFromIndex(),
            Text::make('Programma', 'program')->nullable()->hideFromIndex(),
            Text::make('Luogo Implementazione', 'implementation_place')->nullable()->hideFromIndex(),
            Text::make('Ente Finanziatore', 'funding_agency')->nullable()->hideFromIndex(),
            Textarea::make('Sito Web Bando', 'website')->nullable()->hideFromIndex(),
            Textarea::make('Tematica', 'topic')->nullable()->hideFromIndex(),
            Date::make('Data Pubblicazione', 'publication_date')->nullable()->hideFromIndex(),
            Number::make('Numero Progetti Presentabili', 'projects_submittable')->nullable()->hideFromIndex(),
            Text::make('Tipologia Finanziamento', 'funding_type')->nullable()->hideFromIndex(),
            Textarea::make('Ipotesi Azioni MS', 'ms_actions_hypothesis')->nullable()->hideFromIndex(),
            Text::make('Durata Progetto', 'project_duration')->nullable()->hideFromIndex(),
            Date::make('Inizio Attività', 'activity_start')->nullable()->hideFromIndex(),
            Date::make('Fine Attività', 'activity_end')->nullable()->hideFromIndex(),
            Text::make('Ciclicità Finanziamento', 'funding_cycle')->nullable()->hideFromIndex(),
            Date::make('Data Ultima Pubblicazione', 'last_publication_date')->nullable()->hideFromIndex(),
            Date::make('Scadenza', 'deadline')->hideFromIndex(),
            Number::make('Investimento Beneficiario', 'beneficiary_investment')->step(0.01)->hideFromIndex(),
            Number::make('Stima Budget MS', 'ms_budget_estimate')->step(0.01)->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new \App\Nova\Filters\TenderStatusFilter,
            new \App\Nova\Filters\TenderProcedureTypeFilter,
            new \App\Nova\Filters\TenderContractTypeFilter,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new \App\Nova\Actions\ExportTenders,
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Bandi di Gara';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Bando di Gara';
    }

    /**
     * Get the group that the resource belongs to.
     *
     * @return string
     */
    public static function group()
    {
        return 'Tender';
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return $request->user() !== null;
    }

    /**
     * Determine if the current user can update the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return $request->user() && $request->user()->can('update', $this->model());
    }

    /**
     * Determine if the current user can delete the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $request->user() && $request->user()->can('delete', $this->model());
    }
} 
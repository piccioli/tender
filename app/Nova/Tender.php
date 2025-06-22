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
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'reference_number', 'contracting_authority'
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

            new Panel('Informazioni Generali', [
                Text::make('Titolo', 'title')
                    ->sortable()
                    ->rules('required', 'max:255'),

                Textarea::make('Descrizione', 'description')
                    ->hideFromIndex()
                    ->nullable(),

                Text::make('Numero di Riferimento', 'reference_number')
                    ->sortable()
                    ->rules('required', 'unique:tenders,reference_number,{{resourceId}}'),

                Text::make('Stazione Appaltante', 'contracting_authority')
                    ->sortable()
                    ->rules('required', 'max:255'),
            ]),

            new Panel('Valore e Date', [
                Number::make('Valore Stimato', 'estimated_value')
                    ->step(0.01)
                    ->nullable()
                    ->displayUsing(function ($value) {
                        return $value ? number_format($value, 2, ',', '.') . ' €' : 'Non specificato';
                    }),

                Select::make('Valuta', 'currency')
                    ->options([
                        'EUR' => 'Euro (€)',
                        'USD' => 'Dollaro ($)',
                        'GBP' => 'Sterlina (£)',
                    ])
                    ->default('EUR')
                    ->rules('required'),

                Date::make('Data di Pubblicazione', 'publication_date')
                    ->sortable()
                    ->rules('required', 'date'),

                Date::make('Scadenza Presentazione', 'submission_deadline')
                    ->sortable()
                    ->rules('required', 'date', 'after:publication_date'),

                Date::make('Data Apertura Buste', 'opening_date')
                    ->nullable()
                    ->rules('date', 'after:submission_deadline'),
            ]),

            new Panel('Dettagli Procedura', [
                Select::make('Tipo di Procedura', 'procedure_type')
                    ->options(\App\Models\Tender::getProcedureTypeOptions())
                    ->sortable()
                    ->rules('required'),

                Select::make('Tipo di Contratto', 'contract_type')
                    ->options(\App\Models\Tender::getContractTypeOptions())
                    ->sortable()
                    ->rules('required'),

                Text::make('Codici CPV', 'cpv_codes')
                    ->nullable()
                    ->help('Separare i codici con virgola'),

                Text::make('Luogo di Esecuzione', 'place_of_execution')
                    ->nullable(),

                Text::make('Durata (Mesi)', 'duration_months')
                    ->nullable(),
            ]),

            new Panel('Stato e Note', [
                Select::make('Stato', 'status')
                    ->options(\App\Models\Tender::getStatusOptions())
                    ->sortable()
                    ->default('active')
                    ->rules('required'),

                Textarea::make('Note', 'notes')
                    ->hideFromIndex()
                    ->nullable(),

                URL::make('URL Documento', 'document_url')
                    ->nullable()
                    ->hideFromIndex(),
            ]),

            // Campi calcolati per l'index
            Badge::make('Stato', 'status')
                ->map([
                    'active' => 'success',
                    'closed' => 'warning',
                    'awarded' => 'info',
                    'cancelled' => 'danger',
                    'draft' => 'default',
                ])
                ->onlyOnIndex(),

            Text::make('Valore Formattato', function () {
                return $this->formatted_estimated_value;
            })->onlyOnIndex(),
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
        return $request->user() && $request->user()->can('create', static::$model);
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
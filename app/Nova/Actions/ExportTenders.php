<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ExportTenders extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Esporta Bandi';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $filename = 'tenders_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'ID',
            'Titolo',
            'Numero di Riferimento',
            'Stazione Appaltante',
            'Valore Stimato',
            'Valuta',
            'Data di Pubblicazione',
            'Scadenza Presentazione',
            'Data Apertura Buste',
            'Tipo di Procedura',
            'Tipo di Contratto',
            'Codici CPV',
            'Luogo di Esecuzione',
            'Durata (Mesi)',
            'Stato',
            'Note',
            'URL Documento',
            'Data Creazione',
            'Data Aggiornamento'
        ];

        $csvData = [];
        $csvData[] = $headers;

        foreach ($models as $tender) {
            $csvData[] = [
                $tender->id,
                $tender->title,
                $tender->reference_number,
                $tender->contracting_authority,
                $tender->estimated_value,
                $tender->currency,
                $tender->publication_date?->format('d/m/Y'),
                $tender->submission_deadline?->format('d/m/Y'),
                $tender->opening_date?->format('d/m/Y'),
                $tender->procedure_type,
                $tender->contract_type,
                $tender->cpv_codes,
                $tender->place_of_execution,
                $tender->duration_months,
                $tender->status,
                $tender->notes,
                $tender->document_url,
                $tender->created_at?->format('d/m/Y H:i:s'),
                $tender->updated_at?->format('d/m/Y H:i:s')
            ];
        }

        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field ?? '') . '"';
            }, $row)) . "\n";
        }

        return Action::download(
            $csvContent,
            $filename,
            ['Content-Type' => 'text/csv']
        );
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'reference_number',
        'contracting_authority',
        'estimated_value',
        'currency',
        'publication_date',
        'submission_deadline',
        'opening_date',
        'procedure_type',
        'contract_type',
        'cpv_codes',
        'place_of_execution',
        'duration_months',
        'status',
        'notes',
        'document_url',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'submission_deadline' => 'date',
        'opening_date' => 'date',
        'estimated_value' => 'decimal:2',
    ];

    /**
     * Get the status options for the tender
     */
    public static function getStatusOptions(): array
    {
        return [
            'active' => 'Attivo',
            'closed' => 'Chiuso',
            'awarded' => 'Aggiudicato',
            'cancelled' => 'Annullato',
            'draft' => 'Bozza',
        ];
    }

    /**
     * Get the procedure type options
     */
    public static function getProcedureTypeOptions(): array
    {
        return [
            'open' => 'Procedura Aperta',
            'restricted' => 'Procedura Ristretta',
            'negotiated' => 'Procedura Negoziale',
            'competitive_dialogue' => 'Dialogo Competitivo',
            'innovation_partnership' => 'Partenariato per l\'Innovazione',
        ];
    }

    /**
     * Get the contract type options
     */
    public static function getContractTypeOptions(): array
    {
        return [
            'works' => 'Lavori',
            'supplies' => 'Forniture',
            'services' => 'Servizi',
            'works_and_supplies' => 'Lavori e Forniture',
            'works_and_services' => 'Lavori e Servizi',
        ];
    }

    /**
     * Scope for active tenders
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for tenders with upcoming deadline
     */
    public function scopeUpcomingDeadline($query, $days = 30)
    {
        return $query->where('submission_deadline', '>=', now())
                    ->where('submission_deadline', '<=', now()->addDays($days));
    }

    /**
     * Check if tender is expired
     */
    public function isExpired(): bool
    {
        return $this->submission_deadline < now();
    }

    /**
     * Get formatted estimated value
     */
    public function getFormattedEstimatedValueAttribute(): string
    {
        if (!$this->estimated_value) {
            return 'Non specificato';
        }
        
        return number_format($this->estimated_value, 2, ',', '.') . ' ' . $this->currency;
    }
} 
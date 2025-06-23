<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'program',
        'implementation_place',
        'funding_agency',
        'website',
        'topic',
        'publication_date',
        'deadline',
        'beneficiary_investment',
        'projects_submittable',
        'ms_budget_estimate',
        'funding_type',
        'ms_actions_hypothesis',
        'project_duration',
        'activity_start',
        'activity_end',
        'funding_cycle',
        'last_publication_date',
        'tender_type',
        'status',
        'user_creator_id',
        'user_editor_id',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'deadline' => 'date',
        'last_publication_date' => 'date',
        'beneficiary_investment' => 'float',
        'ms_budget_estimate' => 'float',
        'activity_start' => 'date',
        'activity_end' => 'date',
    ];

    public function userCreator()
    {
        return $this->belongsTo(User::class, 'user_creator_id');
    }

    public function userEditor()
    {
        return $this->belongsTo(User::class, 'user_editor_id');
    }

    public static function getTenderTypeOptions(): array
    {
        return [
            'Regionale - Locale' => 'Regionale - Locale',
            'Nazionale' => 'Nazionale',
            'Europeo' => 'Europeo',
            'Cooperazione' => 'Cooperazione',
            'Bandi per MS' => 'Bandi per MS',
        ];
    }

    public static function getStatusOptions(): array
    {
        return [
            'draft' => 'Bozza',
            'submitted' => 'Inviato',
            'approved' => 'Approvato',
            'rejected' => 'Respinto',
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

    protected static function booted()
    {
        static::creating(function ($tender) {
            if (auth()->check() && empty($tender->user_creator_id)) {
                $tender->user_creator_id = auth()->id();
            }
        });
    }
} 
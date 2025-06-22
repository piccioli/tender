<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titolo del bando
            $table->text('description')->nullable(); // Descrizione
            $table->string('reference_number')->unique(); // Numero di riferimento
            $table->string('contracting_authority'); // Stazione appaltante
            $table->decimal('estimated_value', 15, 2)->nullable(); // Valore stimato
            $table->string('currency', 3)->default('EUR'); // Valuta
            $table->date('publication_date'); // Data di pubblicazione
            $table->date('submission_deadline'); // Scadenza presentazione
            $table->date('opening_date')->nullable(); // Data apertura buste
            $table->string('procedure_type'); // Tipo di procedura
            $table->string('contract_type'); // Tipo di contratto
            $table->string('cpv_codes')->nullable(); // Codici CPV
            $table->string('place_of_execution')->nullable(); // Luogo di esecuzione
            $table->string('duration_months')->nullable(); // Durata in mesi
            $table->string('status')->default('active'); // Stato del bando
            $table->text('notes')->nullable(); // Note aggiuntive
            $table->string('document_url')->nullable(); // URL documento
            $table->timestamps();
            
            // Indici per migliorare le performance
            $table->index(['status', 'publication_date']);
            $table->index(['contracting_authority']);
            $table->index(['procedure_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
}; 
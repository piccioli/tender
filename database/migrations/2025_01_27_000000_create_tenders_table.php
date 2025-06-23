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

            // General tender data
            $table->string('name');
            $table->string('program')->nullable();
            $table->string('implementation_place')->nullable();
            $table->string('funding_agency')->nullable();
            $table->text('website')->nullable();
            $table->text('topic')->nullable();
            $table->date('publication_date')->nullable();
            $table->date('deadline')->nullable();
            $table->float('beneficiary_investment', 8, 2)->nullable();
            $table->integer('projects_submittable')->nullable();
            $table->float('ms_budget_estimate', 12, 2)->nullable();
            $table->string('funding_type')->nullable();
            $table->text('ms_actions_hypothesis')->nullable();
            $table->string('project_duration')->nullable();
            $table->date('activity_start')->nullable();
            $table->date('activity_end')->nullable();
            $table->string('funding_cycle')->nullable();
            $table->date('last_publication_date')->nullable();

            // Tender type (from sheet name)
            $table->enum('tender_type', [
                'Regionale - Locale',
                'Nazionale',
                'Europeo',
                'Cooperazione',
                'Bandi per MS'
            ]);

            // Tender status
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');

            // User relations
            $table->foreignId('user_creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_editor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
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
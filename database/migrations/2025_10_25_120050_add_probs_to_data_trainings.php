<?php

// database/migrations/2025_10_25_120000_add_probs_to_data_trainings.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            // gunakan presisi yang cukup; 16,8 biasanya aman
            $table->decimal('prob_layak', 16, 8)->nullable()->after('status_penempatan');
            $table->decimal('prob_tidak_layak', 16, 8)->nullable()->after('prob_layak');
        });
    }

    public function down(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            $table->dropColumn(['prob_layak', 'prob_tidak_layak']);
        });
    }
};


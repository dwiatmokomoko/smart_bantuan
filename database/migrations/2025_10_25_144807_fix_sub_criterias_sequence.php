<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Sesuaikan untuk PostgreSQL
        DB::statement("
            SELECT setval(
                pg_get_serial_sequence('sub_criterias','id'),
                COALESCE((SELECT MAX(id) FROM sub_criterias), 0) + 1,
                false
            )
        ");
    }

    public function down(): void
    {
        // no-op
    }
};

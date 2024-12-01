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
    Schema::table('useritems', function (Blueprint $table) {
        $table->integer('qty')->default(0)->after('ref'); // Tambahkan kolom qty setelah ref
    });
}

public function down(): void
{
    Schema::table('useritems', function (Blueprint $table) {
        $table->dropColumn('qty'); // Hapus kolom qty jika rollback
    });
}

};

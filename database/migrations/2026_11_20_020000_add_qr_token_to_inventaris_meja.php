<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventaris_meja', function (Blueprint $table) {
            $table->uuid('qr_token')->nullable()->unique();
        });

        DB::table('inventaris_meja')
            ->select('id_meja')
            ->whereNull('qr_token')
            ->orderBy('id_meja')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    DB::table('inventaris_meja')
                        ->where('id_meja', $row->id_meja)
                        ->update(['qr_token' => (string) Str::uuid()]);
                }
            }, 'id_meja', 'id_meja');
    }

    public function down(): void
    {
        Schema::table('inventaris_meja', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn('qr_token');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * The database connection that should be used by the migration.
     *
     * @var string|null
     */
    public $connection = null; // Use default connection (Master DB)

    public function up(): void
    {
        // This migration runs on Master DB (default connection)
        Schema::connection(null)->create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->string('db_name');
            $table->string('db_username');
            $table->string('db_password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};

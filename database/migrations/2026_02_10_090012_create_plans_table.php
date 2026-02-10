<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     *
     * @var string|null
     */
    public $connection = null; // Use default connection (Master DB)

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Plans are stored in Master DB
        Schema::connection(null)->create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('billing_cycle'); // monthly, yearly
            $table->integer('max_users')->nullable();
            $table->bigInteger('max_storage')->nullable(); // in bytes
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(null)->dropIfExists('plans');
    }
};

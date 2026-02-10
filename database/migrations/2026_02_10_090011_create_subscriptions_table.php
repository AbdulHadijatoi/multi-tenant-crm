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
        // Subscriptions are stored in Master DB
        Schema::connection(null)->create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('status')->default('active'); // active, past_due, canceled
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('billing_cycle'); // monthly, yearly
            $table->boolean('auto_renew')->default(true);
            $table->string('provider')->default('manual'); // stripe, paypal, manual
            $table->string('provider_ref_id')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('plan_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(null)->dropIfExists('subscriptions');
    }
};

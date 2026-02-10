<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NOTE: This is a TENANT DB migration.
     * This migration should run on tenant databases, NOT on Master DB.
     * Use: php artisan tenant:migrate {tenant_id}
     */
    public function up()
    {
        // Users Table Update (if needed, typically standard Laravel)
        // Assuming 'users' table exists, adding custom fields if not present
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('trader');
                $table->string('kyc_status')->default('pending');
            });
        }

        // Wallets
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('locked_balance', 15, 2)->default(0);
            $table->decimal('commission_balance', 15, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->timestamps();
        });

        // Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('type'); // deposit, withdraw, transfer, profit
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('USD');
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->string('method')->nullable(); // bank, crypto, etc
            $table->text('description')->nullable();
            $table->string('reference_id')->nullable();
            $table->timestamps();
        });

        // Trading Accounts
        Schema::create('trading_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('platform'); // MT4, MT5
            $table->string('login_id')->unique();
            $table->string('server_name');
            $table->string('type')->default('Live'); // Live, Demo
            $table->string('leverage')->default('1:100');
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('equity', 15, 2)->default(0);
            $table->timestamps();
        });

        // Prop Challenges
        Schema::create('prop_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('plan_size'); // 10K, 50K
            $table->string('status'); // active, passed, failed
            $table->integer('phase')->default(1);
            $table->string('account_login')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        // Support Tickets
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('open');
            $table->string('priority')->default('normal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('prop_challenges');
        Schema::dropIfExists('trading_accounts');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wallets');
    }
};

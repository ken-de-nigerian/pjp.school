<?php

declare(strict_types=1);

use App\Support\Coercion;
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
        if (Schema::hasTable('subscription_transactions')) {
            return;
        }

        $tableName = Coercion::string(config('payments.subscriptions.logging.table'), 'subscription_transactions');
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('subscription_code')->unique()->index();
            $table->string('provider')->index();
            $table->string('status')->index();
            $table->string('plan_code')->index();
            $table->string('customer_email');
            $table->decimal('amount', 15);
            $table->string('currency', 3);
            $table->date('next_payment_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Coercion::string(config('payments.subscriptions.logging.table'), 'subscription_transactions'));
    }
};

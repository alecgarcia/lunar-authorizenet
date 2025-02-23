<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Models\Customer;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lunar_authorizenet', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->string('descriptor');
            $table->string('token');
            $table->integer('card_last_four')->nullable();
            $table->dateTime('card_expires_at')->nullable();
            $table->integer('card_bin')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lunar_authorizenet');
    }
};

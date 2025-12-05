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
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        Schema::create('payment_links', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('XAF');
            $table->string('name', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('description')->nullable();
            $table->enum('status', ['pending', 'paid', 'expired', 'canceled'])
                ->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->string('redirect_url_success')->nullable();
            $table->string('redirect_url_failed')->nullable();
            $table->timestamps();
        });
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            // electricity, water, tv, internet…
            $table->string('type');

            $table->string('meter_number')->index()->nullable();

            $table->unsignedInteger('previous_index')->nullable();
            $table->unsignedInteger('current_index')->nullable();

            $table->text('raw_text')->nullable();

            // pending, validated, failed
            $table->string('status')->default('pending');

            $table->timestamps();

           // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            // electricity, water, tv, internet, canal
            $table->string('type')->index();

            // compteur ou numéro d’abonné
            $table->string('account_number')->nullable();

            // si c'est une facture issue d'un relevé OCR
            $table->unsignedBigInteger('meter_reading_id')->nullable();

            $table->unsignedInteger('previous_index')->nullable();
            $table->unsignedInteger('current_index')->nullable();
            $table->unsignedInteger('consumption')->nullable(); // kWh, m³

            $table->double('amount')->default(0);
            $table->string('currency', 10)->default('XAF');

            $table->string('description')->nullable();

            // pending, awaiting_payment, paid, failed
            $table->string('status')->default('pending');

            // opérateur : ENEO, CAMWATER, CANAL+, NEXTTEL…
            $table->string('operator')->nullable();

            // référence interne ou opérateur
            $table->string('payment_reference')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

           // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('meter_reading_id')->references('id')->on('meter_readings')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

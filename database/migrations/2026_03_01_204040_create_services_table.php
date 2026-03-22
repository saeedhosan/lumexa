<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Service;
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
        Schema::create('services', function (Blueprint $table): void {

            $table->id();
            $table->uuid();

            $table->string('name')->index();
            $table->string('slug')->unique();

            $table->string('icon')->nullable();
            $table->string('logo')->nullable();
            $table->string('code')->nullable()->index();
            $table->string('about', 600)->nullable();

            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_default')->default(false)->index();

            $table->string('version', 20)->default(Service::DEFAULT_VERSION);
            $table->string('provider')->nullable();

            $table->json('features')->nullable();
            $table->json('settings')->nullable();

            // metadata only (no FK required)
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();

            $table->timestamps();

            $table->index(['is_active', 'is_default']);
        });

        Schema::create('service_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('action')->nullable();
            $table->text('message')->nullable();
            $table->text('context')->nullable();
            $table->timestamps();
        });

        Schema::create('company_service', function (Blueprint $table): void {
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['company_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_service');
        Schema::dropIfExists('service_logs');
        Schema::dropIfExists('services');
    }
};

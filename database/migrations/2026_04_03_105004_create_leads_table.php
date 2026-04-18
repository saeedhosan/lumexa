<?php

declare(strict_types=1);

use App\Enums\LeadListStatus;
use App\Enums\LeadStatus;
use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
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
        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('status', LeadStatus::values())->default(LeadStatus::default());
            $table->timestamps();
        });

        Schema::create('lead_lists', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Lead::class)->constrained()->cascadeOnDelete();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code', 6)->nullable();
            $table->date('birth_of_date')->nullable();
            $table->enum('status', LeadListStatus::values())->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_lists');
        Schema::dropIfExists('leads');
    }
};

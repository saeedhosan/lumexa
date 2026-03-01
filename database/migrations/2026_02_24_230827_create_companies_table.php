<?php

declare(strict_types=1);

use App\Models\Company;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();

            $table->string('language')->default('en');
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');
            $table->string('country')->default('US');

            $table->json('settings')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });

        Schema::create('company_user', function (Blueprint $table): void {
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete();
            $table->string('role')->default(Company::ROLE_CUSTOMER);
            $table->primary(['user_id', 'company_id']);
            $table->unique(['user_id', 'company_id']);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignIdFor(Company::class, 'current_company_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_user');

        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'current_company_id')) {
                $table->dropForeign(['current_company_id']);
                $table->dropColumn('current_company_id');
            }
        });

        Schema::dropIfExists('companies');
    }
};

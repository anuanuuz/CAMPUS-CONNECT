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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->unique();
            $table->string('username')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            
            // Academic Information
            $table->string('graduation_year', 10)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('degree', 100)->nullable();
            $table->string('student_id', 50)->nullable();
            
            // Profile Information
            $table->text('bio')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('cover_picture')->nullable();
            
            // Contact Information
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50)->default('United States');
            $table->string('postal_code', 20)->nullable();
            
            // Professional Information
            $table->string('company_name', 100)->nullable();
            $table->string('job_title', 100)->nullable();
            $table->string('industry', 100)->nullable();
            $table->string('website')->nullable();
            
            // Social Media Links
            $table->string('linkedin_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('github_url')->nullable();
            
            // System Fields
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->unsignedInteger('login_count')->default(0);
            $table->unsignedTinyInteger('profile_completion')->default(0);
            
            // Privacy & Preferences
            $table->json('privacy_settings')->nullable();
            $table->json('notification_preferences')->nullable();
            $table->string('timezone', 50)->default('America/New_York');
            $table->string('language', 5)->default('en');
            
            // Laravel defaults
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['graduation_year', 'department']);
            $table->index(['city', 'state', 'country']);
            $table->index(['is_active', 'is_featured']);
            $table->index('last_login_at');
            $table->fullText(['first_name', 'last_name', 'company_name', 'job_title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
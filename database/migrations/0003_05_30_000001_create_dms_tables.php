<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dms_document_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('dms_document_categories')->nullOnDelete();
            $table->string('code')->nullable()->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('dms_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('dms_documents')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('dms_document_categories')->nullOnDelete();
            $table->string('doc_number')->unique();
            $table->string('title');
            $table->string('dms_document_type')->index();
            $table->string('status', 30)->default('draft')->index();
            $table->boolean('is_controlled')->default(true);
            $table->foreignId('team_id')->nullable()->constrained('org_teams')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('current_version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->string('site_location')->nullable();
            $table->string('security_classification')->default('internal');
            $table->string('regulatory_impact')->nullable();
            $table->json('risk_assessment_tags')->nullable();
            $table->boolean('requires_training')->default(false);
            $table->unsignedInteger('retention_period_years')->default(5);
            $table->date('effective_date')->nullable();
            $table->date('next_review_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('obsolete_date')->nullable();
            $table->string('regulatory_hash', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['team_id', 'status']);
            $table->index(['next_review_date', 'status']);
        });

        Schema::create('dms_document_approvals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dms_document_id')->constrained('dms_documents')->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->foreignId('approver_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('status', 30)->default('pending')->index();
            $table->timestamp('approved_at')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        Schema::create('dms_document_signatures', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dms_document_id')->constrained('dms_documents')->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->foreignId('signed_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('signed_at')->nullable();
            $table->string('signature_hash', 64);
            $table->string('purpose')->nullable();
            $table->timestamps();
        });

        Schema::create('dms_document_distributions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dms_document_id')->constrained('dms_documents')->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('org_teams')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('distributed_at')->nullable();
            $table->timestamps();
            $table->index(['team_id', 'staff_id']);
        });

        Schema::create('dms_document_acknowledgements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dms_document_id')->constrained('dms_documents')->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
            $table->unique(['version_id', 'staff_id']);
        });

        Schema::create('dms_document_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dms_document_id')->constrained('dms_documents')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('status', 30)->default('open')->index();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('dms_document_trainings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dms_document_id')->constrained('dms_documents')->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->boolean('required')->default(true);
            $table->timestamps();
        });

        Schema::create('dms_document_attachments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dms_document_id')->constrained('dms_documents')->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->string('name');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('checksum')->nullable();
            $table->timestamps();
        });

        Schema::create('dms_document_retentions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('dms_document_id')->constrained('dms_documents')->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->date('retain_until')->nullable();
            $table->string('status', 30)->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dms_document_retentions');
        Schema::dropIfExists('dms_document_attachments');
        Schema::dropIfExists('dms_document_trainings');
        Schema::dropIfExists('dms_document_reviews');
        Schema::dropIfExists('dms_document_acknowledgements');
        Schema::dropIfExists('dms_document_distributions');
        Schema::dropIfExists('dms_document_signatures');
        Schema::dropIfExists('dms_document_approvals');
        Schema::dropIfExists('dms_documents');
        Schema::dropIfExists('dms_document_categories');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_revisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('revision', 50);
            $table->string('status', 40)->default('draft')->index();
            $table->string('file_disk')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('checksum', 64)->nullable()->index();
            $table->text('change_summary')->nullable();
            $table->json('question_bank')->nullable();
            $table->timestampTz('issued_at')->nullable();
            $table->timestampTz('effective_at')->nullable()->index();
            $table->timestampTz('expires_at')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('approved_by')->nullable()->index();
            $table->timestampTz('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['document_id', 'revision']);
        });

        Schema::table('documents', function (Blueprint $table): void {
            $table->foreign('current_revision_id')
                ->references('id')
                ->on('document_revisions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table): void {
            $table->dropForeign(['current_revision_id']);
        });

        Schema::dropIfExists('document_revisions');
    }
};

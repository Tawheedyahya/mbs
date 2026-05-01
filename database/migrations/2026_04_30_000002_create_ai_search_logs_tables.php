<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_search_query_logs', function (Blueprint $table) {
            $table->id();
            $table->string('conversation_id', 100)->nullable()->index();
            $table->unsignedBigInteger('hospital_id')->nullable()->index();
            $table->text('query_text');
            $table->text('normalized_query')->nullable();
            $table->string('intent', 100)->nullable()->index();
            $table->decimal('semantic_score', 8, 5)->nullable();
            $table->string('semantic_method', 50)->nullable();
            $table->text('matched_example')->nullable();
            $table->json('plan_json')->nullable();
            $table->mediumText('sql_text')->nullable();
            $table->json('parameters_json')->nullable();
            $table->json('result_json')->nullable();
            $table->mediumText('answer_text')->nullable();
            $table->json('chart_hint_json')->nullable();
            $table->string('status', 30)->default('started')->index();
            $table->mediumText('error_text')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->timestamps();

            $table->index(['hospital_id', 'created_at']);
            $table->index(['intent', 'created_at']);
            $table->index(['status', 'created_at']);
        });

        Schema::create('ai_search_query_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_log_id')->index();
            $table->unsignedBigInteger('hospital_id')->nullable()->index();
            $table->boolean('is_helpful')->nullable();
            $table->string('corrected_intent', 100)->nullable();
            $table->mediumText('corrected_answer')->nullable();
            $table->text('feedback_text')->nullable();
            $table->timestamps();

            $table->index(['hospital_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_search_query_feedback');
        Schema::dropIfExists('ai_search_query_logs');
    }
};

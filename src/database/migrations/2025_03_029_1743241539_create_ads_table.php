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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable()->unique()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('additional_information')->nullable();
            $table->string('app_id')->nullable();
            $table->string('click_url')->nullable();
            $table->string('creatives_url')->nullable();
            $table->text('kpi')->nullable();
            $table->string('leadflow')->nullable();
            $table->decimal('payout', 10, 2)->nullable();
            $table->string('payout_currency')->nullable();
            $table->string('preview_url')->nullable();
            $table->text('restrictions')->nullable();
            $table->json('targeting')->nullable();
            $table->json('app_categories')->nullable();
            $table->json('icons')->nullable();
            $table->json('thumbs')->nullable();
            $table->json('event_types')->nullable();
            $table->json('landing_page_html_templates')->nullable();
            $table->json('sub_sources')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
}; 
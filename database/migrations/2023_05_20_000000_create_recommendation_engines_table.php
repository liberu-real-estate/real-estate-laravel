<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendationEnginesTable extends Migration
{
    public function up()
    {
        Schema::create('recommendation_engines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('preferences')->nullable();
            $table->json('search_history')->nullable();
            $table->json('browsing_behavior')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recommendation_engines');
    }
}
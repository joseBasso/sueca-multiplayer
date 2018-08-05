<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nickname')->unique();
            $table->boolean('admin')->default(false);
            $table->boolean('blocked')->default(false);
            $table->string('reason_blocked')->nullable();
            $table->string('reason_reactivated')->nullable();
            $table->integer('total_points')->default(0);
            $table->integer('total_games_played')->default(0);
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('platform_email');
            $table->string('platform_email_properties');
            $table->string('img_base_path');
            $table->timestamps();
        });

        Schema::create('decks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('hidden_face_image_path');
            $table->boolean('active')->default(true);
            $table->boolean('complete')->default(false);
            $table->timestamps();
        });

        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('value', ['Ace','2','3','4','5','6','7','Jack','Queen','King']);
            $table->enum('suite', ['Club','Diamond','Heart','Spade']);
            $table->integer('deck_id')->unsigned();
            $table->foreign('deck_id')->references('id')->on('decks')->onDelete('cascade');
            $table->string('path');
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['pending', 'active', 'terminated', 'canceled'])->default('pending');
              //Nº de pontos das cartas
            $table->integer('team1_cardpoints')->nullable();
            $table->integer('team2_cardpoints')->nullable();
              //Nº da equipa que ganhou
            $table->integer('team_winner')->nullable();
              //Nº da equipa que desconfiou (null-> nenhuma equipa desconfiou)
            $table->integer('team_desconfiou')->nullable();
              //Nº da equipa que renunciou (null-> nenhuma equipa renunciou)
            $table->integer('team_renunciou')->nullable();
              //Nº de pontos finais do jogo (a atribuir aos jogadores)
              //Valores possíveis (-4, 0, 1, 2, 4)
            $table->integer('team1_points')->nullable();
            $table->integer('team2_points')->nullable();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('deck_used')->unsigned();
            $table->foreign('deck_used')->references('id')->on('decks');
            $table->timestamps();
        });

        Schema::create('game_user', function (Blueprint $table) {
            $table->integer('game_id')->unsigned();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('team_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_user');
        Schema::dropIfExists('games');
        Schema::dropIfExists('cards');
        Schema::dropIfExists('decks');
        Schema::dropIfExists('config');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
}

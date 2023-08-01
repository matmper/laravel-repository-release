<?php

declare(strict_types=1);

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code', 2)->unique();
            $table->timestamps();
        });
    }

    /**
     * Rollback the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
}

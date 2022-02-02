<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMediasTable
 */
class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::SYS_MEDIAS,
            function (Blueprint $table) {
                $table->id();
                $table->string('filename');
                $table->string('path');
                $table->bigInteger('mediaable_id')->nullable();
                $table->string('mediaable_type')->nullable();
                $table->string('field_name')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DBTables::SYS_MEDIAS);
    }
}

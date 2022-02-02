<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateApplicantExportsTable
 */
class CreateApplicantExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::APPLICANT_EXPORTS,
            function (Blueprint $table) {
                $table->id();
                $table->string('filename');
                $table->string('path');
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
        Schema::dropIfExists(DBTables::APPLICANT_EXPORTS);
    }
}

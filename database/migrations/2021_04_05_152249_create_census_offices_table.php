<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCensusOfficesTable
 */
class CreateCensusOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::CENSUS_OFFICES,
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('office_name')->unique();
                $table->foreignId('district_code')->references('code')->on(DBTables::DISTRICTS)->onDelete('cascade');
                Helper::commonMigration($table, false, false);
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
        Schema::dropIfExists(DBTables::CENSUS_OFFICES);
    }
}

<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddCensusCensusHouseNumberToHouseDailyReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(DBTables::HOUSE_DAILY_REPORTS, function (Blueprint $table) {
            $table->integer('number_of_houses_in_census')->nullable();
            $table->integer('number_of_families_in_census')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(DBTables::HOUSE_DAILY_REPORTS, function (Blueprint $table) {
            $table->dropColumn('number_of_houses_in_census');
            $table->dropColumn('number_of_families_in_census');
        });
    }
}

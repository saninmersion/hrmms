<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddCensusAreaToDailyReportsTable
 */
class AddCensusAreaToDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            DBTables::DAILY_REPORTS,
            function (Blueprint $table) {
                $table->integer("district_code")->nullable();
                $table->integer("census_office_id")->nullable();
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
        Schema::table(
            DBTables::DAILY_REPORTS,
            function (Blueprint $table) {
                $table->dropColumn("district_code");
                $table->dropColumn("census_office_id");
            }
        );
    }
}

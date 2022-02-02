<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DBTables::HOUSE_DAILY_REPORTS, function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained(DBTables::AUTH_USERS, 'id')->onDelete('cascade');
            $table->integer('total_surveyed');
            $table->date('report_date');
            $table->foreignId('district_code')->nullable()->references('code')->on(DBTables::DISTRICTS)->onDelete('cascade');
            $table->foreignId("census_office_id")->nullable()->references('id')->on(DBTables::CENSUS_OFFICES)->onDelete('cascade');
            Helper::commonMigration($table, true, false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DBTables::HOUSE_DAILY_REPORTS);
    }
}

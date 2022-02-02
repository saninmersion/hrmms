<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::DAILY_REPORTS,
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('created_by')->constrained(DBTables::AUTH_USERS, 'id')->onDelete('cascade');
                $table->integer('total_surveyed');
                $table->date('report_date');
                Helper::commonMigration($table, true, false);
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
        Schema::dropIfExists(DBTables::DAILY_REPORTS);
    }
}

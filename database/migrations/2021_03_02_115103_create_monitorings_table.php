<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::MONITORINGS,
            function (Blueprint $table) {
                $table->id();
                $table->string('monitoring_form');
                $table->date('monitoring_date');
                $table->foreignId('district_code')->constrained(DBTables::DISTRICTS, 'code')->onDelete('cascade');
                $table->foreignId('municipality_code')->constrained(DBTables::MUNICIPALITIES, 'code')->onDelete('cascade');
                $table->integer('ward');
                $table->string('census_area');
                $table->jsonb('monitoring_data')->nullable();
                $table->foreignId('monitored_by_id')->constrained(DBTables::AUTH_USERS)->onDelete('cascade');
                $table->foreignId('entered_by_id')->constrained(DBTables::AUTH_USERS)->onDelete('cascade');
                Helper::commonMigration($table, true, true);
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
        Schema::dropIfExists(DBTables::MONITORINGS);
    }
}

<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnumeratorIdToDailyReportsTable extends Migration
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
                $table->foreignId('enumerator_id')->nullable()->constrained(DBTables::ENUMERATORS, 'id')->onDelete('cascade');
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
                $table->dropColumn("enumerator_id");
            }
        );
    }
}

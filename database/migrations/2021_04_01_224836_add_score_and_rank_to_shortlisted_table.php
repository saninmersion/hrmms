<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddScoreAndRankToShortlistedTable
 */
class AddScoreAndRankToShortlistedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            DBTables::SHORTLISTED,
            function (Blueprint $table) {
                $table->integer('rank')->nullable();
                $table->decimal('score', 6, 3)->nullable();
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
            DBTables::SHORTLISTED,
            function (Blueprint $table) {
                $table->dropColumn('score');
                $table->dropColumn('rank');
            }
        );
    }
}

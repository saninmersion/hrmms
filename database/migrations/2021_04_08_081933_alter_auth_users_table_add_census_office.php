<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterAuthUsersTableAddCensusOffice
 */
class AlterAuthUsersTableAddCensusOffice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            DBTables::AUTH_USERS,
            function (Blueprint $table) {
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
            DBTables::AUTH_USERS,
            function (Blueprint $table) {
                $table->dropColumn("census_office_id");
            }
        );
    }
}

<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddDistrictCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(
            DBTables::AUTH_USERS,
            function (Blueprint $table) {
                $table->foreignId('district_code')->nullable()->references('code')->on(
                    DBTables::DISTRICTS
                )->onDelete('cascade');
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
        //
        Schema::table(DBTables::AUTH_USERS, function (Blueprint $table) {
            $table->dropColumn(['district_code']);
        });
    }
}

<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTblApplicationsAddIsOffline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(DBTables::APPLICATIONS, function(Blueprint $table) {
            $table->boolean("is_offline")->nullable();
            $table->foreignId('entered_by_id')->nullable()->constrained(DBTables::AUTH_USERS)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(DBTables::APPLICATIONS, function(Blueprint $table) {
            $table->dropColumn("is_offline");
            $table->dropColumn("entered_by_id");
        });
    }
}

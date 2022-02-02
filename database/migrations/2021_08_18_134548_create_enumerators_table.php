<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnumeratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::ENUMERATORS, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('target')->default(0);
            $table->foreignId('supervisor_id')->nullable()->constrained(DBTables::AUTH_USERS, 'id')->onDelete('cascade');
            Helper::commonMigration($table, true, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DBTables::ENUMERATORS);
    }
}

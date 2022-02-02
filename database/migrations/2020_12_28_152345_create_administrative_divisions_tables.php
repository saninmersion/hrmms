<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAdministrativeDivisionsTables
 */
class CreateAdministrativeDivisionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::PROVINCES,
            function (Blueprint $table) {
                $table->id();
                $table->integer('code')->unique();
                $table->string('title_en');
                $table->string('title_ne');
                Helper::commonMigration($table, false, false);
            }
        );

        Schema::create(
            DBTables::DISTRICTS,
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('province_code')->nullable()->references('code')->on(DBTables::PROVINCES)->onDelete(
                    'cascade'
                );
                $table->integer('dist_id');
                $table->integer('code')->unique();
                $table->string('title_en');
                $table->string('title_ne');
                $table->boolean('is_old_district')->default(false);
                Helper::commonMigration($table, false, false);
            }
        );

        Schema::create(
            DBTables::MUNICIPALITIES,
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('province_code')->references('code')->on(DBTables::PROVINCES)->onDelete('cascade');
                $table->foreignId('district_code')->references('code')->on(DBTables::DISTRICTS)->onDelete('cascade');
                $table->integer('mun_id');
                $table->integer('code')->unique();
                $table->string('title_en');
                $table->string('title_ne');
                $table->integer('total_wards');
                Helper::commonMigration($table, false, false);
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
        Schema::dropIfExists(DBTables::MUNICIPALITIES);
        Schema::dropIfExists(DBTables::DISTRICTS);
        Schema::dropIfExists(DBTables::PROVINCES);
    }
}

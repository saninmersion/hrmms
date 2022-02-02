<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateShortlistedTable
 */
class CreateShortlistedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::SHORTLISTED,
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('applicant_id')->nullable()->references('id')->on(DBTables::AUTH_APPLICANTS)->onDelete('cascade');
                $table->foreignId('municipality_code')->nullable()->references('code')->on(DBTables::MUNICIPALITIES)->onDelete('cascade');
                $table->string('role');
                $table->boolean('is_shortlisted')->default(true);
                $table->string('hiring_status')->nullable();
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
        Schema::dropIfExists(DBTables::SHORTLISTED);
    }
}

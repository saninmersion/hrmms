<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateApplicantsTable
 */
class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DBTables::AUTH_APPLICANTS, function (Blueprint $table) {
            $table->id();
            $table->string('citizenship_number');
            $table->string('citizenship_issued_date_bs')->nullable();
            $table->foreignId('citizenship_issued_district_code')->nullable()->index()->references('code')->on(DBTables::DISTRICTS)->onDelete('cascade');

            $table->unique(["citizenship_number", "citizenship_issued_district_code"], 'unique_citizenship');

            $table->string('dob_bs')->nullable();
            $table->string('mobile_number')->nullable()->index();
            $table->jsonb('details')->nullable();
            $table->jsonb('permanent_address')->nullable();
            $table->jsonb('temporary_address')->nullable();
            $table->jsonb('current_address')->nullable();
            $table->string('photo_file')->nullable();
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
        Schema::table(DBTables::AUTH_APPLICANTS, function (Blueprint $table) {
            $table->dropUnique('unique_citizenship');
        });

        Schema::dropIfExists(DBTables::AUTH_APPLICANTS);
    }
}

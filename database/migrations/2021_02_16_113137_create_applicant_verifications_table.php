<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateApplicantVerificationsTable
 */
class CreateApplicantVerificationsTable extends Migration
{
    public function up()
    {
        Schema::create(
            DBTables::APPLICANT_VERIFICATIONS,
            function (Blueprint $table) {
                $table->id();
                $table->jsonb('modified')->nullable();
                $table->jsonb('checklist')->nullable();
                $table->string('verification_status');
                $table->text('remarks')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->bigInteger('verifier_id')->nullable()->index();
                $table->foreign('verifier_id')->references('id')->on(DBTables::AUTH_USERS)->onDelete('cascade')->nullable();
                $table->foreignId('applicant_id')->constrained(DBTables::AUTH_APPLICANTS)->onDelete('cascade');

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
        Schema::dropIfExists(DBTables::APPLICANT_VERIFICATIONS);
    }
}

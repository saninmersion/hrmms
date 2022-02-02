<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerifierAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::VERIFIER_ASSIGNMENT, function (Blueprint $table) {
            $table->id();
            $table->foreignId('verifier_id')->constrained(DBTables::AUTH_USERS)->onDelete('cascade');
            $table->foreignId('applicant_id')->constrained(DBTables::AUTH_APPLICANTS)->onDelete('cascade');
            Helper::commonMigration($table, false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DBTables::VERIFIER_ASSIGNMENT);
    }
}

<?php

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\StatusTypes;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateApplicationsTable
 */
class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DBTables::APPLICATIONS, function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained(DBTables::AUTH_APPLICANTS)->onDelete('cascade');
            $table->boolean('for_supervisor')->default(false);
            $table->boolean('for_enumerator')->default(false);
            $table->jsonb('locations')->nullable();
            $table->string('status')->default(StatusTypes::APPLICATION_DRAFT);
            $table->timestamp('application_date')->nullable();
            $table->jsonb('official')->nullable();
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
        Schema::dropIfExists(DBTables::APPLICATIONS);
    }
}

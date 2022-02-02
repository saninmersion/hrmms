<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateApplicationDownloadLogTable
 */
class CreateApplicationDownloadLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::APPLICATION_DOWNLOAD_LOGS,
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('export_id')->constrained(DBTables::APPLICANT_EXPORTS)->onDelete('cascade');
                $table->foreignId('user_id')->constrained(DBTables::AUTH_USERS)->onDelete('cascade');
                $table->timestamps();
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
        Schema::dropIfExists(DBTables::APPLICATION_DOWNLOAD_LOGS);
    }
}

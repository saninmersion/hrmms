<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddExportedAtInExportsTable
 */
class AddExportedAtInExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            DBTables::APPLICANT_EXPORTS,
            function (Blueprint $table) {
                $table->timestamp('exported_at')->nullable();
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
        Schema::table(
            DBTables::APPLICANT_EXPORTS,
            function (Blueprint $table) {
                $table->dropColumn('exported_at');
            }
        );
    }
}

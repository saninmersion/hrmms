<?php

use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            DBTables::AUTH_USERS,
            function (Blueprint $table) {
                $table->id();
                $table->string('role')->default(AuthRoles::STAFFS);
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('username')->unique();
                $table->string('password');
                $table->text('profile_photo_path')->nullable();
                $table->rememberToken();
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
        Schema::dropIfExists(DBTables::AUTH_USERS);
    }
}

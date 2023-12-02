<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('consecutive')->unique()->nullable();
            $table->unsignedBigInteger('from_user_id');
            $table->datetime('from_date');
            $table->string('from_observation')->nullable();
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->datetime('to_date')->nullable();
            $table->string('to_observation')->nullable();
            $table->string('status');
            $table->foreign('from_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::unprepared('DROP PROCEDURE IF EXISTS transfers');

        DB::unprepared('
            CREATE PROCEDURE transfers(IN idParam INT)
            BEGIN
                DECLARE currentDate CHAR(8);
                DECLARE lastConsecutive CHAR(12);
                DECLARE registrationNumber INT;

                -- Get the current date in "Ymd" format
                SET currentDate = DATE_FORMAT(NOW(), "%Y%m%d");

                -- Get the last consecutive for the provided ID and current date
                SELECT consecutive INTO lastConsecutive 
                FROM transfers 
                WHERE id = idParam AND DATE_FORMAT(created_at, "%Y%m%d") = currentDate
                ORDER BY created_at DESC 
                LIMIT 1;

                -- Get the registration number (last 4 digits of the consecutive)
                SET registrationNumber = IFNULL(CAST(SUBSTRING(lastConsecutive, -4) AS UNSIGNED) + 1, 1);

                -- Format the registration number to 4 digits with leading zeros
                SET registrationNumber = LPAD(registrationNumber, 4, "0");

                -- Build the new consecutive
                SET @new_consecutive = CONCAT(currentDate, registrationNumber);

                -- Save the new consecutive in the "consecutive" field of the "transfers" table
                UPDATE transfers SET consecutive = @new_consecutive WHERE id = idParam;
            END
        ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
};

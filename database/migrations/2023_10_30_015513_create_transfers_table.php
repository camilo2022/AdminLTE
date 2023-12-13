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
            $table->unsignedBigInteger('from_warehouse_id');
            $table->unsignedBigInteger('from_user_id');
            $table->datetime('from_date');
            $table->string('from_observation')->nullable();
            $table->unsignedBigInteger('to_warehouse_id');
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->datetime('to_date')->nullable();
            $table->string('to_observation')->nullable();
            $table->string('status');
            $table->foreign('from_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::unprepared('DROP PROCEDURE IF EXISTS transfers');

        DB::unprepared('
            CREATE PROCEDURE transfers()  
                BEGIN
                DECLARE consecutive VARCHAR(50);

                SET @last_consecutive = (SELECT transfers.consecutive 
                                         FROM transfers
                                         WHERE transfers.created_at = (SELECT MAX(created_at) 
                                                                        FROM transfers 
                                                                        WHERE DATE(created_at) = CURRENT_DATE()));
              
                IF @last_consecutive IS NULL THEN
                  SET consecutive = CONCAT(YEAR(NOW()), LPAD(MONTH(NOW()), 2, "0"), LPAD(DAY(NOW()), 2, "0"), "001");  
                ELSE 
                    SET @number = CAST(SUBSTR(@last_consecutive, 9) AS UNSIGNED) + 1;
                  
                    IF @number >= 1 AND @number < 10 THEN
                        SET consecutive = CONCAT(SUBSTR(@last_consecutive, 1, 8), LPAD(@number, 3, "0"));
                    ELSEIF @number >= 10 AND @number < 100 THEN  
                        SET consecutive = CONCAT(SUBSTR(@last_consecutive, 1, 8), LPAD(@number, 2, "0"));
                    ELSE
                        SET consecutive = CONCAT(SUBSTR(@last_consecutive, 1, 8), @number);
                    END IF;
                END IF;

                SELECT consecutive; 
            
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

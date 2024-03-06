<?php

use App\Models\Order;
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
        Schema::create('order_dispatches', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_id'); */
            $table->foreignIdFor(Order::class)->constrained();
            $table->unsignedBigInteger('dispatch_user_id');
            $table->enum('dispatch_status', ['Pendiente', 'Rechazado', 'Cancelado', 'Aprobado', 'Empacado', 'Despachado'])->default('Pendiente');
            $table->datetime('dispatch_date')->nullable();
            $table->string('consecutive')->unique();
            $table->enum('payment_status', ['Pendiente de Pago', 'Pagado', 'Parcialmente Pagado', 'Cancelado', 'Reembolsado'])->default('Pendiente de Pago');
            /* $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade'); */
            $table->foreign('dispatch_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        DB::unprepared('DROP PROCEDURE IF EXISTS order_dispatches');

        DB::unprepared('
            CREATE PROCEDURE order_dispatches()
                BEGIN
                DECLARE consecutive VARCHAR(50);

                SET @last_consecutive = (SELECT order_dispatches.consecutive
                                         FROM order_dispatches
                                         WHERE order_dispatches.created_at = (SELECT MAX(created_at)
                                                                        FROM order_dispatches
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

        DB::unprepared('DROP PROCEDURE IF EXISTS order_dispatch_status');

        DB::unprepared('
            CREATE PROCEDURE order_dispatch_status(IN order_dispatch_id INT, order_id INT)
            BEGIN
                DECLARE totalRevision INT;
                DECLARE totalAprobado INT;

                SELECT COUNT(*) INTO totalRevision FROM order_details WHERE order_id = order_id AND status = "Revision";
                SELECT COUNT(*) INTO totalAprobado FROM order_details WHERE order_id = order_id AND status = "Aprobado";

                IF totalRevision = 0 AND totalAprobado = 0 THEN
                    UPDATE order_dispatch_details SET status = "Aprobado" WHERE order_dispatch_id = order_dispatch_id;
                END IF;
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
        Schema::dropIfExists('order_dispatches');
    }
};

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->comment('Identificador del pedido.');
            $table->unsignedBigInteger('client_id')->comment('Identificador del cliente.');
            $table->unsignedBigInteger('client_branch_id')->comment('Identificador de la sucursal del cliente.');
            $table->unsignedBigInteger('sale_channel_id')->comment('Identificador del canal de venta del pedido');
            $table->enum('dispatch', ['De inmediato', 'Antes de', 'Despues de'])->comment('Cuando despachar.');
            $table->date('dispatch_date')->nullable()->comment('Fecha de cuando despachar.');
            $table->unsignedBigInteger('seller_user_id')->comment('Identificador del usuario de vendedor.');
            $table->enum('seller_status', ['Pendiente', 'Cancelado', 'Aprobado'])->default('Pendiente')->comment('Estado del vendedor.');
            $table->datetime('seller_date')->comment('Fecha del vendedor.');
            $table->string('seller_observation')->nullable()->comment('Observacion del vendedor.');
            $table->unsignedBigInteger('wallet_user_id')->nullable()->comment('Identificador del usuario de cartera.');
            $table->enum('wallet_status', ['Pendiente', 'Cancelado', 'Parcialmente Aprobado', 'Aprobado'])->default('Pendiente')->comment('Estado de cartera.');
            $table->datetime('wallet_date')->nullable()->comment('Fecha de cartera');
            $table->string('wallet_observation')->nullable()->comment('Observacion de cartera');
            $table->enum('dispatched_status', ['Pendiente', 'Cancelado', 'Parcialmente Aprobado', 'Aprobado', 'Parcialmente Devuelto', 'Devuelto', 'Parcialmente Despachado', 'Despachado'])->default('Pendiente')->comment('Estado de despacho.');
            $table->datetime('dispatched_date')->nullable()->comment('Fecha de despacho.');
            $table->unsignedBigInteger('correria_id')->comment('Identificador de la correria.');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('client_branch_id')->references('id')->on('client_branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sale_channel_id')->references('id')->on('sale_channels')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('seller_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('wallet_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('correria_id')->references('id')->on('correrias')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        DB::unprepared('DROP PROCEDURE IF EXISTS order_seller_status');

        DB::unprepared('
            CREATE PROCEDURE order_seller_status(IN order_id INT)
            BEGIN
                DECLARE cntCancelados INT;
                DECLARE totalDetalles INT;

                SELECT COUNT(*) INTO cntCancelados FROM order_details WHERE order_id = order_id AND status = "Cancelado";
                SELECT COUNT(*) INTO totalDetalles FROM order_details WHERE order_id = order_id;

                IF cntCancelados = totalDetalles THEN
                    UPDATE orders SET seller_status = "Cancelado", wallet_status = "Cancelado", dispatched_status = "Cancelado" WHERE id = order_id;
                END IF;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS order_wallet_status');

        DB::unprepared('
            CREATE PROCEDURE order_wallet_status(IN order_id INT)
            BEGIN
                DECLARE cntPendiente INT;
                DECLARE cntRevision INT;
                DECLARE cntAprobado INT;
                DECLARE cntFiltrado INT;
                DECLARE cntEmpacado INT;
                DECLARE cntDevuelto INT;
                DECLARE cntDespachado INT;
                DECLARE cntCanceladoRechazado INT;
                DECLARE totalDetalles INT;

                SELECT COUNT(*) INTO cntPendiente FROM order_details WHERE order_id = order_id AND status = "Pendiente";
                SELECT COUNT(*) INTO cntRevision FROM order_details WHERE order_id = order_id AND status = "Revision";
                SELECT COUNT(*) INTO cntAprobado FROM order_details WHERE order_id = order_id AND status = "Aprobado";
                SELECT COUNT(*) INTO cntFiltrado FROM order_details WHERE order_id = order_id AND status = "Filtrado";
                SELECT COUNT(*) INTO cntEmpacado FROM order_details WHERE order_id = order_id AND status = "Empacado";
                SELECT COUNT(*) INTO cntDevuelto FROM order_details WHERE order_id = order_id AND status = "Devuelto";
                SELECT COUNT(*) INTO cntDespachado FROM order_details WHERE order_id = order_id AND status = "Despachado";
                SELECT COUNT(*) INTO cntCanceladoRechazado FROM order_details WHERE order_id = order_id AND (status = "Cancelado" OR status = "Rechazado");
                SELECT COUNT(*) INTO totalDetalles FROM order_details WHERE order_id = order_id;

                IF cntCanceladoRechazado = totalDetalles THEN
                    UPDATE orders SET wallet_status = "Cancelado", dispatched_status = "Cancelado" WHERE id = order_id;
                ELSEIF cntPendiente = 0 AND cntRevision = 0 THEN
                    IF cntAprobado > 0 OR cntFiltrado > 0 OR cntEmpacado > 0 OR cntDevuelto > 0 OR cntDespachado > 0 THEN
                        UPDATE orders SET wallet_status = "Aprobado" WHERE id = order_id;
                    ELSE
                        UPDATE orders SET wallet_status = "Cancelado", dispatched_status = "Cancelado" WHERE id = order_id;
                    END IF;
                ELSE
                    UPDATE orders SET wallet_status = "Parcialmente Aprobado" WHERE id = order_id;
                END IF;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS order_dispatched_status');

        DB::unprepared('
            CREATE PROCEDURE order_dispatched_status(IN order_id INT)
            BEGIN
                DECLARE cntFiltrado INT;
                DECLARE cntEmpacado INT;
                DECLARE cntAprobado INT;
                DECLARE cntDespachado INT;
                DECLARE cntDevuelto INT;
                DECLARE cntCanceladoRechazado INT;
                DECLARE totalDetalles INT;
                DECLARE porcentajeDespachado DECIMAL(5,2);
                DECLARE porcentajeDevuelto DECIMAL(5,2);

                SELECT COUNT(*) INTO cntFiltrado FROM order_details WHERE order_id = order_id AND status = "Filtrado";
                SELECT COUNT(*) INTO cntEmpacado FROM order_details WHERE order_id = order_id AND status = "Empacado";
                SELECT COUNT(*) INTO cntAprobado FROM order_details WHERE order_id = order_id AND status = "Aprobado";
                SELECT COUNT(*) INTO cntDespachado FROM order_details WHERE order_id = order_id AND status = "Despachado";
                SELECT COUNT(*) INTO cntDevuelto FROM order_details WHERE order_id = order_id AND status = "Devuelto";
                SELECT COUNT(*) INTO cntCanceladoRechazado FROM order_details WHERE order_id = order_id AND (status = "Cancelado" OR status = "Rechazado");
                SELECT COUNT(*) INTO totalDetalles FROM order_details WHERE order_id = order_id;

                SET porcentajeDespachado = IF(totalDetalles > 0, (cntDespachado / totalDetalles) * 100, 0);
                SET porcentajeDevuelto = IF(totalDetalles > 0, (cntDevuelto / (cntDevuelto + cntDespachado)) * 100, 0);

                IF cntCanceladoRechazado > 0 THEN
                    UPDATE orders SET dispatched_status = "Cancelado" WHERE id = order_id;
                ELSEIF cntDevuelto > 0 AND porcentajeDevuelto > 50 THEN
                    UPDATE orders SET dispatched_status = "Parcialmente Devuelto" WHERE id = order_id;
                ELSEIF cntDevuelto = totalDetalles THEN
                    UPDATE orders SET dispatched_status = "Devuelto" WHERE id = order_id;
                ELSEIF porcentajeDespachado > 50 THEN
                    UPDATE orders SET dispatched_status = "Parcialmente Despachado" WHERE id = order_id;
                ELSEIF cntDespachado = totalDetalles THEN
                    UPDATE orders SET dispatched_status = "Despachado" WHERE id = order_id;
                ELSEIF cntFiltrado > 0 OR cntEmpacado > 0 THEN
                    IF cntAprobado > 0 THEN
                        UPDATE orders SET dispatched_status = "Parcialmente Aprobado" WHERE id = order_id;
                    ELSE
                        UPDATE orders SET dispatched_status = "Aprobado" WHERE id = order_id;
                    END IF;
                ELSE
                    UPDATE orders SET dispatched_status = "Pendiente" WHERE id = order_id;
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
        Schema::dropIfExists('orders');
    }
};

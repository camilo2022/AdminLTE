<?php

use App\Models\Client;
use App\Models\ClientBranch;
use App\Models\Correria;
use App\Models\SaleChannel;
use App\Models\Transporter;
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
            /* $table->unsignedBigInteger('client_id')->comment('Identificador del cliente.');
            $table->unsignedBigInteger('client_branch_id')->comment('Identificador de la sucursal del cliente.');
            $table->unsignedBigInteger('transporter_id')->comment('Identificador de la transportadora del pedido');
            $table->unsignedBigInteger('sale_channel_id')->comment('Identificador del canal de venta del pedido'); */
            $table->foreignIdFor(Client::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(ClientBranch::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Transporter::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(SaleChannel::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
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

            $table->enum('dispatched_status', ['Pendiente', 'Cancelado', 'Rechazado', 'Parcialmente Aprobado', 'Aprobado', 'Parcialmente Empacado', 'Empacado', 'Parcialmente Devuelto', 'Devuelto', 'Parcialmente Despachado', 'Despachado'])->default('Pendiente')->comment('Estado de despacho.');
            
            $table->datetime('dispatched_date')->nullable()->comment('Fecha de despacho.');
            /* $table->unsignedBigInteger('correria_id')->comment('Identificador de la correria.'); */
            $table->foreignIdFor(Correria::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            /* $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('client_branch_id')->references('id')->on('client_branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transporter_id')->references('id')->on('transporters')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sale_channel_id')->references('id')->on('sale_channels')->onUpdate('cascade')->onDelete('cascade'); */
            $table->foreign('seller_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('wallet_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            /* $table->foreign('correria_id')->references('id')->on('correrias')->onUpdate('cascade')->onDelete('cascade'); */
            $table->timestamps();
        });

        DB::unprepared('DROP PROCEDURE IF EXISTS order_seller_status');

        DB::unprepared('
            CREATE PROCEDURE order_seller_status(IN order_id INT)
            BEGIN
                DECLARE totalCancelados INT;
                DECLARE totalDetalles INT;

                SELECT COUNT(*) INTO totalCancelados FROM order_details WHERE order_details.order_id = order_id AND status = "Cancelado";
                SELECT COUNT(*) INTO totalDetalles FROM order_details WHERE order_details.order_id = order_id;

                IF totalCancelados = totalDetalles THEN
                    UPDATE orders SET seller_status = "Cancelado", wallet_status = "Cancelado", dispatched_status = "Cancelado" WHERE id = order_id;
                END IF;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS order_wallet_status');

        DB::unprepared('
            CREATE PROCEDURE order_wallet_status(IN order_id INT)
            BEGIN
                DECLARE totalPendienteRevision INT;
                DECLARE totalAprobado INT;
                DECLARE totalFiltrado INT;
                DECLARE totalEmpacado INT;
                DECLARE totalDevuelto INT;
                DECLARE totalDespachado INT;
                DECLARE totalCanceladoRechazado INT;
                DECLARE totalDetalles INT;

                SELECT COUNT(*) INTO totalPendienteRevision FROM order_details WHERE order_details.order_id = order_id AND (status = "Pendiente" OR status = "Revision");
                SELECT COUNT(*) INTO totalAprobado FROM order_details WHERE order_details.order_id = order_id AND status = "Aprobado";
                SELECT COUNT(*) INTO totalFiltrado FROM order_details WHERE order_details.order_id = order_id AND status = "Filtrado";
                SELECT COUNT(*) INTO totalEmpacado FROM order_details WHERE order_details.order_id = order_id AND status = "Empacado";
                SELECT COUNT(*) INTO totalDevuelto FROM order_details WHERE order_details.order_id = order_id AND status = "Devuelto";
                SELECT COUNT(*) INTO totalDespachado FROM order_details WHERE order_details.order_id = order_id AND status = "Despachado";
                SELECT COUNT(*) INTO totalCanceladoRechazado FROM order_details WHERE order_details.order_id = order_id AND (status = "Cancelado" OR status = "Rechazado");
                SELECT COUNT(*) INTO totalDetalles FROM order_details WHERE order_details.order_id = order_id;

                IF totalCanceladoRechazado = totalDetalles THEN
                    UPDATE orders SET wallet_status = "Cancelado", dispatched_status = "Cancelado" WHERE id = order_id;
                ELSEIF totalPendienteRevision = 0 THEN
                    IF totalAprobado > 0 OR totalFiltrado > 0 OR totalEmpacado > 0 OR totalDevuelto > 0 OR totalDespachado > 0 THEN
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
                DECLARE totalPendienteRevision INT;
                DECLARE totalAgotadoCanceladoRechazado INT;
                DECLARE totalAprobado INT;
                DECLARE totalFiltrado INT;
                DECLARE totalEmpacado INT;
                DECLARE totalDespachado INT;
                DECLARE totalDevuelto INT;
                DECLARE totalParcialmenteDevuelto INT;
                DECLARE totalDetalles INT;
                        
                SELECT COUNT(*) INTO totalPendienteRevision FROM order_details WHERE order_details.order_id = order_id AND status IN ("Pendiente", "Revision");
                SELECT COUNT(*) INTO totalAgotadoCanceladoRechazado FROM order_details WHERE order_details.order_id = order_id AND status IN ("Agotado", "Cancelado", "Rechazado");
                SELECT COUNT(*) INTO totalAprobado FROM order_details WHERE order_details.order_id = order_id AND status = "Aprobado";
                SELECT COUNT(*) INTO totalFiltrado FROM order_details WHERE order_details.order_id = order_id AND status = "Filtrado";
                SELECT COUNT(*) INTO totalEmpacado FROM order_details WHERE order_details.order_id = order_id AND status = "Empacado";
                SELECT COUNT(*) INTO totalDespachado FROM order_details WHERE order_details.order_id = order_id AND status = "Despachado";
                SELECT COUNT(*) INTO totalDevuelto FROM order_details WHERE order_details.order_id = order_id AND status = "Devuelto";
                SELECT COUNT(*) INTO totalParcialmenteDevuelto FROM order_details WHERE order_details.order_id = order_id AND status = "Parcialmente Devuelto";
                SELECT COUNT(*) INTO totalDetalles FROM order_details WHERE order_details.order_id = order_id AND status NOT IN ("Agotado", "Cancelado", "Rechazado");
                        
                IF totalDevuelto = totalDetalles THEN
                    UPDATE orders SET dispatched_status = "Devuelto" WHERE id = order_id;
                ELSEIF totalDevuelto + totalParcialmenteDevuelto = totalDetalles THEN
                    UPDATE orders SET dispatched_status = "Parcialmente Devuelto" WHERE id = order_id;
                ELSEIF totalParcialmenteDevuelto > 0 THEN
                    UPDATE orders SET dispatched_status = "Parcialmente Devuelto" WHERE id = order_id;
                ELSEIF totalDevuelto > 0 THEN
                    UPDATE orders SET dispatched_status = "Parcialmente Devuelto" WHERE id = order_id;
                ELSEIF totalDespachado = totalDetalles THEN
                    UPDATE orders SET dispatched_status = "Despachado" WHERE id = order_id;
                ELSEIF totalDespachado > 0 THEN
                    UPDATE orders SET dispatched_status = "Parcialmente Despachado" WHERE id = order_id;
                ELSEIF totalEmpacado = totalDetalles THEN
                    UPDATE orders SET dispatched_status = "Empacado" WHERE id = order_id;
                ELSEIF totalEmpacado > 0 THEN
                    UPDATE orders SET dispatched_status = "Parcialmente Empacado" WHERE id = order_id;
                ELSEIF totalFiltrado = totalDetalles THEN
                    UPDATE orders SET dispatched_status = "Aprobado" WHERE id = order_id;
                ELSEIF totalFiltrado > 0 THEN
                    UPDATE orders SET dispatched_status = "Parcialmente Aprobado" WHERE id = order_id;
                ELSEIF totalPendienteRevision > 0 THEN
                    UPDATE orders SET dispatched_status = "Pendiente" WHERE id = order_id;
                ELSE
                    UPDATE orders SET dispatched_status = "Rechazado" WHERE id = order_id;
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

<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::create(['departament_id' => 1, 'name' => 'AMAZONAS']);

        Province::create(['departament_id' => 2, 'name' => 'BAJO CAUCA']);
        Province::create(['departament_id' => 2, 'name' => 'MAGDALENA MEDIO']);
        Province::create(['departament_id' => 2, 'name' => 'NORDESTE ANTIOQUIA']);
        Province::create(['departament_id' => 2, 'name' => 'NORTE ANTIOQUIA']);
        Province::create(['departament_id' => 2, 'name' => 'OCCIDENTE ANTIOQUIA']);
        Province::create(['departament_id' => 2, 'name' => 'ORIENTE ANTIOQUIA']);
        Province::create(['departament_id' => 2, 'name' => 'SUROESTE ANTIOQUIA']);
        Province::create(['departament_id' => 2, 'name' => 'URABA']);
        Province::create(['departament_id' => 2, 'name' => 'VALLE DEL ABURRA']);

        Province::create(['departament_id' => 3, 'name' => 'ARAUCA']);

        Province::create(['departament_id' => 4, 'name' => 'ARCHIPIELAGO DE SAN ANDRES']);

        Province::create(['departament_id' => 5, 'name' => 'NORTE ATLÁNTICO']);
        Province::create(['departament_id' => 5, 'name' => 'SUR ATLÁNTICO']);
        Province::create(['departament_id' => 5, 'name' => 'CENTRO ORIENTE ATLÁNTICO']);
        Province::create(['departament_id' => 5, 'name' => 'OCCIDENTAL ATLÁNTICO']);

        Province::create(['departament_id' => 6, 'name' => 'BOGOTA']);

        Province::create(['departament_id' => 7, 'name' => 'ARAUDEPRESION MOMPOSINACA']);
        Province::create(['departament_id' => 7, 'name' => 'DIQUE BOLIVARENSE']);
        Province::create(['departament_id' => 7, 'name' => 'LOBA']);
        Province::create(['departament_id' => 7, 'name' => 'MAGDALENA MEDIO BOLIVARENSE']);
        Province::create(['departament_id' => 7, 'name' => 'MOJANA BOLIVARENSE']);
        Province::create(['departament_id' => 7, 'name' => 'MONTES DE MARIA']);

        Province::create(['departament_id' => 8, 'name' => 'CENTRO BOYACÁ']);
        Province::create(['departament_id' => 8, 'name' => 'GUTIERREZ']);
        Province::create(['departament_id' => 8, 'name' => 'LA LIBERTAD']);
        Province::create(['departament_id' => 8, 'name' => 'LENGUPA']);
        Province::create(['departament_id' => 8, 'name' => 'MARQUEZ']);
        Province::create(['departament_id' => 8, 'name' => 'NEIRA']);
        Province::create(['departament_id' => 8, 'name' => 'NORTE BOYACÁ']);
        Province::create(['departament_id' => 8, 'name' => 'OCCIDENTE BOYACÁ']);
        Province::create(['departament_id' => 8, 'name' => 'ORIENTE BOYACÁ']);
        Province::create(['departament_id' => 8, 'name' => 'RICAURTE']);
        Province::create(['departament_id' => 8, 'name' => 'SUGAMUXI']);
        Province::create(['departament_id' => 8, 'name' => 'TUNDAMA']);
        Province::create(['departament_id' => 8, 'name' => 'VALDERRAMA']);

        Province::create(['departament_id' => 9, 'name' => 'ALTO OCCIDENTE CALDAS']);
        Province::create(['departament_id' => 9, 'name' => 'ALTO ORIENTE CALDAS']);
        Province::create(['departament_id' => 9, 'name' => 'BAJO OCCIDENTE CALDAS']);
        Province::create(['departament_id' => 9, 'name' => 'CENTRO CALDAS']);
        Province::create(['departament_id' => 9, 'name' => 'NORTE CALDAS']);
        Province::create(['departament_id' => 9, 'name' => 'ORIENTE CALDAS']);

        Province::create(['departament_id' => 10, 'name' => 'CAQUETA']);

        Province::create(['departament_id' => 11, 'name' => 'CASANARE']);

        Province::create(['departament_id' => 12, 'name' => 'CENTRO CAUCA']);
        Province::create(['departament_id' => 12, 'name' => 'NORTE CAUCA']);
        Province::create(['departament_id' => 12, 'name' => 'OCCIDENTE CAUCA']);
        Province::create(['departament_id' => 12, 'name' => 'ORIENTE CAUCA']);
        Province::create(['departament_id' => 12, 'name' => 'SUR CAUCA']);

        Province::create(['departament_id' => 13, 'name' => 'CENTRAL CESAR']);
        Province::create(['departament_id' => 13, 'name' => 'NOROCCIDENTAL CESAR']);
        Province::create(['departament_id' => 13, 'name' => 'NORTE CESAR']);
        Province::create(['departament_id' => 13, 'name' => 'SUR CESAR']);

        Province::create(['departament_id' => 14, 'name' => 'ATRATO']);
        Province::create(['departament_id' => 14, 'name' => 'DARIEN']);
        Province::create(['departament_id' => 14, 'name' => 'PACIFICO NORTE']);
        Province::create(['departament_id' => 14, 'name' => 'PACIFICO SUR']);
        Province::create(['departament_id' => 14, 'name' => 'SAN JUAN']);

        Province::create(['departament_id' => 15, 'name' => 'ALTO SINU']);
        Province::create(['departament_id' => 15, 'name' => 'BAJO SINU']);
        Province::create(['departament_id' => 15, 'name' => 'CENTRO CORDOBA']);
        Province::create(['departament_id' => 15, 'name' => 'COSTANERA']);
        Province::create(['departament_id' => 15, 'name' => 'SABANAS']);
        Province::create(['departament_id' => 15, 'name' => 'SAN JORGE']);
        Province::create(['departament_id' => 15, 'name' => 'SINU MEDIO']);

        Province::create(['departament_id' => 16, 'name' => 'ALMEIDAS']);
        Province::create(['departament_id' => 16, 'name' => 'ALTO MAGDALENA']);
        Province::create(['departament_id' => 16, 'name' => 'BAJO MAGDALENA']);
        Province::create(['departament_id' => 16, 'name' => 'GUALIVA']);
        Province::create(['departament_id' => 16, 'name' => 'GUAVIO']);
        Province::create(['departament_id' => 16, 'name' => 'MAGDALENA CENTRO']);
        Province::create(['departament_id' => 16, 'name' => 'MEDINA']);
        Province::create(['departament_id' => 16, 'name' => 'ORIENTE CUNDINAMARCA']);
        Province::create(['departament_id' => 16, 'name' => 'RIO NEGRO']);
        Province::create(['departament_id' => 16, 'name' => 'SABANA CENTRO']);
        Province::create(['departament_id' => 16, 'name' => 'SABANA OCCIDENTE']);
        Province::create(['departament_id' => 16, 'name' => 'SOACHA']);
        Province::create(['departament_id' => 16, 'name' => 'SUMAPAZ']);
        Province::create(['departament_id' => 16, 'name' => 'TEQUENDAMA']);
        Province::create(['departament_id' => 16, 'name' => 'UBATE']);

        Province::create(['departament_id' => 17, 'name' => 'GUAINIA']);

        Province::create(['departament_id' => 18, 'name' => 'GUAVIARE']);

        Province::create(['departament_id' => 19, 'name' => 'CENTRO HUILA']);
        Province::create(['departament_id' => 19, 'name' => 'NORTE HUILA']);
        Province::create(['departament_id' => 19, 'name' => 'OCCIDENTE HUILA']);
        Province::create(['departament_id' => 19, 'name' => 'SUR HUILA']);

        Province::create(['departament_id' => 20, 'name' => 'NORTE LA GUAJIRA']);
        Province::create(['departament_id' => 20, 'name' => 'SUR LA GUAJIRA']);

        Province::create(['departament_id' => 21, 'name' => 'CENTRO MAGDALENA']);
        Province::create(['departament_id' => 21, 'name' => 'NORTE MAGDALENA']);
        Province::create(['departament_id' => 21, 'name' => 'RIO']);
        Province::create(['departament_id' => 21, 'name' => 'SANTA MARTA']);
        Province::create(['departament_id' => 21, 'name' => 'SUR MAGDALENA']);

        Province::create(['departament_id' => 22, 'name' => 'ARIARI']);
        Province::create(['departament_id' => 22, 'name' => 'CAPITAL']);
        Province::create(['departament_id' => 22, 'name' => 'PIEDEMONTE']);
        Province::create(['departament_id' => 22, 'name' => 'RIO META']);

        Province::create(['departament_id' => 23, 'name' => 'CENTRO NARIÑO']);
        Province::create(['departament_id' => 23, 'name' => 'CENTRO OCCIDENTE NARIÑO']);
        Province::create(['departament_id' => 23, 'name' => 'COSTA']);
        Province::create(['departament_id' => 23, 'name' => 'NORTE NARIÑO']);
        Province::create(['departament_id' => 23, 'name' => 'SUR NARIÑO']);

        Province::create(['departament_id' => 24, 'name' => 'CENTRO NORTE DE SANTANDER']);
        Province::create(['departament_id' => 24, 'name' => 'NORTE NORTE DE SANTANDER']);
        Province::create(['departament_id' => 24, 'name' => 'OCCIDENTE NORTE DE SANTANDER']);
        Province::create(['departament_id' => 24, 'name' => 'ORIENTAL NORTE DE SANTANDER']);
        Province::create(['departament_id' => 24, 'name' => 'SUR OCCIDENTE NORTE DE SANTANDER']);
        Province::create(['departament_id' => 24, 'name' => 'SUR ORIENTE NORTE DE SANTANDER']);

        Province::create(['departament_id' => 25, 'name' => 'PUTUMAYO']);

        Province::create(['departament_id' => 26, 'name' => 'CAPITAL']);
        Province::create(['departament_id' => 26, 'name' => 'CORDILLERANOS']);
        Province::create(['departament_id' => 26, 'name' => 'FRIA']);
        Province::create(['departament_id' => 26, 'name' => 'NORTE QUINDIO']);
        Province::create(['departament_id' => 26, 'name' => 'VALLE']);

        Province::create(['departament_id' => 27, 'name' => 'UNO - VERTIENTE ORIENTAL']);
        Province::create(['departament_id' => 27, 'name' => 'DOS - VERTIENTE OCCIDENTAL']);
        Province::create(['departament_id' => 27, 'name' => 'TRES - VERTIENTE DEL PACIFICO']);

        Province::create(['departament_id' => 28, 'name' => 'COMUNERA']);
        Province::create(['departament_id' => 28, 'name' => 'GARCIA ROVIRA']);
        Province::create(['departament_id' => 28, 'name' => 'GUANENTA']);
        Province::create(['departament_id' => 28, 'name' => 'MARES']);
        Province::create(['departament_id' => 28, 'name' => 'SOTO']);
        Province::create(['departament_id' => 28, 'name' => 'VELEZ']);

        Province::create(['departament_id' => 29, 'name' => 'MOJANA']);
        Province::create(['departament_id' => 29, 'name' => 'MONTES DE MARIA']);
        Province::create(['departament_id' => 29, 'name' => 'MORROSQUILLO']);
        Province::create(['departament_id' => 29, 'name' => 'SABANAS']);
        Province::create(['departament_id' => 29, 'name' => 'SAN JORGE']);

        Province::create(['departament_id' => 30, 'name' => 'NORTE TOLIMA']);
        Province::create(['departament_id' => 30, 'name' => 'ORIENTE TOLIMA']);
        Province::create(['departament_id' => 30, 'name' => 'SUR TOLIMA']);
        Province::create(['departament_id' => 30, 'name' => 'IBAGUE']);
        Province::create(['departament_id' => 30, 'name' => 'SURORIENTE TOLIMA']);
        Province::create(['departament_id' => 30, 'name' => 'NEVADOS']);

        Province::create(['departament_id' => 31, 'name' => 'CENTRO VALLE DEL CAUCA']);
        Province::create(['departament_id' => 31, 'name' => 'NORTE VALLE DEL CAUCA']);
        Province::create(['departament_id' => 31, 'name' => 'OCCIDENTE VALLE DEL CAUCA']);
        Province::create(['departament_id' => 31, 'name' => 'ORIENTE VALLE DEL CAUCA']);
        Province::create(['departament_id' => 31, 'name' => 'SUR VALLE DEL CAUCA']);

        Province::create(['departament_id' => 32, 'name' => 'VAUPES']);

        Province::create(['departament_id' => 33, 'name' => 'VICHADA']);
    }
}

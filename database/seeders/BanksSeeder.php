<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::create(['name' => 'BANCO DE BOGOTÁ', 'sector_code' => '01', 'entity_code' => '001']);
        Bank::create(['name' => 'BANCO POPULAR', 'sector_code' => '01', 'entity_code' => '002']);
        Bank::create(['name' => 'BANCO SANTANDER', 'sector_code' => '01', 'entity_code' => '006']);
        Bank::create(['name' => 'BANCOLOMBIA', 'sector_code' => '01', 'entity_code' => '007']);
        Bank::create(['name' => 'ABN AMRO BANK', 'sector_code' => '01', 'entity_code' => '008']);
        Bank::create(['name' => 'CITIBANK', 'sector_code' => '01', 'entity_code' => '009']);
        Bank::create(['name' => 'BANISTMO COLOMBIA', 'sector_code' => '01', 'entity_code' => '010']);
        Bank::create(['name' => 'BANCO SUDAMERIS COLOMBIA', 'sector_code' => '01', 'entity_code' => '012']);
        Bank::create(['name' => 'BBVA COLOMBIA', 'sector_code' => '01', 'entity_code' => '013']);
        Bank::create(['name' => 'BANCO DE CRÉDITO HELM SERVICES', 'sector_code' => '01', 'entity_code' => '014']);
        Bank::create(['name' => 'BANCO COLPATRIA', 'sector_code' => '01', 'entity_code' => '019']);
        Bank::create(['name' => 'BANESTADO', 'sector_code' => '01', 'entity_code' => '020']);
        Bank::create(['name' => 'BANCO UNIÓN COLOMBIANO', 'sector_code' => '01', 'entity_code' => '022']);
        Bank::create(['name' => 'BANCO DE OCCIDENTE', 'sector_code' => '01', 'entity_code' => '023']);
        Bank::create(['name' => 'BANCO STANDARD CHARTERED COLOMBIA', 'sector_code' => '01', 'entity_code' => '024']);
        Bank::create(['name' => 'BANCO TEQUENDAMA', 'sector_code' => '01', 'entity_code' => '029']);
        Bank::create(['name' => 'BANCO CAJA SOCIAL', 'sector_code' => '01', 'entity_code' => '030']);
        Bank::create(['name' => 'BANCO SUPERIOR', 'sector_code' => '01', 'entity_code' => '034']);
        Bank::create(['name' => 'BANKBOSTON', 'sector_code' => '01', 'entity_code' => '036']);
        Bank::create(['name' => 'MEGABANCO', 'sector_code' => '01', 'entity_code' => '037']);
        Bank::create(['name' => 'BANCO DAVIVIENDA', 'sector_code' => '01', 'entity_code' => '039']);
        Bank::create(['name' => 'BANCO AGRARIO DE COLOMBIA', 'sector_code' => '01', 'entity_code' => '041']);
        Bank::create(['name' => 'BANCO ALIADAS', 'sector_code' => '01', 'entity_code' => '048']);
        Bank::create(['name' => 'GRANBANCO', 'sector_code' => '01', 'entity_code' => '050']);
        Bank::create(['name' => 'BANCO COMERCIAL AVVILLAS', 'sector_code' => '01', 'entity_code' => '052']);
        Bank::create(['name' => 'BANCO GRANAHORRAR', 'sector_code' => '01', 'entity_code' => '054']);
        Bank::create(['name' => 'BANCO CONAVI', 'sector_code' => '01', 'entity_code' => '055']);
        Bank::create(['name' => 'BANCO COLMENA', 'sector_code' => '01', 'entity_code' => '057']);

        // Corporaciones Financieras
        Bank::create(['name' => 'CORFICOLOMBIANA', 'sector_code' => '02', 'entity_code' => '006']);
        Bank::create(['name' => 'CORFIVALLE', 'sector_code' => '02', 'entity_code' => '011']);
        Bank::create(['name' => 'CORFINSURA', 'sector_code' => '02', 'entity_code' => '036']);
        Bank::create(['name' => 'COLCORP', 'sector_code' => '02', 'entity_code' => '037']);

        // Compañías de Financiamiento Comercial
        Bank::create(['name' => 'GIROS Y FINANZAS', 'sector_code' => '04', 'entity_code' => '008']);
        Bank::create(['name' => 'INVERSORA PICHINCHA', 'sector_code' => '04', 'entity_code' => '013']);
        Bank::create(['name' => 'COMERCIA', 'sector_code' => '04', 'entity_code' => '017']);
        Bank::create(['name' => 'MAZDACRÉDITO', 'sector_code' => '04', 'entity_code' => '021']);
        Bank::create(['name' => 'CONFINANCIERA', 'sector_code' => '04', 'entity_code' => '022']);
        Bank::create(['name' => 'SERFINANSA', 'sector_code' => '04', 'entity_code' => '023']);
        Bank::create(['name' => 'FINANCIERA ANDINA', 'sector_code' => '04', 'entity_code' => '024']);
        Bank::create(['name' => 'SUFINANCIAMIENTO', 'sector_code' => '04', 'entity_code' => '026']);
        Bank::create(['name' => 'G.M.A.C. COLOMBIA', 'sector_code' => '04', 'entity_code' => '031']);
        Bank::create(['name' => 'FINANCIERA INTERNACIONAL', 'sector_code' => '04', 'entity_code' => '033']);
        Bank::create(['name' => 'MACROFINANCIERA S.A.', 'sector_code' => '04', 'entity_code' => '035']);
        Bank::create(['name' => 'COLTEFINANCIERA', 'sector_code' => '04', 'entity_code' => '046']);
        Bank::create(['name' => 'LEASING COLOMBIA S.A.', 'sector_code' => '04', 'entity_code' => '067']);
        Bank::create(['name' => 'LEASING BOGOTÁ S.A.', 'sector_code' => '04', 'entity_code' => '084']);

    }
}

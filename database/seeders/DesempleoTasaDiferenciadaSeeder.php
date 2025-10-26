<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\polizas\DesempleoTasaDiferenciada;

class DesempleoTasaDiferenciadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["PolizaDesempleoTipoCartera" => 1,  "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0006200000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 2,  "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0002500000", "SaldosMontos" => 5, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 4,  "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0006000000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 5,  "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0002500000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 6,  "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0003500000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 7,  "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0005000000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 8,  "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0004000000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 9,  "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0003500000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 10, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0002500000", "SaldosMontos" => 2, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 12, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0002500000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 13, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0006200000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 14, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0004700000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 15, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0004500000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 16, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0005000000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 17, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0004300000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 18, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0004000000", "SaldosMontos" => 1, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 19, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0002500000", "SaldosMontos" => 5, "Usuario" => 8],
            ["PolizaDesempleoTipoCartera" => 20, "FechaDesde" => null, "FechaHasta" => null, "MontoDesde" => null, "MontoHasta" => null, "Tasa" => "0.0002500000", "SaldosMontos" => 1, "Usuario" => 8],

        ];

        DesempleoTasaDiferenciada::insert($data);
    }
}

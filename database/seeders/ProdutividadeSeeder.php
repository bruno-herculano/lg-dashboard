<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdutividadeSeeder extends Seeder
{
    /**
     * Seed de dados de produtividade para janeiro/2026 — Planta A
     * Eficiência = (produzida - defeitos) / produzida * 100
     */
    public function run()
    {
        DB::table('produtividades')->truncate();

        $linhas = [
            'Geladeira',
            'Máquina de Lavar',
            'TV',
            'Ar-Condicionado',
        ];

        // Dias úteis de janeiro de 2026 (seg–sex, exceto feriados)
        $diasUteis = $this->diasUteisJaneiro2026();

        // Parâmetros base por linha (produção diária e taxa de defeito %)
        $parametros = [
            'Geladeira'         => ['base' => 320, 'variacao' => 30, 'defeito_min' => 2, 'defeito_max' => 8],
            'Máquina de Lavar'  => ['base' => 280, 'variacao' => 25, 'defeito_min' => 3, 'defeito_max' => 10],
            'TV'                => ['base' => 500, 'variacao' => 50, 'defeito_min' => 1, 'defeito_max' => 6],
            'Ar-Condicionado'   => ['base' => 210, 'variacao' => 20, 'defeito_min' => 4, 'defeito_max' => 12],
        ];

        $registros = [];

        foreach ($linhas as $linha) {
            $p = $parametros[$linha];

            foreach ($diasUteis as $dia) {
                $produzida = $p['base'] + rand(-$p['variacao'], $p['variacao']);
                $pctDefeito = rand($p['defeito_min'] * 10, $p['defeito_max'] * 10) / 1000; // 0.xxx
                $defeitos   = (int) round($produzida * $pctDefeito);

                $registros[] = [
                    'linha_produto'       => $linha,
                    'planta'              => 'Planta A',
                    'data_producao'       => $dia,
                    'quantidade_produzida' => $produzida,
                    'quantidade_defeitos'  => $defeitos,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ];
            }
        }

        DB::table('produtividades')->insert($registros);

        $this->command->info('✅ Seeder executado! ' . count($registros) . ' registros inseridos.');
    }

    /**
     * Retorna os dias úteis de Janeiro/2026 (segunda a sexta)
     */
    private function diasUteisJaneiro2026(): array
    {
        $dias = [];
        $inicio = Carbon::create(2026, 1, 1);
        $fim    = Carbon::create(2026, 1, 31);

        // Feriados de janeiro de 2026 (Confraternização Universal)
        $feriados = ['2026-01-01'];

        while ($inicio->lte($fim)) {
            if ($inicio->isWeekday() && !in_array($inicio->toDateString(), $feriados)) {
                $dias[] = $inicio->toDateString();
            }
            $inicio->addDay();
        }

        return $dias;
    }
}

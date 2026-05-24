<?php

namespace App\Http\Controllers;

use App\Models\Produtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $linhaSelecionada = $request->input('linha', 'todas');
        $linhas = Produtividade::linhasDisponiveis();

        // Query base
        $query = DB::table('produtividades')
            ->select(
                'linha_produto',
                DB::raw('SUM(quantidade_produzida) as total_produzida'),
                DB::raw('SUM(quantidade_defeitos)  as total_defeitos'),
                DB::raw('SUM(quantidade_produzida - quantidade_defeitos) as total_bons'),
                DB::raw('ROUND(
                    (SUM(quantidade_produzida - quantidade_defeitos) / SUM(quantidade_produzida)) * 100,
                    2
                ) as eficiencia')
            )
            ->where('planta', 'Planta A')
            ->whereYear('data_producao', 2026)
            ->whereMonth('data_producao', 1)
            ->groupBy('linha_produto')
            ->orderBy('linha_produto');

        if ($linhaSelecionada !== 'todas') {
            $query->where('linha_produto', $linhaSelecionada);
        }

        $dadosAgrupados = $query->get();

        // Totais consolidados
        $totais = DB::table('produtividades')
            ->select(
                DB::raw('SUM(quantidade_produzida) as total_produzida'),
                DB::raw('SUM(quantidade_defeitos)  as total_defeitos'),
                DB::raw('SUM(quantidade_produzida - quantidade_defeitos) as total_bons'),
                DB::raw('ROUND(
                    (SUM(quantidade_produzida - quantidade_defeitos) / SUM(quantidade_produzida)) * 100,
                    2
                ) as eficiencia_geral')
            )
            ->where('planta', 'Planta A')
            ->whereYear('data_producao', 2026)
            ->whereMonth('data_producao', 1)
            ->when($linhaSelecionada !== 'todas', fn($q) => $q->where('linha_produto', $linhaSelecionada))
            ->first();

        // Dados diários para o gráfico de linha
        $queryGrafico = DB::table('produtividades')
            ->select(
                'data_producao',
                'linha_produto',
                DB::raw('SUM(quantidade_produzida) as total_produzida'),
                DB::raw('SUM(quantidade_defeitos)  as total_defeitos'),
                DB::raw('ROUND(
                    (SUM(quantidade_produzida - quantidade_defeitos) / SUM(quantidade_produzida)) * 100,
                    2
                ) as eficiencia')
            )
            ->where('planta', 'Planta A')
            ->whereYear('data_producao', 2026)
            ->whereMonth('data_producao', 1)
            ->when($linhaSelecionada !== 'todas', fn($q) => $q->where('linha_produto', $linhaSelecionada))
            ->groupBy('data_producao', 'linha_produto')
            ->orderBy('data_producao');

        $dadosDiarios = $queryGrafico->get();

        // Prepara dados para Chart.js
        $labels = $dadosDiarios->pluck('data_producao')->unique()->sort()->values();
        $chartData = $this->prepararChartData($dadosDiarios, $labels, $linhaSelecionada, $linhas);

        return view('dashboard.index', compact(
            'dadosAgrupados',
            'totais',
            'linhaSelecionada',
            'linhas',
            'labels',
            'chartData'
        ));
    }

    private function prepararChartData($dadosDiarios, $labels, $linhaSelecionada, $linhas): array
    {
        $cores = [
            'Geladeira'         => 'rgba(78, 115, 223, 1)',
            'Máquina de Lavar'  => 'rgba(28, 200, 138, 1)',
            'TV'                => 'rgba(246, 194, 62, 1)',
            'Ar-Condicionado'   => 'rgba(231, 74, 59, 1)',
        ];

        $coresFundo = [
            'Geladeira'         => 'rgba(78, 115, 223, 0.15)',
            'Máquina de Lavar'  => 'rgba(28, 200, 138, 0.15)',
            'TV'                => 'rgba(246, 194, 62, 0.15)',
            'Ar-Condicionado'   => 'rgba(231, 74, 59, 0.15)',
        ];

        $datasets = [];
        $linhasParaExibir = $linhaSelecionada === 'todas' ? $linhas : [$linhaSelecionada];

        foreach ($linhasParaExibir as $linha) {
            $dadosLinha = [];
            foreach ($labels as $data) {
                $registro = $dadosDiarios->first(fn($d) => $d->data_producao === $data && $d->linha_produto === $linha);
                $dadosLinha[] = $registro ? $registro->eficiencia : null;
            }

            $datasets[] = [
                'label'           => $linha,
                'data'            => $dadosLinha,
                'borderColor'     => $cores[$linha] ?? 'rgba(0,0,0,1)',
                'backgroundColor' => $coresFundo[$linha] ?? 'rgba(0,0,0,0.1)',
                'borderWidth'     => 2,
                'pointRadius'     => 3,
                'fill'            => true,
                'tension'         => 0.3,
            ];
        }

        return [
            'labels'   => $labels->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values()->toArray(),
            'datasets' => $datasets,
        ];
    }
}

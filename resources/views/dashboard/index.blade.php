@extends('layouts.app')

@section('title', 'Dashboard de Produção')
@section('breadcrumb', 'Dashboard')
@section('page-title', 'Eficiência de Produção — Planta A')

@push('styles')
    <style>
        /* ─── Cores por linha ─── */
        .cor-geladeira {
            background: linear-gradient(195deg, #42a5f5, #1565c0);
        }

        .cor-maquina {
            background: linear-gradient(195deg, #66bb6a, #2e7d32);
        }

        .cor-tv {
            background: linear-gradient(195deg, #ffa726, #e65100);
        }

        .cor-ar {
            background: linear-gradient(195deg, #ef5350, #b71c1c);
        }

        .cor-geral {
            background: linear-gradient(195deg, var(--lg-red), #6a1228);
        }

        /* Tabela bonita */
        .table-prod thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #8392a5;
            border: none;
        }

        .table-prod tbody td {
            vertical-align: middle;
            border-color: #f0f0f0;
        }

        .table-prod tbody tr:hover {
            background: #f8f9ff;
        }

        /* Linha ativa no filtro sidebar */
        .linha-badge {
            display: inline-block;
            padding: .25em .75em;
            border-radius: 1rem;
            font-size: .78rem;
            font-weight: 600;
        }

        /* Card KPI número */
        .kpi-number {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .kpi-label {
            font-size: .8rem;
            color: #8392a5;
        }

        /* Chart container */
        .chart-container {
            position: relative;
            height: 320px;
        }
    </style>
@endpush

@section('content')

    {{-- ══════════════════════════════════════════
    BARRA DE FILTROS POR LINHA
    ══════════════════════════════════════════ --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center gap-2">
                    <span class="text-sm text-muted me-2 fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filtrar por linha:
                    </span>

                    <a href="{{ route('dashboard') }}"
                        class="btn btn-sm {{ $linhaSelecionada === 'todas' ? 'btn-dark' : 'btn-outline-secondary' }} mb-0">
                        <i class="fas fa-th-large me-1"></i> Todas
                    </a>

                    @foreach($linhas as $linha)
                        @php
                            $icons = [
                                'Geladeira' => 'fa-snowflake',
                                'Máquina de Lavar' => 'fa-tshirt',
                                'TV' => 'fa-tv',
                                'Ar-Condicionado' => 'fa-wind',
                            ];
                            $cores = [
                                'Geladeira' => 'btn-info',
                                'Máquina de Lavar' => 'btn-success',
                                'TV' => 'btn-warning',
                                'Ar-Condicionado' => 'btn-danger',
                            ];
                            $ativo = $linhaSelecionada === $linha;
                        @endphp
                        <a href="{{ route('dashboard', ['linha' => $linha]) }}"
                            class="btn btn-sm {{ $ativo ? $cores[$linha] : 'btn-outline-secondary' }} mb-0 filter-btn {{ $ativo ? 'active' : '' }}">
                            <i class="fas {{ $icons[$linha] ?? 'fa-industry' }} me-1"></i>
                            {{ $linha }}
                        </a>
                    @endforeach

                    @if($linhaSelecionada !== 'todas')
                        <span class="badge bg-gradient-secondary ms-auto">
                            Exibindo: <strong>{{ $linhaSelecionada }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
    CARDS DE KPI (Totais consolidados)
    ══════════════════════════════════════════ --}}
    <div class="row mb-4">

        {{-- Total Produzido --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card kpi-card shadow">
                <div class="card-header p-3 pt-2 border-0">
                    <div class="icon icon-lg kpi-icon bg-dark shadow-lg text-white"
                        style="margin-top:-1.5rem; border-radius:1rem; width:64px; height:64px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-industry fa-lg"></i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="kpi-label mb-0">Total Produzido</p>
                        <h4 class="kpi-number mb-0 text-dark">
                            {{ number_format($totais->total_produzida ?? 0, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0 text-sm">
                        <span class="text-success font-weight-bolder">Janeiro 2026</span>
                        <span class="text-secondary ms-1">Planta A</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Defeitos --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card kpi-card shadow">
                <div class="card-header p-3 pt-2 border-0">
                    <div class="icon icon-lg kpi-icon bg-danger shadow-lg text-white"
                        style="margin-top:-1.5rem; border-radius:1rem; width:64px; height:64px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="kpi-label mb-0">Total Defeitos</p>
                        <h4 class="kpi-number mb-0 text-dark">
                            {{ number_format($totais->total_defeitos ?? 0, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0 text-sm">
                        <span class="text-danger font-weight-bolder">
                            {{ number_format(($totais->total_defeitos / max($totais->total_produzida, 1)) * 100, 1) }}%
                        </span>
                        <span class="text-secondary ms-1">taxa de defeito</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Produtos Conformes --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card kpi-card shadow">
                <div class="card-header p-3 pt-2 border-0">
                    <div class="icon icon-lg kpi-icon bg-success shadow-lg text-white"
                        style="margin-top:-1.5rem; border-radius:1rem; width:64px; height:64px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="kpi-label mb-0">Produtos Conformes</p>
                        <h4 class="kpi-number mb-0 text-dark">
                            {{ number_format($totais->total_bons ?? 0, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0 text-sm">
                        <span class="text-success font-weight-bolder">Sem defeitos</span>
                        <span class="text-secondary ms-1">unidades aprovadas</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Eficiência Geral --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card kpi-card shadow">
                <div class="card-header p-3 pt-2 border-0">
                    <div class="icon icon-lg kpi-icon bg-info shadow-lg text-white"
                        style="margin-top:-1.5rem; border-radius:1rem; width:64px; height:64px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-chart-pie fa-lg"></i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="kpi-label mb-0">Eficiência Geral</p>
                        <h4 class="kpi-number mb-0 text-dark">
                            {{ number_format($totais->eficiencia_geral ?? 0, 2, ',', '.') }}%
                        </h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    @php
                        $ef = $totais->eficiencia_geral ?? 0;
                        $cor = $ef >= 95 ? 'success' : ($ef >= 90 ? 'warning' : 'danger');
                        $label = $ef >= 95 ? 'Excelente' : ($ef >= 90 ? 'Atenção' : 'Crítico');
                    @endphp
                    <p class="mb-0 text-sm">
                        <span class="text-{{ $cor }} font-weight-bolder">{{ $label }}</span>
                        <span class="text-secondary ms-1">eficiência do período</span>
                    </p>
                </div>
            </div>
        </div>

    </div>{{-- /row KPIs --}}


    {{-- ══════════════════════════════════════════
    GRÁFICO + TABELA DETALHADA
    ══════════════════════════════════════════ --}}
    <div class="row">

        {{-- Gráfico de Eficiência Diária --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header pb-0 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-0">
                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                Eficiência Diária (%) — Janeiro 2026
                            </h6>
                            <p class="text-sm text-muted mb-0">
                                {{ $linhaSelecionada === 'todas' ? 'Todas as linhas' : $linhaSelecionada }}
                            </p>
                        </div>
                        <span class="badge bg-gradient-primary text-xs">
                            <i class="fas fa-calendar me-1"></i> Jan/2026
                        </span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart-container">
                        <canvas id="chartEficiencia"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfico de Barras — Produção vs Defeitos --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header pb-0 border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-warning"></i>
                        Produção vs Defeitos
                    </h6>
                    <p class="text-sm text-muted mb-0">Total por linha de produto</p>
                </div>
                <div class="card-body p-3">
                    <div class="chart-container" style="height: 280px;">
                        <canvas id="chartBarras"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header pb-0 border-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-0">
                            <i class="fas fa-table me-2 text-secondary"></i>
                            Resumo por Linha de Produto
                        </h6>
                        <p class="text-sm text-muted mb-0">
                            Período: Janeiro/2026 · Planta A ·
                            @if($linhaSelecionada === 'todas')
                                <span class="fw-bold">Todas as linhas</span>
                            @else
                                Filtrado: <span class="fw-bold">{{ $linhaSelecionada }}</span>
                            @endif
                        </p>
                    </div>
                    {{-- Link para resetar filtro --}}
                    @if($linhaSelecionada !== 'todas')
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary mb-0">
                            <i class="fas fa-times me-1"></i> Limpar filtro
                        </a>
                    @endif
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-prod align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Linha de Produto</th>
                                    <th class="text-center">Qtd. Produzida</th>
                                    <th class="text-center">Qtd. Defeitos</th>
                                    <th class="text-center">Produtos Bons</th>
                                    <th class="text-center">Eficiência (%)</th>
                                    <th class="text-center pe-4">Barra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dadosAgrupados as $dado)
                                    @php
                                        $ef = (float) $dado->eficiencia;
                                        $classBadge = $ef >= 95 ? 'badge-alta' : ($ef >= 90 ? 'badge-media' : 'badge-baixa');
                                        $corBarra = $ef >= 95 ? 'bg-success' : ($ef >= 90 ? 'bg-warning' : 'bg-danger');

                                        $icone = match ($dado->linha_produto) {
                                            'Geladeira' => ['icon' => 'fa-snowflake', 'cor' => 'cor-geladeira'],
                                            'Máquina de Lavar' => ['icon' => 'fa-tshirt', 'cor' => 'cor-maquina'],
                                            'TV' => ['icon' => 'fa-tv', 'cor' => 'cor-tv'],
                                            default => ['icon' => 'fa-wind', 'cor' => 'cor-ar'],
                                        };
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="{{ $icone['cor'] }} text-white rounded-circle d-flex align-items-center justify-content-center shadow"
                                                    style="width:36px;height:36px;min-width:36px;">
                                                    <i class="fas {{ $icone['icon'] }} text-sm"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 text-sm">{{ $dado->linha_produto }}</h6>
                                                    <p class="text-xs text-muted mb-0">Planta A</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-sm font-weight-bold">
                                                {{ number_format($dado->total_produzida, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-sm text-danger font-weight-bold">
                                                {{ number_format($dado->total_defeitos, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-sm text-success font-weight-bold">
                                                {{ number_format($dado->total_bons, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-efic {{ $classBadge }}">
                                                {{ number_format($ef, 2, ',', '.') }}%
                                            </span>
                                        </td>
                                        <td class="pe-4" style="min-width: 120px;">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress progress-efic flex-grow-1" style="height:8px;">
                                                    <div class="progress-bar {{ $corBarra }}" role="progressbar"
                                                        style="width: {{ min($ef, 100) }}%" aria-valuenow="{{ $ef }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <span class="text-xs text-muted" style="min-width:38px;">
                                                    {{ number_format($ef, 1, ',', '.') }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Nenhum dado encontrado para o filtro selecionado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ─── Dados vindos do Laravel (JSON) ───
            const chartData = @json($chartData);

            const palette = {
                primary: 'rgba(78, 115, 223, 1)',
                success: 'rgba(28, 200, 138, 1)',
                warning: 'rgba(246, 194, 62, 1)',
                danger: 'rgba(231, 74, 59, 1)',
            };

            // Gráfico 1 — Linha de Eficiência Diária

            const ctxLinha = document.getElementById('chartEficiencia').getContext('2d');
            new Chart(ctxLinha, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { font: { size: 12 }, padding: 16, usePointStyle: true }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    return ` ${ctx.dataset.label}: ${ctx.parsed.y !== null ? ctx.parsed.y.toFixed(2) + '%' : 'N/D'}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        },
                        y: {
                            min: 85,
                            max: 100,
                            ticks: {
                                callback: (v) => v + '%',
                                font: { size: 11 }
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        }
                    }
                }
            });

            // Gráfico 2 — Barras (Produzido vs Defeitos)

            const dadosTabela = @json($dadosAgrupados);

            const labels = dadosTabela.map(d => d.linha_produto);
            const produzid = dadosTabela.map(d => d.total_produzida);
            const defeitos = dadosTabela.map(d => d.total_defeitos);
            const bons = dadosTabela.map(d => d.total_bons);

            const ctxBarras = document.getElementById('chartBarras').getContext('2d');
            new Chart(ctxBarras, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Conformes',
                            data: bons,
                            backgroundColor: 'rgba(28, 200, 138, 0.8)',
                            borderRadius: 6,
                            stack: 'stack'
                        },
                        {
                            label: 'Defeitos',
                            data: defeitos,
                            backgroundColor: 'rgba(231, 74, 59, 0.8)',
                            borderRadius: 6,
                            stack: 'stack'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { font: { size: 11 }, usePointStyle: true, padding: 12 }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString('pt-BR')}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            stacked: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                callback: (v) => v.toLocaleString('pt-BR'),
                                font: { size: 10 }
                            }
                        }
                    }
                }
            });

        });
    </script>
@endpush

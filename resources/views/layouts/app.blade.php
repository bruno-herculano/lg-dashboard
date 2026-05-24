<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'LG Dashboard') | Eficiência de Produção</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" />

    <!-- Material Dashboard 2 — Creative Tim (CDN gratuito) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/creativetimofficial/material-dashboard@3.2.0/assets/css/material-dashboard.min.css" />

    <style>
        /* ─── Ajustes visuais LG ─── */
        :root {
            --lg-red: #a50034;
            --lg-dark: #1a1a2e;
        }

        .bg-lg {
            background-color: #f0ece5
        }

        .brand-logo {
            color: var(--lg-red) !important;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .brand-subtitle {
            color: #90a4ae;
            font-size: .75rem;
        }

        /* Sidebar ativa */
        .sidenav .nav-link.active,
        .sidenav .nav-link:hover {
            background: linear-gradient(195deg, var(--lg-red), #c62828) !important;
        }

        /* Cards de KPI */
        .kpi-card {
            border-radius: 1rem;
        }

        .kpi-icon {
            width: 64px;
            height: 64px;
            border-radius: .75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Badges de eficiência na tabela */
        .badge-efic {
            font-size: .82rem;
            padding: .35em .65em;
            border-radius: 2rem;
            font-weight: 600;
        }

        .badge-alta {
            background: rgba(28, 200, 138, .15);
            color: #1bc275;
        }

        .badge-media {
            background: rgba(246, 194, 62, .15);
            color: #d4a017;
        }

        .badge-baixa {
            background: rgba(231, 74, 59, .15);
            color: #e74a3b;
        }

        /* Progress bar colorida */
        .progress-efic {
            height: 8px;
            border-radius: 4px;
        }

        /* Filtro ativo */
        .filter-btn.active {
            box-shadow: 0 4px 15px rgba(165, 0, 52, .4);
        }

        /* Responsivo sidebar */
        @media (max-width: 1200px) {
            .sidenav {
                transform: translateX(-260px);
            }

            .sidenav.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="g-sidenav-show bg-lg">

    <!-- ── SIDEBAR ── -->
    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-dark shadow"
        id="sidenav-main">

        <div class="sidenav-header text-center py-3">
            <a class="navbar-brand d-flex align-items-center justify-content-center gap-2"
                href="{{ route('dashboard') }}">
                <span
                    style="background:var(--lg-red);color:#fff;border-radius:8px;padding:4px 10px;font-weight:900;font-size:1.2rem;letter-spacing:2px;">LG</span>
                <div class="text-start">
                    <div class="brand-logo">Dashboard</div>
                    <div class="brand-subtitle">Planta A — Eficiência</div>
                </div>
            </a>
        </div>
        <hr class="horizontal dark mt-0">

        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons-round text-dark" style="font-size:18px">dashboard</span>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Linhas de Produto</h6>
                </li>

                @foreach(['Geladeira', 'Máquina de Lavar', 'TV', 'Ar-Condicionado'] as $linha)
                    <li class="nav-item">
                        <a class="nav-link {{ request('linha') === $linha ? 'active' : '' }}"
                            href="{{ route('dashboard', ['linha' => $linha]) }}">
                            <div
                                class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                @if($linha === 'Geladeira')
                                    <span class="material-icons-round text-info" style="font-size:16px">kitchen</span>
                                @elseif($linha === 'Máquina de Lavar')
                                    <span class="material-icons-round text-success"
                                        style="font-size:16px">local_laundry_service</span>
                                @elseif($linha === 'TV')
                                    <span class="material-icons-round text-warning" style="font-size:16px">tv</span>
                                @else
                                    <span class="material-icons-round text-danger" style="font-size:16px">ac_unit</span>
                                @endif
                            </div>
                            <span class="nav-link-text ms-1">{{ $linha }}</span>
                        </a>
                    </li>
                @endforeach

                <li class="nav-item mt-2">
                    <a class="nav-link {{ request('linha') === null && request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons-round text-primary" style="font-size:16px">bar_chart</span>
                        </div>
                        <span class="nav-link-text ms-1">Todas as Linhas</span>
                    </a>
                </li>

            </ul>
        </div>
    </aside>
    <!-- ── FIM SIDEBAR ── -->

    <!-- ── MAIN CONTENT ── -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" style="margin-left: 270px;">

        <!-- Navbar topo -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur">
            <div class="container-fluid py-1 px-3">

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm">
                            <a class="opacity-5 text-dark" href="#">LG Electronics</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                            @yield('breadcrumb', 'Dashboard')
                        </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">@yield('page-title', 'Eficiência de Produção')</h6>
                </nav>

                <!-- Hamburguer mobile -->
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <span class="text-muted text-sm me-2">
                            <i class="fas fa-calendar-alt me-1"></i> Janeiro 2026
                        </span>
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
        <!-- fim navbar -->

        <!-- Conteúdo da página -->
        <div class="container-fluid py-4">
            @yield('content')

            <!-- Footer -->
            <footer class="footer pt-3">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © {{ date('Y') }} — <strong>LG Electronics</strong> · Planta A · Dashboard de Produção
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <span class="nav-link text-muted text-sm" style="font-size:.75rem;">
                                        Template: <a href="https://www.creative-tim.com/product/material-dashboard"
                                            target="_blank" class="text-muted fw-bold">Material Dashboard</a>
                                        by Creative Tim
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </main>
    <!-- ── FIM MAIN CONTENT ── -->

    <!-- Scripts Material Dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/creativetimofficial/material-dashboard@3.2.0/assets/js/core/popper.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/creativetimofficial/material-dashboard@3.2.0/assets/js/material-dashboard.min.js"></script>

    @stack('scripts')
</body>

</html>

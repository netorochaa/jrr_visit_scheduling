<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-warning">
    <!-- Brand Logo -->
    <a href="" class="brand-link navbar-light">
        <img src="http://roseannedore.com.br/img/logo_roseanelab.png"
            alt="Laboratório Roseanne Dore"
            class="brand-image">
        <span class="brand-text font-weight-light"><strong>RD</strong>omiciliar</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('img/avatar.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('user.edit', Auth::user()->id) }}" class="d-block">{{ Auth::user()->name }}<br>
                    <u><span class="text-muted"><small>Editar usuário</small></span></u>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('auth.home') }}" class="nav-link @if($namepage == 'Home') active @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Home</p>
                    </a>
                </li>
                @if(Auth::user()->type == 2 || Auth::user()->type == 99)
                    <li class="nav-item">
                        <a href="{{ route('activity.index') }}" class="nav-link @if($namepage == 'Rota do dia') active @endif">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>Rota</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item has-treeview @if($threeview == 'Coletas') menu-open @endif">
                    <a href="#" class="nav-link  @if($threeview == 'Coletas') active @endif">
                      <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            Coletas
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('collect.index') }}" class="nav-link @if($namepage == 'Agendar coleta') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Agendar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('collect.extra', null) }}" class="nav-link @if($namepage == 'Coleta extra') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Extra</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('collect.list.reserved') }}" class="nav-link @if($namepage == 'Coletas reservadas') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reservadas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('collect.list.cancelled') }}" class="nav-link @if($namepage == 'Coletas canceladas') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Canceladas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @if(Auth::user()->type > 2)
                    <li class="nav-item has-treeview @if($threeview == 'Cadastros') menu-open @endif">
                        <a href="#" class="nav-link  @if($threeview == 'Cadastros') active @endif">
                        <i class="nav-icon fas fa-edit"></i>
                            <p>
                                Cadastros
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('freedays.index') }}" class="nav-link @if($namepage == 'Dias sem coletas') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dias sem coletas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('collector.index') }}" class="nav-link @if($namepage == 'Coletador') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Coletador</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user.index') }}" class="nav-link @if($namepage == 'Usuário') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Usuário</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('neighborhood.index') }}" class="nav-link @if($namepage == 'Bairro') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Bairro</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('patienttype.index') }}" class="nav-link @if($namepage == 'Tipo de paciente') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tipo de paciente</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cancellationtype.index') }}" class="nav-link @if($namepage == 'Cancelamento de coleta') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cancelamento de coleta</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item has-treeview @if($threeview == 'Relatórios') menu-open @endif">
                    <a href="#" class="nav-link  @if($threeview == 'Relatórios') active @endif">
                      <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Relatórios
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('report.cash') }}" class="nav-link @if($namepage == 'Caixa') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Caixa</p>
                            </a>
                        </li>
                    </ul>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('report.others') }}" class="nav-link @if($namepage == 'Outros') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Caixa</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('report.statistic') }}" class="nav-link @if($namepage == 'Estatísticas') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Caixa</p>
                            </a>
                        </li>
                    </ul> --}}
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

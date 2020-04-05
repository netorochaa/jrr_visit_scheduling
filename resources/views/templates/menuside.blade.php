<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-warning">
    <!-- Brand Logo -->
    <a href="" class="brand-link navbar-light">
        {{-- <img src="../../dist/img/AdminLTELogo.png"
            alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: .8"> --}}
        <span class="brand-text font-weight-light"><strong>RD</strong>omiciliar</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../img/avatar.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Usuário</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="#" class="nav-link @if($namepage == 'Home') active @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if($namepage == 'Atividade do dia') active @endif">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Iniciar atividade</p>
                    </a>
                </li>
                <li class="nav-item has-treeview @if($threeview == 'Coleta') menu-open @endif">
                    <a href="#" class="nav-link @if($threeview == 'Coleta') active @endif">
                      <i class="nav-icon far fa-calendar-alt"></i>
                      <p>
                        Coleta
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="#" class="nav-link @if($namepage == 'Agendar coleta') active @endif">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Agendar</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#" class="nav-link @if($namepage == 'Listar coletas') active @endif">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Listar</p>
                        </a>
                      </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview @if($threeview == 'Cadastros') menu-open @endif">
                    <a href="#" class="nav-link @if($threeview == 'Cadastros') active @endif">
                      <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Cadastros
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link @if($namepage == 'Usuário') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Usuário</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/top-nav.html" class="nav-link @if($namepage == 'Dias sem coletas') active @endif">
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
                            <a href="pages/layout/top-nav-sidebar.html" class="nav-link @if($namepage == 'Cidade') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cidade</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/top-nav-sidebar.html" class="nav-link @if($namepage == 'Tipo de paciente') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipo de paciente</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/top-nav-sidebar.html" class="nav-link @if($namepage == 'Cancelamento de coleta') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cancelamento de coleta</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
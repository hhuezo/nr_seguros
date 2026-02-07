<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NR Seguros</title>

    <!-- Bootstrap -->
    <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">


    <!-- select 2 -->
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">

    <!-- Switchery -->
    <link href="{{ asset('vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('build/css/custom.min.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> --}}

    <style>
        @media (min-width: 768px) .form-horizontal .control-label {
            padding-top: 7px;
            margin-bottom: 0;
            /* text-align: right; */
        }
    </style>
    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            /* Ajustado a un tamaño más pequeño */
            height: 20px;
            /* Ajustado a un tamaño más pequeño */
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            /* Ajustado para ser proporcional al nuevo tamaño */
            width: 16px;
            /* Ajustado para ser proporcional al nuevo tamaño */
            left: 2px;
            /* Ajustado para posicionar correctamente el círculo */
            bottom: 2px;
            /* Ajustado para posicionar correctamente el círculo */
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(20px);
            /* Ajustado al nuevo ancho del switch */
            -ms-transform: translateX(20px);
            /* Ajustado al nuevo ancho del switch */
            transform: translateX(20px);
            /* Ajustado al nuevo ancho del switch */
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 20px;
            /* Proporcional al nuevo tamaño */
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

    <style>
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        #loading-overlay img {
            width: 50px;
            height: 50px;
        }
    </style>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <!-- <div class="navbar nav_title" style="border: 0;">
                       <a href="#" class="site_title"><i class="fa fa-spinner"></i> <span>Rec. Humanos</span></a>
                    </div>-->

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="{{ asset('img/usuario.svg') }}" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Bienvenido,</span>
                            <h2>{{ auth()->user()->name }}</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>NR Seguros</h3>
                            <ul class="nav side-menu">

                                @can('administracion menu')
                                    <li><a href="{{ url('/control-primas') }}"><i class="fa fa-bar-chart"></i>Control de
                                            Primas
                                            General</a></li>
                                @endcan

                                @can('seguridad menu')
                                    <li><a><i class="fa fa-users"></i> Seguridad <span
                                                class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu" id="ul-seguridad">
                                            @can('usuario read')
                                                <li id="li-catalogo-usuario"><a href="{{ url('usuario/') }}">Usuario</a></li>
                                            @endcan
                                            @can('permiso read')
                                                <li><a href="{{ url('permission/') }}">Permisos</a></li>
                                            @endcan
                                            @can('tipo-permiso read')
                                                <li><a href="{{ url('permission_type/') }}">Tipo permisos</a></li>
                                            @endcan
                                            @can('rol read')
                                                <li id="li-catalogo-role"><a href="{{ url('rol/') }}">Roles</a></li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('configuracion menu')
                                    <li><a><i class="fa fa-cog"></i> Configuracion<span
                                                class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu" id="ul-configuracion">
                                            @can('configuracion-recibo read')
                                                <li><a href="{{ url('catalogo/configuracion_recibo/1/edit') }}">Configuración
                                                        de Recibo</a></li>
                                            @endcan
                                            @can('numeracion-recibo read')
                                                <li><a href="{{ url('catalogo/numeracion_recibo') }}">Numeracion recibo</a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan
                                @can('catalogos menu')
                                    <li><a><i class="fa fa-folder"></i> Catálogos<span
                                                class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu" id="ul-catalogo">


                              
                                            @can('ejecutivos read')
                                                <li><a href="{{ url('catalogo/ejecutivos') }}">Ejecutivo</a></li>
                                            @endcan
                                            @can('estado-polizas read')
                                                <li><a href="{{ url('catalogo/estado_polizas') }}">Estado Póliza</a></li>
                                            @endcan
                                            @can('estado-venta read')
                                                <li><a href="{{ url('catalogo/estado_venta') }}">Estado Venta</a></li>
                                            @endcan
                                            @can('nr-cartera read')
                                                <li><a href="{{ url('catalogo/nr_cartera') }}">Tipo Cartera NR </a></li>
                                            @endcan
                                            @can('tipo-negocio read')
                                                <li><a href="{{ url('catalogo/tipo_negocio') }}">Tipo Negocio</a></li>
                                            @endcan
                                            @can('tipo-cobro read')
                                                <li><a href="{{ url('catalogo/tipo_cobro') }}">Tipo Cobro</a></li>
                                            @endcan
                                            @can('tipo-poliza read')
                                                <li><a href="{{ url('catalogo/tipo_poliza') }}">Tipo Póliza (ramo)</a></li>
                                            @endcan
                                            @can('area-comercial read')
                                                <li><a href="{{ url('catalogo/area_comercial') }}">Cargo o puesto</a></li>
                                            @endcan
                                            @can('ubicacion-cobro read')
                                                <li><a href="{{ url('catalogo/ubicacion_cobro') }}">Ubicación Cobro</a></li>
                                            @endcan
                                            @can('ramo read')
                                                <li><a href="{{ url('catalogo/necesidad_proteccion') }}">Ramos</a></li>
                                            @endcan
                                            @can('perfiles read')
                                                <li><a href="{{ url('catalogo/perfiles') }}">Requisitos de asegurabilidad</a>
                                                </li>
                                            @endcan
                                            @can('departamento-nr read')
                                                <li><a href="{{ url('catalogo/departamento_nr') }}">Departamentos NR</a></li>
                                            @endcan
                                            @can('producto read')
                                                <li  id="li-producto"><a href="{{ url('catalogo/producto') }}">Productos</a></li>
                                            @endcan
                                            @can('plan read')
                                                <li  id="li-plan"><a href="{{ url('catalogo/plan') }}">Planes</a></li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                <li><a><i class="fa fa-folder"></i> Suscripciones<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" id="ul-suscripciones">


                                        <li id="li-suscripciones"><a
                                                href="{{ url('suscripciones') }}">Suscripciones</a>
                                        </li>
                                        <li><a href="{{ url('fechasferiadas') }}">Fechas Feriadas </a></li>
                                        <li><a href="{{ url('estadoscasos') }}">Estado del Caso</a></li>
                                        <li><a href="{{ url('tiposordenesmedicas') }}">Tipo de Orden Medica</a></li>
                                        <li><a href="{{ url('tiposimc') }}">Tipo de IMC</a></li>
                                        <li><a href="{{ url('tiposclientes') }}">Tipo de Cliente </a></li>
                                        <li><a href="{{ url('ocupaciones') }}">Ocupación </a></li>
                                        <li><a href="{{ url('tipocreditos') }}">Tipos Crédito </a></li>




                                    </ul>
                                </li>

                                <li><a><i class="fa fa-folder-open"></i> Catálogos deuda<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" id="ul-poliza-deuda">
                                        <li><a href="{{ url('catalogo/tipo_cartera') }}">Linea de crédito</a></li>


                                    </ul>
                                </li>


                                <li><a><i class="fa fa-folder-o"></i> Catálogos vida<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" id="ul-poliza-vida">
                                        <li id="li-catalogo-vida-tipo-cartera"><a
                                                href="{{ url('catalogo/tipo_cartera_vida') }}">Tipo Cartera </a></li>


                                    </ul>
                                </li>


                                <li id="botonMenuCliente"><a><i class="fa fa-user"></i> Cliente <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul id="menuCliente" class="nav child_menu">
                                        <li id="opcionCliente"><a href="{{ url('catalogo/cliente') }}">Clientes</a>
                                        </li>
                                    </ul>
                                </li>

                                <li id="botonMenuNegocio"><a><i class="fa fa-solid fa-briefcase"></i> Cotizaciones
                                        <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" id="menuNegocio">
                                        <li id="opcionNegocio"><a href="{{ url('catalogo/negocio') }}">Negocio</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-shield"></i> Aseguradoras <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul id="ul-aseguradoras" class="nav child_menu">
                                        <li id="li-catalogo-aseguradora"><a
                                                href="{{ url('catalogo/aseguradoras') }}">Aseguradora </a></li>
                                        <!-- <li><a href="{{ url('catalogo/necesidad_aseguradora') }}">Asignar Necesidad de Protección <br>
                                Aseguradora</a></li> -->
                                    </ul>
                                </li>

                                <li><a><i class="fa fa-folder-open-o"></i> Pólizas<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" id="ul-poliza">
                                        <li id="li-poliza-residencia"><a
                                                href="{{ url('polizas/residencia') }}">Residencia</a></li>
                                        <li id="li-poliza-vida"><a href="{{ url('polizas/vida') }}">Vida</a></li>
                                        <li id="li-poliza-deuda"><a href="{{ url('polizas/deuda') }}">Deuda</a></li>
                                        <li id="li-poliza-desempleo"><a
                                                href="{{ url('polizas/desempleo') }}">Desempleo</a></li>
                                        <li id="li-poliza-seguro"><a href="{{ url('poliza/seguro') }}">Polizas
                                                seguro</a></li>
                                        <li id="li-control-cartera"><a href="{{ url('control_cartera') }}">Control
                                                carteras</a></li>
                                    </ul>
                                </li>

                                <!-- <li><a><i class="fa fa-file-pdf-o"></i> Reportes<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">

                                    <li><a href="{{ url('reportes/corte_caja') }}">Cortes de caja</a></li>

                                </ul>
                            </li> -->

                                <!-- <li><a><i class="fa fa-suitcase"></i> Validación<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">

                                    <li><a href="{{ url('polizas/validacion_cartera') }}">Validación de cartera</a></li>

                                </ul>
                            </li> -->
                            </ul>
                        </div>
                        <!-- sidebar menu -->
                    </div>
                    <!-- /sidebar menu -->

                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <img src="{{ asset('img/usuario.svg') }}"
                                        alt="">{{ auth()->user()->name }}
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">



                                    <li> <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Salir') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main"
                style=" background-repeat: no-repeat; background-size: 30% ; background-position-x:right ; background-position-y:bottom ;">

                @yield('contenido') <div class="x_content"></div>

            </div>
            <!-- /page content -->


        </div>
    </div>


    <!-- Bootstrap -->
    <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('vendors/fastclick/lib/fastclick.js') }}"></script>


    <!-- Custom Theme Scripts -->
    <script src="{{ asset('build/js/custom.min.js') }}"></script>

    <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <!-- Switchery -->
    <script src="{{ asset('vendors/switchery/dist/switchery.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('vendors/select2/select2.min.js') }}"></script>



    <!-- mascara de entrada -->
    <script src="{{ asset('vendors/input-mask/jquery.inputmask.js') }}"></script>


</body>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()
        //Dui
        $('[data-mask]').inputmask()
    });


    function displayOption(ul, li) {
        var _li = document.getElementById(li);

        // Verificar si el elemento <li> existe
        if (_li) {
            // Agregar la clase 'current-page' al elemento <li>
            _li.classList.add('current-page');

            // Obtener el elemento <ul> por su id
            var _ul = document.getElementById(ul);

            // Verificar si el elemento <ul> existe
            if (_ul) {
                // Establecer el display del <ul> a 'block'
                _ul.style.display = 'block';
            }
        }
    }
</script>

</html>

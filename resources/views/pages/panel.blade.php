@extends('layout')

@section('titulo')
    Panel | Grupo Verfrut
@endsection

@section('contenido')
    {{-- <div class="container p-5">
        <div class="text-center">
            <h3>¿Á que módulo deseas entrar?</h3>
        </div>
        <div class="py-5">
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/ingresos" {{ $data['usuario']->ingresos === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-arrow-up"></i> Ingresos Personal
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/cuentas" {{ $data['usuario']->cuentas === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-money-check"></i> Cambios/Aperturas Cuentas
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/formularios-permisos" {{ $data['usuario']->permisos === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-file-powerpoint"></i> Formularios de Permisos
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/eleccion-afp" {{ $data['usuario']->afp === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-file-alt"></i> Elección AFP
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/atencion-cambio-clave" {{ $data['usuario']->reseteo_clave === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-lock"></i> Cambio Clave TU RECIBO
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/sanciones" {{ $data['usuario']->sanciones === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-ban"></i> Sanciones
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/liquidaciones-utilidades" {{ $data['usuario']->liquidaciones === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-money-bill-wave"></i> Pagos
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/consulta-trabajadores" {{ $data['usuario']->consultas_trabajadores === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-users"></i> Consulta de Trabajadores
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/sctr" {{ $data['usuario']->sctr === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-file-alt"></i> SCTR
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/estado-documentos" {{ $data['usuario']->estado_documentos === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-file-alt"></i> Estado documentos TU RECIBO
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-block btn-light" href="/descansos-medicos" {{ $data['usuario']->descansos_medicos === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-user-md"></i> Informes Bienestar Social
                    </a>
                </div>
            </div>
        </div>
        <br />
        @if ($data['usuario']['rol'] === 'admin')
            <div class="text-center">
                <h4>Módulos de Administrador</h4>
            </div>
            <div class="py-5">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-block btn-light" href="/usuarios" {{ $data['usuario']->usuarios === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-user-friends"></i> Gestión de Usuarios
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-block btn-light" href="/aplicacion" {{ $data['usuario']->aplicacion === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-mobile-alt"></i> Panel de Control (Aplicación)
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div> --}}

    <div id="panel"></div>

    <script>
        sessionStorage.setItem('data', JSON.stringify(@json($data)) );
    </script>
@endsection

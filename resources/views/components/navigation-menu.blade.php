
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                
                <div class="sb-sidenav-menu-heading">Modulos</div>
                @foreach ($modulos as $modulo)
                <a class="nav-link" href="{{ route($modulo->enlace . '.index') }}">

                    <div class="sb-nav-link-icon"><i class="{{ $modulo->icono }}"></i></div>
                    {{ $modulo->nombre }}
                </a>

                    {{-- <a href="{{ route($modulo->enlace . '.index') }}" class="list-group-item list-group-item-action">
                        {{ $modulo->nombre }}
                        <i class="{{ $modulo->icono }}"></i>
                    </a> --}}
                @endforeach


            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small"> Bienvenido</div>
            {{ Auth::user()->nombre }}



        </div>
    </nav>
</div>

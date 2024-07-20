@extends("layout")


@section('titulo')
Okrs Organización
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset("css/stylesOkrs.min.css")}}">
@endsection

@section('headerPage')
<header class="main-heading">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-icon">
                    <i class="icon-shareable"></i>
                </div>
                <div class="page-title">
                    <h4>OKR's Organización</h4>
                </div>
            </div>
        </div>
    </div>
</header>
@endsection

@section('contenido')
<div class="row gutters">
    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="progresos" data-bs-toggle="tooltip" align="center">
                    <h1 style="font-size: 3.5rem;color: black !important;"><b>{{ $PorcentajeFinalBarra }}%</b></h1>
                </div>
                <div id="progresoOKRS" class="progress-bar bg-success" role="progressbar" style="width:{{ $PorcentajeBarra }}%;{{ $ColorPorcentaje }};" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                </div><br>
            </div>
        </div>
    </div>
</div>
@foreach($Okrs as $okr)
<div class="row gutters">
    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header" id="headerOKR">
                <table style="width: 100%;border-style: hidden;margin-bottom: -10px;" class="table" id="OKR_id_{{$okr['id_okrs']}}">
                    <thead>
                        <th style="align-content: center;width:10%;text-align: center;">
                            <div class="progreso-bar-container" style="--i:{{ $okr['porcentaje'] }};--clr:{{ $okr['color_bg'] }}">
                                <div class="progreso-bar objetivo-okr">
                                    <progreso id="objetivo-okr" min="0" value="{{ $okr['porcentaje'] }}"></progreso>
                                </div>
                            </div>
                        </th>
                        <th style="width: 10%;align-content: center;text-align: center;" id="fotoOwnerOkr">
                            <div class="user-profile">{!! $okr['foto'] !!}</div>
                        </th>
                        <th style="width: 75%;font-size: 1rem;align-content: center;">
                            <h5><b>{{ $okr['objetivo_okr'] }}</b><br></h5>
                            <b>OKR: </b>{{ $okr['tipo'] }}<br>
                            <b>Owner: </b>{{ $okr['nombre_owner'] }}<br>
                            <div class="sub_periodo">
                                {{ $okr['anio'] }}: {{ $okr['fecha_inicia'] }} a {{ $okr['fecha_termina'] }} || Publicado por: {{ $okr['nombre_owner'] }}
                            </div>
                        </th>

                    </thead>
                </table>
            </div>

            <div class="card-body responsive fill_resultados" id="bodyOKR">
                <div class="row gutters">
                    <div class="col-md-12 col-sm-12">
                        <div id="accordionIcons{{$okr['id_okrs']}}" class="accordion-icons" role="tablist">
                            @foreach($okr['kr'] as $kr)
                            <div class="card mb-0">
                                <div class="card-header" role="tab" id="heading{{$kr['id']}}">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <h5 class="fill_resultados">
                                                <a class="collapsed" data-toggle="collapse" href="#collapseKr{{$kr['id']}}" aria-expanded="false" aria-controls="collapseKr{{$kr['id']}}" id="datosResultados_{{$kr['id']}}" style="color: black !important;">
                                                    {{ $kr['periodo'] }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ $kr['descripcion'] }}
                                                </a>
                                            </h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h5 class="fill_resultados">
                                                Meta:&nbsp;{{ $kr['txt_meta'] }}
                                            </h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="fill_resultados">
                                                {!! $kr['porcentajeBarra'] !!}&nbsp;&nbsp;{{ $kr['porcentaje'] }} %
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseKr{{$kr['id']}}" class="collapse" role="tabpanel" aria-labelledby="heading{{$kr['id']}}" data-parent="#accordionIcons{{$okr['id_okrs']}}">
                                    <div class="card-body">
                                        <div class="row">
                                            @handheld
                                            <div class="col md-12">
                                                <table class="table table-sm" id="TableKrMovil">
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Responsables</b></td>
                                                            <td clase="detalleKrIniciativa">{!! $kr['listaResponsables'] !!}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Inicia</b></td>
                                                            <td clase="detalleKrIniciativa">{{ $kr['fecha_inicia'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Entrega</b></td>
                                                            <td clase="detalleKrIniciativa">{{ $kr['fecha_entrega'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Días Faltantes</b></td>
                                                            <td clase="detalleKrIniciativa">{!! $kr['porcentaje_dias'] !!}Faltan {{ $kr['dias_faltantes'] }} días</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Seguimiento</b></td>
                                                            <td clase="detalleKrIniciativa">{!! $kr['avance'] !!}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Acciones</b></td>
                                                            <td clase="detalleKrIniciativa">{!! $kr['acciones'] !!}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            @elsehandheld
                                            <div class="col-md-3"><b>Responsables</b><br>{!! $kr['listaResponsables'] !!}</div>
                                            <div class="col-md-2"><b>Inicia</b><br>{{ $kr['fecha_inicia'] }}</div>
                                            <div class="col-md-2"><b>Entrega</b><br>{{ $kr['fecha_entrega'] }}</div>
                                            <div class="col-md-2"><b>Días Faltantes</b>{!! $kr['porcentaje_dias'] !!}Faltan {{ $kr['dias_faltantes'] }} días</div>
                                            <div class="col-md-2"><b>Seguimiento</b>{!! $kr['avance'] !!}</div>
                                            <div class="col-md-1" style="text-align: right;"><b>Acciones</b><br>{!! $kr['acciones'] !!}</div>
                                            @endhandheld
                                        </div>
                                    </div>
                                    @if($kr['iniciativas'])
                                    <div class="card-footer">
                                        @handheld
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div id="accordionIcons{{$kr['id']}}" class="accordion-icons" role="tablist">
                                                    @foreach($kr['iniciativas'] as $iniciativa)
                                                    <div class="card mb-0">
                                                        <div class="card-header" role="tab" id="header{{$kr['id']}}_{{$iniciativa['id']}}">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <h5 class="fill_iniciativas">
                                                                        <a class="collapsed" data-toggle="collapse" href="#collapsin{{$iniciativa['id']}}" aria-expanded="false" aria-controls="collapsin{{$iniciativa['id']}}" id="datosIniciativas_{{$iniciativa['id']}}" style="color: black !important;">
                                                                            {{$iniciativa['descripcion']}}
                                                                        </a>
                                                                    </h5>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <h5 class="fill_iniciativas">
                                                                        Meta:&nbsp;{{$iniciativa['meta']}}
                                                                    </h5>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <h5 class="fill_iniciativas">
                                                                        {!! $iniciativa['porcentajeBarra'] !!}&nbsp;&nbsp;{{ $iniciativa['porcentaje'] }} %
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="collapsin{{$iniciativa['id']}}" class="collapse" role="tabpanel" aria-labelledby="header{{$kr['id']}}_{{$iniciativa['id']}}" data-parent="#accordionIcons{{$kr['id']}}">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col md-12">
                                                                        <table class="table table-sm" id="TableIniciativaMovil">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td><b>Responsables</b></td>
                                                                                    <td clase="detalleKrIniciativa">{!!$iniciativa['responsables']!!}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Entrega</b></td>
                                                                                    <td clase="detalleKrIniciativa">{{$iniciativa['entrega']}}</td>
                                                                                </tr>
                                                                                <tr id="fila_{{$iniciativa['id']}}">
                                                                                    <td><b>Seguimiento</b></td>
                                                                                    <td clase="detalleKrIniciativa">{!!$iniciativa['avance']!!}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Acciones</b></td>
                                                                                    <td clase="detalleKrIniciativa" style="text-align: right;text-align:-webkit-right;">{!! $iniciativa['acciones'] !!}</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @elsehandheld
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <table class="table table-responsive" id="IniciativasKR{{$kr['id']}}">
                                                    <thead>
                                                        <th style="width: 50% !important;"><b>Iniciativa</b></th>
                                                        <th style="width: 10% !important;"><b>Responsables</b></th>
                                                        <th style="width: 10% !important;"><b>Entrega</b></th>
                                                        <th style="width: 5% !important;"><b>Meta</b></th>
                                                        <th style="width: 10% !important;"><b>Seguimiento</b></th>
                                                        <th style="width: 15% !important;"><b>% Iniciativa</b></th>
                                                        <th style="text-align: right;width: 10% !important;"><b>Acciones</b></th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($kr['iniciativas'] as $iniciativa)
                                                        <tr id="fila_{{$iniciativa['id']}}">
                                                            <td style="width: 50% !important;">{{$iniciativa['descripcion']}}</td>
                                                            <td style="width: 10% !important;">{!!$iniciativa['responsables']!!}</td>
                                                            <td style="width: 10% !important;">{{$iniciativa['entrega']}}</td>
                                                            <td style="width: 5% !important;">{{$iniciativa['meta']}}</td>
                                                            <td style="width: 10% !important;">{!!$iniciativa['avance']!!}</td>
                                                            <td style="width: 15% !important;">{!! $iniciativa['porcentajeBarra'] !!}&nbsp;&nbsp;{{ $iniciativa['porcentaje'] }} %</td>
                                                            <td style="text-align: right;text-align:-webkit-right;width: 10% !important;">{!! $iniciativa['acciones'] !!}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        @endhandheld
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endforeach

<div class="row gutters">
    <div class="col-md-12 col-sm-12">
        {{$paginacion->links()}}
    </div>
</div>
@include("modals.modalProfile")
@endsection
@section('scripts')
<script src="{{asset("js/okrs.min.js")}}"></script>
<script>
    $(document).ready(function() {
        $("#laretalOkrsEquipo").addClass("active selected");
        $("#navOkrs").addClass("in");
        $("#menuOkrsOrganizacion").addClass("current-page");
        $('.js-example-basic-single').select2();
        $(".pagination").addClass("table-responsive mb-2");

    });

    document.addEventListener("DOMContentLoaded", function() {
        var Iniciativa = "{{ $IniciativaKR }}";
        var Resultado = "{{ $ResultadoOKR }}";
        var divElement = document.getElementById("datosResultados_{{ $ResultadoOKR }}");
        var divElementKr = document.getElementById("heading{{ $ResultadoOKR }}");
        var divIniciativaM = document.getElementById("datosIniciativas_{{ $IniciativaKR }}");
        var divIniciativaMH = document.getElementById("header{{ $ResultadoOKR }}_{{$IniciativaKR}}");
        var divIniciativa = document.getElementById("IniciativasKR{{$ResultadoOKR}}");

        if (Iniciativa) {
            @handheld
            if (divIniciativaMH) {
                $("#datosResultados_{{ $ResultadoOKR }}").removeClass("collapsed");
                document.getElementById("datosResultados_{{ $ResultadoOKR }}").setAttribute("aria-expanded", true);
                $("#collapseKr{{ $ResultadoOKR }}").addClass("show");
                $('html, body').animate({
                    scrollTop: $("#header{{ $ResultadoOKR }}_{{$IniciativaKR}}").offset().top - 90
                }, 2000);
                $("#datosIniciativas_{{ $IniciativaKR }}").removeClass("collapsed");
                document.getElementById("datosIniciativas_{{ $IniciativaKR }}").setAttribute("aria-expanded", true);
                $("#collapsin{{ $IniciativaKR }}").addClass("show");
            }
            @elsehandheld
            if (divIniciativa) {
                $("#datosResultados_{{ $ResultadoOKR }}").removeClass("collapsed");
                document.getElementById("datosResultados_{{ $ResultadoOKR }}").setAttribute("aria-expanded", true);
                $("#collapseKr{{ $ResultadoOKR }}").addClass("show");
                $('html, body, #heading{{ $ResultadoOKR }}').animate({
                    scrollTop: $("#fila_{{ $IniciativaKR }}").offset().top - 200
                }, 2000);
            }
            @endhandheld
        }
        if (Resultado) {
        if (divElement) {
            // bootstrap.ScrollSpy.getInstance(divElementKr);
            $('html, body').animate({
                scrollTop: $("#heading{{ $ResultadoOKR }}").offset().top - 90
            }, 2000);
            $("#datosResultados_{{ $ResultadoOKR }}").removeClass("collapsed");
            document.getElementById("datosResultados_{{ $ResultadoOKR }}").setAttribute("aria-expanded", true);
            $("#collapseKr{{ $ResultadoOKR }}").addClass("show");
        }
    }

        // divElement.scrollIntoView(false);
        // divIniciativa.scrollIntoView(false);

    });
    window.location.hash = "";
    window.location.hash = "";
</script>
@endsection
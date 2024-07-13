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
                            <b>{{ $okr['objetivo_okr'] }}</b><br>
                            <b>OKR: </b>{{ $okr['tipo'] }}<br>
                            <b>Owner: </b>{{ $okr['nombre_owner'] }}<br>
                            <div class="sub_periodo">
                                {{ $okr['anio'] }}: {{ $okr['fecha_inicia'] }} a {{ $okr['fecha_termina'] }} || Publicado por: {{ $okr['nombre_owner'] }}
                            </div>
                        </th>

                    </thead>
                </table>
            </div>

            <div class="card-body responsive" id="bodyOKR">
                <div class="row gutters">
                    <div class="col-md-12 col-sm-12">
                        <div id="accordionIcons" class="accordion-icons" role="tablist">
                            @foreach($okr['kr'] as $kr)
                            <div class="card mb-0">
                                <div class="card-header" role="tab" id="heading{{$kr['id']}}">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <h5 class="fill_resultados">
                                                <a class="collapsed" data-toggle="collapse" href="#collapse{{$kr['id']}}" aria-expanded="false" aria-controls="collapse{{$kr['id']}}">
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
                                <div id="collapse{{$kr['id']}}" class="collapse" role="tabpanel" aria-labelledby="heading{{$kr['id']}}" data-parent="#accordionIcons">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- <div class="col-md-12 table-responsive">
                                                <table style="width: 100%;" class="display table dt-responsive nowrap">
                                                    <thead>
                                                        <th>Responsables</th>
                                                        <th>Inicia</th>
                                                        <th>Entrega</th>
                                                        <th>Días Faltantes</th>
                                                        <th>Seguimiento</th>
                                                        <th style="text-align: right !important;">Acciones</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $kr['porcentaje'] }}</td>
                                                            <td>{{ $kr['porcentaje'] }}</td>
                                                            <td>{{ $kr['porcentaje'] }}</td>
                                                            <td>{{ $kr['porcentaje'] }}</td>
                                                            <td>{{ $kr['porcentaje'] }}</td>
                                                            <td>{{ $kr['porcentaje'] }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> -->
                                            <div class="col-md-3"><b>Responsables</b><br>{!! $kr['listaResponsables'] !!}</div>
                                            <div class="col-md-2"><b>Inicia</b><br>{{ $kr['fecha_inicia'] }}</div>
                                            <div class="col-md-2"><b>Entrega</b><br>{{ $kr['fecha_entrega'] }}</div>
                                            <div class="col-md-2"><b>Días Faltantes</b>{!! $kr['porcentaje_dias'] !!}Faltan {{ $kr['dias_faltantes'] }} días</div>
                                            <div class="col-md-1"><b>Seguimiento</b>{!! $kr['avance'] !!}</div>
                                            <div class="col-md-2"><b>Acciones</b></div>
                                        </div>
                                    </div>
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
        
        $('table.display').DataTable({
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                },
                {
                    responsivePriority: 2,
                    targets: -1
                }
            ],
            responsive: true,
            info: false,
            ordering: false,
            paging: false,
            searching: false,
            autoWidth: true,
            language: {
                processing: "Procesando...",
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros.",
                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                infoEmpty: "Mostrando registros del 0 al 0 de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                infoPostFix: "",
                loadingRecords: "Cargando...",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "Ningún dato disponible en esta tabla",
                row: "Registro",
                export: "Exportar",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Ultimo"
                },
                aria: {
                    sortAscending: ": Activar para ordenar la columna de manera ascendente",
                    sortDescending: ": Activar para ordenar la columna de manera descendente"
                },
                select: {
                    row: "registro",
                    selected: "seleccionado"
                }
            }

        });

    });
</script>

@endsection
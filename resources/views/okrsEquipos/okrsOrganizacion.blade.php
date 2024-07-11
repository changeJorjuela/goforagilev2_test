@extends("layout")


@section('titulo')
Okrs Organización
@endsection

@section('styles')
<style>
    .pagination {
        margin: 2px 0;
        white-space: nowrap;
        justify-content: flex-end;
    }

    @media screen and (max-width: 767px) {
        .pagination {
            justify-content: left !important;
        }
    }
</style>
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
                <table style="width: 100%;border-style: hidden;" class="table" id="OKR_id_{{$okr['id_okrs']}}">
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
                    <div class="col-sm-12">
                        <div id="accordionIcons" class="accordion-icons" role="tablist">
                            @foreach($okr['kr'] as $kr)
                            <div class="card mb-0">
                                <div class="card-header" role="tab" id="heading{{ $kr['id'] }}">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapse{{ $kr['id'] }}" aria-expanded="false" aria-controls="collapse{{ $kr['id'] }}" class="collapsed">
                                            Who cen sell items?
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapse{{ $kr['id'] }}" class="collapse" role="tabpanel" aria-labelledby="heading{{ $kr['id'] }}" data-parent="#accordionIcons">
                                    <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad
                                        squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa
                                        nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid
                                        single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft
                                        beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice
                                        lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you
                                        probably haven't heard of them accusamus labore sustainable VHS.
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
<script>
    $(document).ready(function() {
        $("#laretalOkrsEquipo").addClass("active selected");
        $("#navOkrs").addClass("in");
        $("#menuOkrsOrganizacion").addClass("current-page");
        $('.js-example-basic-single').select2();
    });
</script>
<script src="{{asset("js/okrs.min.js")}}"></script>
@endsection
{!! Form::open(['url' => 'filtrarOkrsO', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off','id'=>'form-filtroOKR_upd']) !!}
@csrf
<div class="form-group">
    <div class="row">

        @if(Session::get('id_empresa') == 1)
        <div class="col-md-3">
            {!! Form::select('vicepresidencia_fill',$VicepresidenciasFiltro,Session::get('id_vicepresidencia'),['class'=>'form-control','id'=>'vicepresidencia_fill']) !!}
        </div>
        @endif
        <div class="col-md-3">
            {!! Form::select('areas_fill',$AreasFiltro,Session::get('area_fill'),['class'=>'form-control','id'=>'areas_fill']) !!}
        </div>
        <div class="col-md-3">
            {!! Form::select('responsable_fill',$ResponsablesFiltro,Session::get('responsable_fill'),['class'=>'form-control','id'=>'responsable_fill']) !!}
        </div>
        <div class="col-md-3">
            {!! Form::select('objestrategico_fill',$ObjEstrategicoFiltro,Session::get('obj_estrategico_fill'),['class'=>'form-control','id'=>'objestrategico_fill']) !!}
        </div>
        <div class="col-md-3">
            {!! Form::select('okr_fill',$OkrsFiltro,Session::get('okr_fill'),['class'=>'form-control','id'=>'okr_fill']) !!}
        </div>
        <div class="col-md-2">
            {!! Form::select('tipo_okr_fill',$TipoOkrFiltro,Session::get('tipo_okr_fill'),['class'=>'form-control','id'=>'tipo_okr_fill']) !!}
        </div>
        <div class="col-md-1">
            {!! Form::select('anio_fill',$AnioOkrFiltro,Session::get('anio_fill'),['class'=>'form-control','id'=>'anio_fill']) !!}
        </div>
        <div class="col-md-1" style="align-content: center;">
            Periodo:
        </div>
        <div class="col-md-1" style="align-content: center;">
            <input type="checkbox" name="Q1" value="on" class="form-control-input" {{ Session::get('Q1') == 'on' ? 'checked' : '' }}>Q1
        </div>
        <div class="col-md-1" style="align-content: center;">
            <input type="checkbox" name="Q2" value="on" class="form-control-input" {{ Session::get('Q2') == 'on' ? 'checked' : '' }}>Q2
        </div>
        <div class="col-md-1" style="align-content: center;">
            <input type="checkbox" name="Q3" value="on" class="form-control-input" {{ Session::get('Q3') == 'on' ? 'checked' : '' }}>Q3
        </div>
        <div class="col-md-1" style="align-content: center;">
            <input type="checkbox" name="Q4" value="on" class="form-control-input" {{ Session::get('Q4') == 'on' ? 'checked' : '' }}>Q4
        </div>
        <div class="col-md-1" style="align-content: center;">
            <input type="checkbox" name="Anual" value="on" class="form-control-input" {{ Session::get('Anual') == 'on' ? 'checked' : '' }}>Anual
        </div>
        
    </div>
</div>
{!! Form::close() !!}
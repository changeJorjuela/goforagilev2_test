<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\GoForAgileAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\GoForAgileOkrs;

class OkrsController extends Controller
{
    public function OkrsOrganizacion()
    {
        $filtro = "";
        $avance_general = $count_avance_general_equipo = 0;
        $OkrsOrganizacion = GoForAgileOkrs::OkrsOrganizacion(Session::get('id_empresa'));
        // dd($OkrsOrganizacion);
        $array_okrs = $array_resultados = $array_iniciativas = array();
        $contOkrs = $contKR = $contIni = 0;
        foreach ($OkrsOrganizacion as $value) {
            $promedio = GoForAgileOkrs::ResultadosOKR($value->id_okrs);
            $porcentaje_avance = round($promedio["promedio"]);

            if (is_nan($porcentaje_avance)) {
                $porcentaje_avance = 0;
            }

            $avance_general += $porcentaje_avance;
            $count_avance_general_equipo++;
        }
        $porcentaje_final = round(($avance_general / $count_avance_general_equipo), 2);
        if (is_nan($porcentaje_final)) {
            $porcentaje_final = 0;
        }
        if ($porcentaje_final > 100) {
            $porcentaje_barra = 100;
        } else {
            $porcentaje_barra = $porcentaje_final;
        }
        $porcentajeFinalBarra = round($porcentaje_final, 2);
        $EscalaColor = GoForAgileOkrs::EscalaColor($porcentaje_final, Session::get('id_empresa'));
        $backgroundColor = "background-color:" . $EscalaColor['color_bg'] . " !important";

        foreach ($OkrsOrganizacion as $row) {
            $array_okrs[$contOkrs]['id_okrs'] = $row->id_okrs;
            $empleado = GoForAgileAdmin::EmpleadoId($row->id_empleado);
            foreach ($empleado as $emp) {
                $array_okrs[$contOkrs]['publicado'] = $emp->nombre;
            }
            $array_okrs[$contOkrs]['nombre_owner'] = $row->nombre_owner;
            $array_okrs[$contOkrs]['objetivo_okr'] = $row->objetivo_okr;
            if ($row->tipo == 1) {
                $array_okrs[$contOkrs]['tipo'] = 'Organizacional';
            } else {
                $array_okrs[$contOkrs]['tipo'] = 'Equipo';
            }
            $array_okrs[$contOkrs]['anio'] = $row->anio;
            $array_okrs[$contOkrs]['fecha_inicia'] = GoForAgileAdmin::FechaAmigable($row->fecha_inicia);
            $array_okrs[$contOkrs]['fecha_termina'] = GoForAgileAdmin::FechaAmigable($row->fecha_termina);
            $array_okrs[$contOkrs]['nombre_owner'] = $row->nombre_owner;
            $dataOwner  = GoForAgileAdmin::EmpleadoId($row->id_owner);
            foreach ($dataOwner as $owner) {
                if (!$owner->foto) {
                    $foto_owner = "img_default.jpg";
                } else {
                    $foto_owner = $owner->foto;
                }
            }
            $array_okrs[$contOkrs]["foto"] = '<a class="profile-thumb" href="javascript:Profile(' . $row->id_okrs . ',1)"><img loading="lazy" src="../../recursos/' . $foto_owner . '" class="profile-thumb" title="' . $row->nombre_owner . '" style="width:70px;height:70px;"></a>';
            $promedio = GoForAgileOkrs::ResultadosOKR($row->id_okrs);
            $porcentaje_avance = round($promedio["promedio"]);
            if (is_nan($porcentaje_avance)) {
                $porcentaje_avance = 0;
            }
            $array_okrs[$contOkrs]['porcentaje'] = $porcentaje_avance;
            $EscalaColor = GoForAgileOkrs::EscalaColor($porcentaje_avance, Session::get('id_empresa'));
            $array_okrs[$contOkrs]['color_bg'] = $EscalaColor["color_bg"];
            GoForAgileOkrs::OrderResultados($row->id_okrs);
            $contOkrs++;
            $resultadosVisual = GoForAgileOkrs::ResultadosOKRFiltro($row->id_okrs,$filtro);
            foreach($resultadosVisual as $resultado){

            }
        }

        return view('okrsEquipos.okrsOrganizacion', [
            'PorcentajeFinal' => $porcentaje_final, 'PorcentajeBarra' => $porcentaje_barra, 'PorcentajeFinalBarra' => $porcentajeFinalBarra, 'ColorPorcentaje' => $backgroundColor,
            'Okrs' => $array_okrs
        ]);
    }
}

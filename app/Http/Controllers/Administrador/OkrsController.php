<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\GoForAgileOkrs;

class OkrsController extends Controller
{
    public function OkrsOrganizacion(){
        $avance_general = $count_avance_general_equipo = 0;
        $OkrsOrganizacion = GoForAgileOkrs::OkrsOrganizacion(Session::get('id_empresa'));
        foreach($OkrsOrganizacion as $value){
            $promedio = GoForAgileOkrs::ResultadosOKR($value->id);
            $porcentaje_avance = round($promedio["promedio"]);
        
            if(is_nan($porcentaje_avance)){
                $porcentaje_avance = 0;
            }
            
            $avance_general += $porcentaje_avance;
            $count_avance_general_equipo++;
        }
        $porcentaje_final = round(($avance_general / $count_avance_general_equipo), 2);
        if (is_nan($porcentaje_final)) {
            $porcentaje_final = 0;
        }
        if($porcentaje_final > 100){
            $porcentaje_barra = 100;
        }else{
            $porcentaje_barra = $porcentaje_final;
        }
        $porcentajeFinalBarra = round($porcentaje_final, 2);
        $EscalaColor = GoForAgileOkrs::EscalaColor($porcentaje_final, Session::get('id_empresa'));
        $backgroundColor = "background-color:" . $EscalaColor['color_bg'] . " !important";
        return view('okrsEquipos.okrsOrganizacion',['PorcentajeFinal' => $porcentaje_final, 'PorcentajeBarra' => $porcentaje_barra,'PorcentajeFinalBarra' => $porcentajeFinalBarra, 'ColorPorcentaje' => $backgroundColor]);
    }
}

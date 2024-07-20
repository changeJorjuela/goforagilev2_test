<?php

namespace App\Http\Controllers;

use App\Models\GoForAgileAdmin;
use Illuminate\Http\Request;
use App\Models\GoForAgileOkrs;
use Illuminate\Support\Facades\Session;

class ExtrasController extends Controller
{
    public function ProfileEmpleado(Request $request)
    {
        $profile = GoForAgileAdmin::CardProfile($request->id);
        $infoProfile = "";
        foreach ($profile as $value) {
            $nombre = $value["nombre"];
            $fotoProfile = $value["foto"];
            $cargo = strtoupper($value["cargo"]);
            $area = $value["area"];
            $vp = $value["vicepresidencia"];
        }
        if ($fotoProfile) {
            $foto = $fotoProfile;
        } else {
            $foto = "img_default.jpg";
        }

        if(Session::get('id_empresa') == 1){
            $vicepresidencia = '<div class="row">
                                    <div class="col-md-12">
                                        <p style="font-size: 15px">
                                            <b>Vicepresidencia</b>: ' . $vp . '
                                        </p>                                        
                                    </div>
                                </div>';
        }else{
            $vicepresidencia = '';
        }

        $infoProfile = ' <div class="row">
                            <div class="col-md-6" style="text-align: center;align-content: center;">
                                <img src="../../recursos/' . $foto . '" alt="image" style="width:70%;">                                    
                            </div>
                            <div class="col-md-6" style="align-content: center;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 style="font-family: Montserrat-Bold !important;">' . $nombre . '</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p style="font-size: 15px">
                                            <b>Cargo</b>: ' . $cargo . '
                                        </p>                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p style="font-size: 15px">
                                        <b>Área</b>: ' . $area . '
                                        </p>                                        
                                    </div>
                                </div>
                                '.$vicepresidencia.'
                            </div>
                        </div>               
                    ';

        return $infoProfile;
    }

    public function GuardarAvanceResultado(Request $request){
        
        $idKr = $request->id;
        $idOkr = $request->id_okr;
        $idEmpresa = $request->id_empresa;
        $idUser = $request->id_user;
        $avance = $request->avance;
        
        $avance = GoForAgileOkrs::GuardarAvanceKr($idKr, $avance, $idOkr, $idEmpresa, $idUser);
        $respuesta = "false";
        if($avance){
            $respuesta = "true";
        }

        return $respuesta;
    }

    public function GuardarAvanceIniciativa(Request $request){
        
        $id = $request->id;
        $idKr = $request->id_resultado;
        $idOkr = $request->id_okr;
        $idEmpresa = $request->id_empresa;
        $idUser = $request->id_user;
        $avance = $request->avance;
        
        $avance = GoForAgileOkrs::GuardarAvanceIniciativa($id, $idKr, $avance, $idOkr, $idEmpresa, $idUser);
        $respuesta = "false";
        if($avance){
            $respuesta = "true";
        }

        return $respuesta;
    }
}

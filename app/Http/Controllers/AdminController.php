<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\GoForAgileAdmin;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function CrearArea(Request $request){
        $url = AdminController::FindUrl();
        date_default_timezone_set('America/Bogota');
        $validator = Validator::make($request->all(), [
            'nombre_area' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::to($url.'areas')->withErrors($validator)->withInput();
        }else{
            $nombreArea = $request->nombre_area;
            $padre = $request->padre;
            $jerarquia = $request->jerarquia;
            $BuscarArea = GoForAgileAdmin::BuscarNombreArea($nombreArea,(int)Session::get('id_empresa'));
            if($BuscarArea){
                $verrors = array();
                array_push($verrors, 'Nombre de area ya existe');
                return Redirect::to($url.'areas')->withErrors(['errors' => $verrors])->withInput();
            }else{
                $CrearArea = GoForAgileAdmin::CrearArea($nombreArea,$padre,$jerarquia,(int)Session::get('id_empresa'));
                if($CrearArea){
                    $verrors = 'Se creo el área '.$nombreArea.' con éxito.';
                    return Redirect::to($url.'areas')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear el area');
                    return Redirect::to($url.'tipoTarifa')->withErrors(['errors' => $verrors])->withInput();
                }
            }            
        }
    }

    public function ActualizarArea(Request $request){
        $url = AdminController::FindUrl();
        
        $validator = Validator::make($request->all(), [
            'nombre_area_upd' => 'required',
            'estado_upd' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::to($url.'areas')->withErrors($validator)->withInput();
        }else{
            $nombreArea = $request->nombre_area_upd;
            $padre = $request->padre_upd;
            $jerarquia = $request->jerarquia_upd;
            $estado = $request->estado_upd;
            $idArea = $request->id_area;
            $BuscarArea = GoForAgileAdmin::BuscarNombreAreaUpd($nombreArea,$idArea);
            if($BuscarArea){
                $verrors = array();
                array_push($verrors, 'Nombre de area ya existe');
                return Redirect::to($url.'areas')->withErrors(['errors' => $verrors])->withInput();
            }else{
                $ActualizarArea = GoForAgileAdmin::ActualizarArea($nombreArea,$padre,$jerarquia,$estado,$idArea);
                if($ActualizarArea){
                    $verrors = 'Se actualizo el área '.$nombreArea.' con éxito.';
                    return Redirect::to($url.'areas')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al actualizar el area');
                    return Redirect::to($url.'tipoTarifa')->withErrors(['errors' => $verrors])->withInput();
                }
            }            
        }
    }

    public static function FindUrl(){
        $RolUser = (int)Session::get('role_plataforma');
        switch($RolUser){
            case 1: $url = 'admin/';
                    break;
            case 2: return Redirect::to('lider/home');
                    break;
            case 3: return Redirect::to('colaborador/home');
                    break;
            default: return Redirect::to('/');
                    break;
        }
        return $url;
    }
}

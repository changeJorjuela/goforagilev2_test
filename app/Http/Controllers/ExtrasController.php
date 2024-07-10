<?php

namespace App\Http\Controllers;

use App\Models\GoForAgileAdmin;
use Illuminate\Http\Request;
use App\Models\GoForAgileOkrs;

class ExtrasController extends Controller
{
    public function ProfileEmpleado(Request $request){
        $profile = GoForAgileAdmin::CardProfile($request->id);
        
        foreach($profile as $value){            
            $nombre = $value["nombre"];
            $fotoProfile = $value["foto"];
            $cargo = $value["cargo"];
            $area = $value["area"];
            $vp = $value["vicepresidencia"];
        }
        if($fotoProfile){
            $foto = $fotoProfile;
        }else{
            $foto = "img_default.jpg";
        }
        return view('modals.modalProfile',['Nombre' => $nombre, 'Foto' => $foto, 'Cargo' => $cargo, 'Area' => $area, 'VP' => $vp]);
    }
}

<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\GoForAgileAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\GoForAgileOkrs;
use Illuminate\Pagination\LengthAwarePaginator;

class OkrsController extends Controller
{
    public function OkrsOrganizacion(Request $request)
    {
        $porPagina = 10;
        $paginaActual = LengthAwarePaginator::resolveCurrentPage('pagina');
        // dd(LengthAwarePaginator::resolveCurrentPage('pagina'));
        $offset = ($paginaActual - 1) * $porPagina;
        $filtro = $nombre = $foto = $cargo = $area = $vp = "";
        $avance_general = $count_avance_general_equipo = 0;
        $prueba = GoForAgileOkrs::OkrsOrganizacion(Session::get('id_empresa'), null, null);
        $OkrsOrganizacion = GoForAgileOkrs::OkrsOrganizacion(Session::get('id_empresa'), $porPagina, $offset);
        // dd($count);
        $array_okrs = $array_iniciativas = array();
        $contOkrs = $contKR = $contIni = 0;
        foreach ($prueba as $value) {
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
            $dataOwner  = GoForAgileAdmin::CardProfile($row->id_owner);
            foreach ($dataOwner as $owner) {
                if (!$owner["foto"]) {
                    $foto_owner = "img_default.jpg";
                } else {
                    $foto_owner = $owner["foto"];
                }
                $cargo_owner = $owner["cargo"];
                $area_owner = $owner["area"];
                $vp_owner = $owner["vicepresidencia"];
            }

            $array_okrs[$contOkrs]["foto"] =  '<article class="card__article">
            <div class="card__profile">
               <img loading="lazy" src="../../recursos/' . $foto_owner . '" class="profile-thumb" title="' . $row->nombre_owner . '" style="width:70px;height:70px;">
            </div>
            <div class="card__tooltip">
               <div class="card__content">      
                  <div class="card__data">
                     <div class="card__image">
                        <div class="card__mask">
                           <img src="../../recursos/' . $foto_owner . '" alt="image" class="card__img">
                        </div>
                     </div>
                     <h2 class="card__name">' . $row->nombre_owner . '</h2><br>
                     <h3 class="card__profession">' . $cargo_owner . '</h3><br>
                     <h3 class="card__profession">' . $area_owner . '</h3><br>
                     <h3 class="card__profession">' . $vp_owner . '</h3>
                  </div>
               </div>
            </div>
         </article>';

            // $array_okrs[$contOkrs]["foto"] = '<a class="profile-thumb" href="javascript:Profile(' . $row->id_owner . ',1)"><img loading="lazy" src="../../recursos/' . $foto_owner . '" class="profile-thumb" title="' . $row->nombre_owner . '" style="width:70px;height:70px;"></a>';
            $promedio = GoForAgileOkrs::ResultadosOKR($row->id_okrs);
            $porcentaje_avance = round($promedio["promedio"]);
            if (is_nan($porcentaje_avance)) {
                $porcentaje_avance = 0;
            }
            $array_okrs[$contOkrs]['porcentaje'] = $porcentaje_avance;
            $EscalaColor = GoForAgileOkrs::EscalaColor($porcentaje_avance, Session::get('id_empresa'));
            $array_okrs[$contOkrs]['color_bg'] = $EscalaColor["color_bg"];
            GoForAgileOkrs::OrderResultados($row->id_okrs);
            $resultadosVisual = GoForAgileOkrs::ResultadosOKRFiltro($row->id_okrs, $filtro);
            $array_resultados = array();
            foreach ($resultadosVisual as $resultado) {
                $array_resultados[$contKR]["id"] = $resultado->id;
                $array_resultados[$contKR]["descripcion"] = $resultado->descripcion;
                $array_resultados[$contKR]["periodo"] = $resultado->periodo;
                $array_resultados[$contKR]["meta"] = $resultado->meta;
                $array_resultados[$contKR]["avance_num"] = $resultado->avance;
                $porcentaje_kr = 0;
                if($resultado->meta == 0 || $resultado->meta == ''){
                    $porcentaje_kr = 0;
                }else if($resultado->avance == 0 || $resultado->avance == ''){
                    $porcentaje_kr = 0;
                }else{
                    if(($resultado->avance > 0 && $resultado->meta > 0) && (intval($resultado->avance) && intval($resultado->meta))){
                        $porcentaje_kr = (round($resultado->avance) * 100) / round($resultado->meta);
                        if ($resultado->tendencia == 2) {
                            $porcentaje_kr = ((round($resultado->meta) / round($resultado->avance)) * 100);
                        }
                    }else{
                        $porcentaje_kr = 0;
                    }
                    
                }                

                if (is_infinite($porcentaje_kr)) {
                    $porcentaje_kr = 0;
                }

                if (is_nan($porcentaje_kr)) {
                    $porcentaje_kr = 0;
                }

                if ($porcentaje_kr > 100) {
                    $porcentajeKr = 100;
                } else {
                    $porcentajeKr = $porcentaje_kr;
                }

                $color_text = $color_text_inic = "black";

                $escala_kr = GoForAgileOkrs::EscalaColor(round($porcentaje_kr), Session::get('id_empresa'));
                $back_color_kr = "background-color:" . $escala_kr['color_bg'] . " !important";
                $txt_rango_meta = $escala_kr['txt_subtitulo'];

                $txt_meta = $resultado->meta;
                if ($resultado->medicion == 1) {
                    $txt_meta = $resultado->meta;
                }
                if ($resultado->medicion == 2) {
                    $txt_meta = $resultado->meta . " Hrs";
                }
                if ($resultado->medicion == 3) {
                    $txt_meta = "$ " . $resultado->meta;
                }
                if ($resultado->medicion == 4) {
                    $txt_meta = $resultado->meta . "%";
                }
                if ($resultado->medicion == 5) {
                    $txt_meta = $resultado->meta . " Docs";
                }
                if ($resultado->medicion == 6) {
                    $txt_meta = $resultado->meta . " Hitos";
                }

                $porcentajeBar = '<div class="progress sm no-margin" title="' . $txt_rango_meta . '">
									<div class="progress-bar" role="progressbar" aria-valuenow="' . $porcentajeKr . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $porcentajeKr . '%; color: ' . $color_text . '; ' . $back_color_kr . '">
										<span class="sr-only">' . round($porcentaje_kr) . '% Completado (success)</span>
									</div>
								</div>';

                $array_resultados[$contKR]["txt_meta"] = $txt_meta;
                $array_resultados[$contKR]["porcentajeBarra"] = $porcentajeBar;
                $array_resultados[$contKR]["porcentaje"] = round($porcentaje_kr);

                $array_lista_kr = explode(",", $resultado->responsables);

                $lista_resp_kr = "";
                foreach ($array_lista_kr as $id_resp) {
                    // dd($array_lista_kr);
                    if ($id_resp != '') {

                        if ($id_resp == Session::get('id_user')) {
                            $validar_edit = true;
                        }

                        $dataEmple = GoForAgileAdmin::CardProfile($id_resp);
                        // dd($dataEmple);
                        foreach ($dataEmple as $valueEmp) {
                            if (!$valueEmp["foto"]) {
                                $valueEmp["foto"] = "img_default.jpg";
                            }
                            $lista_resp_kr .= '<article class="card__article_responsables">
            <div class="card__profile_responsables">
               <img loading="lazy" src="../../recursos/' . $valueEmp["foto"] . '" class="profile-thumb" title="' . $valueEmp["nombre"] . '" style="width:35px;height:35px;border-radius: 50%;">
            </div>
            <div class="card__tooltip_responsables">
               <div class="card__content_responsables">      
                  <div class="card__data_responsables">
                     <div class="card__image_responsables">
                        <div class="card__mask_responsables">
                           <img src="../../recursos/' . $valueEmp["foto"] . '" alt="image" class="card__img_responsables">
                        </div>                        
                     </div>
                     <h2 class="card__name_responsables">' . $valueEmp["nombre"] . '</h2><br>
                     <h3 class="card__profession_responsables">' . $valueEmp["cargo"] . '</h3><br>
                     <h3 class="card__profession_responsables">' . $valueEmp["area"] . '</h3><br>
                     <h3 class="card__profession_responsables">' . $valueEmp["vicepresidencia"] . '</h3>
                  </div>
               </div>
            </div>
         </article>';
                            // $lista_resp_kr .= '<a href="javascript:Profile(' . $id_resp . ',1)"  id="profileOkr"><img loading="lazy" src="../../recursos/' . $valueEmp->foto . '" class="foto_min" title="' . $valueEmp->nombre . '" style="width: 35px !important;height: 35px !important;"></a>';
                        }
                    }
                }

                $array_resultados[$contKR]["listaResponsables"] = $lista_resp_kr;
                $array_resultados[$contKR]['fecha_inicia'] = GoForAgileAdmin::FechaAmigable($resultado->fecha_inicia);
                $array_resultados[$contKR]['fecha_entrega'] = GoForAgileAdmin::FechaAmigable($resultado->fecha_entrega);
                $array_resultados[$contKR]['avance'] = '<input type="number" name="" class="form-control form-control-sm mb-0" value="' . $resultado->avance . '" onChange="Guardar_Avance_Resultado(this.value, ' . $resultado->id . ',' . $row->id_okrs . ')" >';

                $fecha_inicia = strtotime($resultado->fecha_inicia);
                $fecha_entrega = strtotime($resultado->fecha_entrega);
                $fecha_hoy = time();
                
                $faltantes1 = floor(($fecha_entrega - $fecha_inicia) / (60 * 60 * 24));
                $faltantes2 = (floor(($fecha_entrega - $fecha_hoy) / (60 * 60 * 24))+1);
                $porcentaje_faltante = ($faltantes2 / $faltantes1)*100;
                // dd($porcentaje_faltante);
                // echo $porcentaje_faltante."<br>";
                if (is_nan($porcentaje_faltante)) {
                    $porcentaje_faltante = 0;
                }
                if ($porcentaje_faltante <= 0) {
                    $porcentaje_faltante = 0;
                }

                if ($porcentaje_faltante > 100) {
                    $porcentaje_faltante = 100;
                }

                if ($faltantes2 <= 0) {
                    $faltantes2 = 0;
                }

                $escala_faltante = GoForAgileOkrs::EscalaColor(round($porcentaje_faltante), Session::get('id_empresa'));
                $back_color_faltante = "background-color:" . $escala_faltante['color_bg'] . " !important";                

                $porcentajeDias = '<div class="progress sm no-margin">
									<div class="progress-bar" role="progressbar" aria-valuenow="' . $porcentaje_faltante . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $porcentaje_faltante . '%; color: ' . $color_text . '; ' . $back_color_faltante . '">										
									</div>
								</div>';

                $array_resultados[$contKR]['porcentaje_dias'] = $porcentajeDias;
                $array_resultados[$contKR]['dias_faltantes'] = $faltantes2;

                $contKR++;
            }
            $array_okrs[$contOkrs]['kr'] = $array_resultados;
            $contOkrs++;
        }
        // dd($array_okrs);

        $currentElements = array_slice($prueba, $offset, $porPagina);

        $paginacion = new LengthAwarePaginator($currentElements, count($prueba), 10, $paginaActual, [
            'path' => $request->url(),
            'pageName' => 'pagina'
        ]);

        return view('okrsEquipos.okrsOrganizacion', [
            'PorcentajeFinal' => $porcentaje_final, 'PorcentajeBarra' => $porcentaje_barra, 'PorcentajeFinalBarra' => $porcentajeFinalBarra, 'ColorPorcentaje' => $backgroundColor,
            'Okrs' => $array_okrs, 'paginacion' => $paginacion, 'Nombre' => $nombre, 'Foto' => $foto, 'Cargo' => $cargo, 'Area' => $area, 'VP' => $vp
        ]);
    }
}

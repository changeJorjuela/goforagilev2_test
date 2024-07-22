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
        $congelar_okrs = true;
        if (Session::get('anio_fill') == Session::get('anio_curso')) {
            $congelar_okrs = false;
        }
        $resultadoOkr = $request->resultado_okr_;
        $iniciativaKr = $request->iniciativa;
        // dd($resultadoOkr);
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
        $EscalaColor = GoForAgileAdmin::EscalaColor($porcentaje_final, Session::get('id_empresa'));
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

            $array_okrs[$contOkrs]["foto"] =  '<div class="card__profile">
               <a href="javascript:Profile(' . $row->id_owner . ',1)"><img loading="lazy" src="../../recursos/' . $foto_owner . '" class="profile-thumb" title="' . $row->nombre_owner . '" style="width:70px;height:70px;" ></A>
            </div>';

            // $array_okrs[$contOkrs]["foto"] = '<a class="profile-thumb" href="javascript:Profile(' . $row->id_owner . ',1)"><img loading="lazy" src="../../recursos/' . $foto_owner . '" class="profile-thumb" title="' . $row->nombre_owner . '" style="width:70px;height:70px;"></a>';
            $promedio = GoForAgileOkrs::ResultadosOKR($row->id_okrs);
            $porcentaje_avance = round($promedio["promedio"]);
            if (is_nan($porcentaje_avance)) {
                $porcentaje_avance = 0;
            }
            $array_okrs[$contOkrs]['porcentaje'] = $porcentaje_avance;
            $EscalaColor = GoForAgileAdmin::EscalaColor($porcentaje_avance, Session::get('id_empresa'));
            $array_okrs[$contOkrs]['color_bg'] = $EscalaColor["color_bg"];
            GoForAgileOkrs::OrderResultados($row->id_okrs);

            $resultados = OkrsController::Resultados($row->id_okrs, $filtro, $congelar_okrs, $row->tipo_role, $array_okrs[$contOkrs]['anio'], 1, $paginaActual);

            $array_okrs[$contOkrs]['tipo_role'] = $row->tipo_role;

            $array_okrs[$contOkrs]['kr'] = $resultados;
            $contOkrs++;
        }
        // dd($array_okrs);

        $currentElements = array_slice($prueba, $offset, $porPagina);

        $paginacion = new LengthAwarePaginator($currentElements, count($prueba), 10, $paginaActual, [
            'path' => $request->url(),
            'pageName' => 'pagina'
        ]);

        $anioOkrFiltro = GoForAgileAdmin::AnioOkr();
        $vicepresidenciasFiltro = GoForAgileAdmin::SelectVicepresidencia(Session::get('id_empresa'));
        $objEstrategicosFiltro = GoForAgileOkrs::SelectObjEstrategico(Session::get('id_empresa'),Session::get('anio_fill'));
        $areasFiltro = GoForAgileAdmin::SelectArea(Session::get('id_empresa'));
        $responsablesFiltro = GoForAgileOkrs::SelectResponsables(Session::get('id_user'),Session::get('id_empresa'));
        $tipoOkrFiltro = GoForAgileOkrs::SelectTipoOkr();
        $okrsFiltro = GoForAgileOkrs::SelectOkrs(Session::get('id_empresa'),Session::get('anio_fill'));
        // Session::put('Q1', 'on');
        
        return view('okrsEquipos.okrsOrganizacion', [
            'PorcentajeFinal' => $porcentaje_final, 'PorcentajeBarra' => $porcentaje_barra, 'PorcentajeFinalBarra' => $porcentajeFinalBarra, 'ColorPorcentaje' => $backgroundColor,
            'Okrs' => $array_okrs, 'paginacion' => $paginacion, 'Nombre' => $nombre, 'Foto' => $foto, 'Cargo' => $cargo, 'Area' => $area, 'VP' => $vp, 'ResultadoOKR' => $resultadoOkr,
            'IniciativaKR' => $iniciativaKr, 'AnioOkrFiltro' => $anioOkrFiltro, 'VicepresidenciasFiltro' => $vicepresidenciasFiltro, 'ObjEstrategicoFiltro' => $objEstrategicosFiltro,
            'AreasFiltro' => $areasFiltro, 'ResponsablesFiltro' => $responsablesFiltro, 'TipoOkrFiltro' => $tipoOkrFiltro, 'OkrsFiltro' => $okrsFiltro
        ]);
    }

    public static function Resultados($idOkr, $filtro, $congelar_okrs, $okrTipoRole, $okrAnio, $pagina, $numeroP)
    {
        $resultadosVisual = GoForAgileOkrs::ResultadosOKRFiltro($idOkr, $filtro);

        $array_resultados = array();
        $contKR = 0;
        foreach ($resultadosVisual as $resultado) {
            $array_resultados[$contKR]["id"] = $resultado->id;
            $array_resultados[$contKR]["descripcion"] = $resultado->descripcion;
            $array_resultados[$contKR]["periodo"] = $resultado->periodo;
            $array_resultados[$contKR]["meta"] = $resultado->meta;
            $array_resultados[$contKR]["avance_num"] = $resultado->avance;
            $array_resultados[$contKR]['id_empleado'] = $resultado->id_empleado;
            $array_resultados[$contKR]['id_okrs'] = $resultado->id_okrs;
            $array_resultados[$contKR]['orden'] = $resultado->orden;
            $porcentaje_kr = 0;
            if ($resultado->meta == 0 || $resultado->meta == '') {
                $porcentaje_kr = 0;
            } else if ($resultado->avance == 0 || $resultado->avance == '') {
                $porcentaje_kr = 0;
            } else {
                if (($resultado->avance > 0 && $resultado->meta > 0) && (intval($resultado->avance) && intval($resultado->meta))) {
                    $porcentaje_kr = (round($resultado->avance) * 100) / round($resultado->meta);
                    if ($resultado->tendencia == 2) {
                        $porcentaje_kr = ((round($resultado->meta) / round($resultado->avance)) * 100);
                    }
                } else {
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

            $escala_kr = GoForAgileAdmin::EscalaColor(round($porcentaje_kr), Session::get('id_empresa'));
            $back_color_kr = "background-color:" . $escala_kr['color_bg'] . " !important";
            $txt_rango_meta = $escala_kr['txt_subtitulo'];

            $txt_meta = $resultado->meta;
            if ($resultado->medicion == 1) {
                $txt_meta = " ".$resultado->meta;
            }
            if ($resultado->medicion == 2) {
                $txt_meta = " ".$resultado->meta . " Hrs";
            }
            if ($resultado->medicion == 3) {
                $txt_meta = " $" . $resultado->meta;
            }
            if ($resultado->medicion == 4) {
                $txt_meta = " ".$resultado->meta . " %";
            }
            if ($resultado->medicion == 5) {
                $txt_meta = " ".$resultado->meta . " Docs";
            }
            if ($resultado->medicion == 6) {
                $txt_meta = " ".$resultado->meta . " Hitos";
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

            $listaResponsables = OkrsController::ListaResponsables($array_lista_kr);

            $array_resultados[$contKR]["listaResponsables"] = $listaResponsables;
            $array_resultados[$contKR]['fecha_inicia'] = GoForAgileAdmin::FechaAmigable($resultado->fecha_inicia);
            $array_resultados[$contKR]['fecha_entrega'] = GoForAgileAdmin::FechaAmigable($resultado->fecha_entrega);
            
            $editar = $txt_meta;
            if ($congelar_okrs == false) {
                if ($okrTipoRole == 1 || $okrTipoRole == 2) {
                    switch ($pagina) {
                        case 1:
                            $editar = '<input type="number" name="" class="form-control form-control-sm mb-0" value="' . $resultado->avance . '" onChange="Guardar_Avance_Resultado(this.value,' . $resultado->id . ',' . $idOkr . ',' . Session::get('id_empresa') . ',' . Session::get('id_user') . ',\'okrsOrganizacion?pagina=' . $numeroP . '\')">';
                            break;
                    }
                }
            }

            $array_resultados[$contKR]['avance'] = $editar;

            $fecha_inicia = strtotime($resultado->fecha_inicia);
            $fecha_entrega = strtotime($resultado->fecha_entrega);
            $fecha_hoy = time();

            $faltantes1 = floor(($fecha_entrega - $fecha_inicia) / (60 * 60 * 24));
            $faltantes2 = (floor(($fecha_entrega - $fecha_hoy) / (60 * 60 * 24)) + 1);
            if ($faltantes1 == 0 || $faltantes2 == 0) {
                $porcentaje_faltante = 0;
            } else {
                $porcentaje_faltante = ($faltantes2 / $faltantes1) * 100;
            }
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

            $escala_faltante = GoForAgileAdmin::EscalaColor(round($porcentaje_faltante), Session::get('id_empresa'));
            $back_color_faltante = "background-color:" . $escala_faltante['color_bg'] . " !important";

            $porcentajeDias = '<div class="progress sm no-margin">
                                <div class="progress-bar" role="progressbar" aria-valuenow="' . $porcentaje_faltante . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $porcentaje_faltante . '%; color: ' . $color_text . '; ' . $back_color_faltante . '">										
                                </div>
                            </div>';

            $array_resultados[$contKR]['porcentaje_dias'] = $porcentajeDias;
            $array_resultados[$contKR]['dias_faltantes'] = $faltantes2;
            $accion_agregar_ini = $accion_comentario = $accion_editar_res = $adicional = '';
            if (($okrTipoRole  == 1 || $okrTipoRole == 2) && $congelar_okrs == false) {
                $accion_agregar_ini = '<a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:VerIniciativa(' . $array_resultados[$contKR]['id'] . ', 0)" class="dropdown-item"><span class="fas fa-list-ul"></span>   &nbsp;&nbsp;&nbsp;Agregar Iniciativa</a>';
            }
            if ($okrTipoRole == 1 && $congelar_okrs == false) {
                $accion_editar_res = '<a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:Editar_Resultado(' . $array_resultados[$contKR]['id'] . ')" class="dropdown-item"><span class="fas fa-edit"></span>  &nbsp;&nbsp;&nbsp;Editar Resultado Clave</a>';
            }
            $queryIni = GoForAgileOkrs::ComentariosKR($array_resultados[$contKR]['id']);
            $accion_comentario = '<a id="aDropDownItem" data-bs-toggle="tooltip" style="color: black !important;" href="javascript:Ver_Comentarios(' . $array_resultados[$contKR]['id'] . ',' . Session::get('id_user') . ')" class="dropdown-item"><span class="fas fa-comments"></span>  &nbsp;&nbsp;&nbsp;Comentarios ' . count($queryIni) . '</a>';
            $adicional .= '<li><a id="aDropDownItem" href="#" data-bs-toggle="tooltip" onclick="MoverResultado(' . $array_resultados[$contKR]['id'] . ',' . Session::get('id_empresa') . ',' . $okrAnio . ',0,' . $array_resultados[$contKR]['id_empleado'] . ',0)" class="dropdown-item"><span class="fas fa-expand-arrows-alt"></span> &nbsp;&nbsp;&nbsp;Mover a otro OKR</a></li>
                                                    <li><a id="aDropDownItem" href="#" data-bs-toggle="tooltip" onclick="CopiarResultado(' . $array_resultados[$contKR]['id'] . ',' . Session::get('id_empresa') . ',' . $okrAnio . ')" class="dropdown-item"><span class="fas fa-copy"></span> &nbsp;&nbsp;&nbsp;Duplicar</a></li>';

            $accion_subir = '<a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:PosicionResultado(' . $array_resultados[$contKR]['id'] . ',1,' . $array_resultados[$contKR]['id_okrs'] . ')" class="dropdown-item"><span class="fas fa-sort-up"></span> &nbsp;&nbsp;Subir Resultado Clave</a>';
            $accion_bajar = '<a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:PosicionResultado(' . $array_resultados[$contKR]['id'] . ',2,' . $array_resultados[$contKR]['id_okrs'] . ')" class="dropdown-item"><span class="fas fa-sort-down"></span> &nbsp;&nbsp;Bajar Resultado Clave</a>';
            $accion_reubicar = '<a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:ReubicarResultado(' . $array_resultados[$contKR]['id'] . ',' . $array_resultados[$contKR]['orden'] . ',' . $array_resultados[$contKR]['id_okrs'] . ')" class="dropdown-item"><span class="fas fa-sort"></span> &nbsp;&nbsp;Reubicar Resultado Clave</a>';
            if ($congelar_okrs == false) {
                $array_resultados[$contKR]['acciones'] = '<div class="dropdown dropdwon-hover">
                                                                    <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="boton_accion">
                                                                    . . .
                                                                    </button>
                                                                    <ul class="dropdown-menu" id="menu_acciones">
                                                                      <li>' . $accion_agregar_ini . '</li>
                                                                      <li>' . $accion_editar_res . '</li>
                                                                      <li>' . $accion_comentario . '</li>
                                                                      ' . $adicional . '
                                                                      
                                                                      <li>' . $accion_reubicar . '</li>										
                                                                      </ul>
                                                                  </div>';
            } else {
                $array_resultados[$contKR]['acciones'] = "";
            }
            $iniciativas = OkrsController::Iniciativas($resultado->id, $resultado->tendencia, $congelar_okrs, $okrTipoRole, $idOkr, $pagina, $numeroP);
            $array_resultados[$contKR]['iniciativas'] = $iniciativas;
            $contKR++;
        }
        return $array_resultados;
    }

    public static function Iniciativas($idResultado, $tendencia, $congelar_okrs, $tipoRole, $idOkr, $pagina, $numeroP)
    {
        $array_iniciativas = array();
        $contIni = 0;
        $Iniciativas = GoForAgileOkrs::IniciativasKR($idResultado);
        foreach ($Iniciativas as $iniciativa) {
            $array_iniciativas[$contIni]["id"] = $iniciativa->id;
            $array_iniciativas[$contIni]["descripcion"] = $iniciativa->descripcion;
            $array_iniciativas[$contIni]["meta"] = $iniciativa->meta;

            if (!isset($iniciativa->tendencia)) {
                GoForAgileOkrs::ActualizaTendenciaIniciativa($tendencia, $iniciativa->id);
            }

            $queryPlanes = GoForAgileOkrs::PlanesIniciativa($iniciativa->id);

            $queryPlanesRealizado = GoForAgileOkrs::PlanesIniciativaRealizado($iniciativa->id);

            $queryDocumentos = GoForAgileOkrs::DocumentosIniciativa($iniciativa->id);

            $queryComentario = GoForAgileOkrs::ComentariosIniciativa($iniciativa->id);

            $accion_comentario = '<a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:Ver_Comentarios_Iniciativa(' . $iniciativa->id . ',' . Session::get('id_user') . ')" class="dropdown-item"><span class="fas fa-comments"></span>  &nbsp;&nbsp;&nbsp;Comentarios ' . count($queryComentario) . '</a>';

            $select = $iniciativa->avance . "%";
            if ($congelar_okrs == false) {
                if ($tipoRole == 1 || $tipoRole == 2) {
                    switch ($pagina) {
                        case 1:
                            $select = '<input type="number" name="" class="form-control form-control-sm mb-0" value="' . $iniciativa->avance . '" onChange="Guardar_Avance_Iniciativa(this.value,' . $iniciativa->id . ',' . $idOkr . ',' . $idResultado .','.Session::get('id_empresa') . ',' . Session::get('id_user') . ',\'okrsOrganizacion?pagina=' . $numeroP . '\')">';
                            break;
                    }
                }
            }

            $bt_editar  = $accion_ver_planes = $accion_documentos = $accion_editar_iniciativa = '';
            if (($tipoRole == 1 || $tipoRole == 2) && $congelar_okrs == false) {
                $accion_editar_iniciativa = '<li><a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:VerIniciativa(' . $idResultado . ', ' . $iniciativa->id . ', ' . $tipoRole . ' )" class="dropdown-item"><span class="fas fa-spell-check"></span>   &nbsp;&nbsp;&nbsp;Editar Iniciativa</a></li>';
            }

            $accion_ver_planes = '<a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:Ver_Planes_accion( ' . $iniciativa->id . ', ' . $tipoRole . ' )" class="dropdown-item" id="iniciativa_' . $iniciativa->id . '"><span class="fas fa-tasks"></span>   &nbsp;&nbsp;&nbsp;Planes de Acción ' . count($queryPlanes) . ' / ' . count($queryPlanesRealizado) . '</a>';

            $accion_documentos = '<a id="aDropDownItem" data-bs-toggle="tooltip" href="javascript:Ver_Documentos( ' . $iniciativa->id . ', ' . $tipoRole . ' )" class="dropdown-item" id="documento_' . $iniciativa->id . '"><span class="fas fa-file-alt"></span>   &nbsp;&nbsp;&nbsp;Documentos ' . count($queryDocumentos) . '</a>';

            if ($iniciativa->meta == 0 || $iniciativa->meta == '') {
                $porciento = 0;
            } else if ($iniciativa->avance == 0 || $iniciativa->avance == '') {
                $porciento = 0;
            } else {
                if (($iniciativa->avance > 0 && $iniciativa->meta > 0) && (intval($iniciativa->avance) && intval($iniciativa->meta))) {
                    $porciento = (round($iniciativa->avance) * 100) / round($iniciativa->meta);
                    if ($iniciativa->tendencia == 2) {
                        $porciento = (($iniciativa->meta / $iniciativa->avance) * 100);
                    }
                } else {
                    $porciento = 0;
                }
            }

            $porciento = round($porciento);

            if (is_nan($porciento) || is_infinite($porciento)) {
                $porciento = 0;
            }

            if ($porciento > 100) {
                $porcentaje_barra = 100;
            } else {
                $porcentaje_barra = $porciento;
            }

            $escala_ini = GoForAgileAdmin::EscalaColor($porciento, Session::get('id_empresa'));
            $back_color_ini = "background-color:" . $escala_ini['color_bg'] . " !important";
            $txt_rango_meta_inic = $escala_ini['txt_subtitulo'];

            $porcentajeBar = '<div class="progress sm no-margin" title="' . $txt_rango_meta_inic . '">
                                <div class="progress-bar" role="progressbar" aria-valuenow="' . $porciento . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $porcentaje_barra . '%; color: ' . $escala_ini['color_text'] . '; ' . $back_color_ini . '">
                                    <span class="sr-only">' . round($porciento) . '% Completado (success)</span>
                                </div>
                            </div>';

            $array_iniciativas[$contIni]["porcentajeBarra"] = $porcentajeBar;
            $array_iniciativas[$contIni]["porcentaje"] = round($porciento);


            $array_lista = explode(",", $iniciativa->responsables);
            $listaResponsables = OkrsController::ListaResponsables($array_lista);

            if (Session::get('role_plataforma') == 1) {
                $validar_edit = true;
            }
            if ($tipoRole == 1) {
                $validar_edit = true;
            }

            if ($validar_edit == false) {
                if ($iniciativa->avance) {
                    $select = " ".$iniciativa->avance . "%";
                } else {
                    $select = " 0%";
                }
            }

            if ($congelar_okrs == false) {

                $acciones_ini = '
                <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown" id="boton_accion">. . .</button>
                        <div class="dropdown-menu dropdown-menu-end">
                            ' . $accion_comentario . '
                            ' . $accion_ver_planes . '
                            ' . $accion_documentos . '
                            ' . $accion_editar_iniciativa . '
                        </div>';
            } else {
                $acciones_ini = '';
            }

            $array_iniciativas[$contIni]["avance"] = $select;
            $array_iniciativas[$contIni]["entrega"] = GoForAgileAdmin::FechaAmigable($iniciativa->fecha_entrega);
            $array_iniciativas[$contIni]["responsables"] = $listaResponsables;
            $array_iniciativas[$contIni]["acciones"] = $acciones_ini;
            $contIni++;
        }
        return $array_iniciativas;
    }

    public static function ListaResponsables($array_list)
    {
        $lista_resp_kr = '';
        foreach ($array_list as $id_resp) {
            if ($id_resp != '') {

                if ($id_resp == Session::get('id_user')) {
                    $validar_edit = true;
                }

                $dataEmple = GoForAgileAdmin::CardProfile($id_resp);
                foreach ($dataEmple as $valueEmp) {
                    if (!$valueEmp["foto"]) {
                        $valueEmp["foto"] = "img_default.jpg";
                    }
                    $lista_resp_kr .= '<a href="javascript:Profile(' . $id_resp . ',1)" id="profileOkr"><img loading="lazy" src="../../recursos/' . $valueEmp["foto"] . '" class="foto_min" title="' . $valueEmp["nombre"] . '" style="width: 35px !important;height: 35px !important;"></a>';
                }
            }
        }


        return $lista_resp_kr;
    }
}

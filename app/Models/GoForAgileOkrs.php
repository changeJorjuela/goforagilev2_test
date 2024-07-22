<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoForAgileOkrs extends Model
{
    protected $connection = 'mysql-goforagile_okrs';

    //OKRS
    public static function OkrsOrganizacion($id_empresa, $porPagina, $offset)
    {
        $adicional = "";
        if ($porPagina) {
            $adicional .= " LIMIT $porPagina";
        }
        if ($offset) {
            $adicional .= " OFFSET $offset";
        }
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $OkrsOrganizacion = DB::Select("
		SELECT Okrs_Equipos.id AS id, Okrs_Equipos.id_empresa AS id_empresa , 
		Okrs_Equipos.id_empleado AS id_empleado , Okrs_Equipos.id_okrs AS id_okrs , 
		Okrs.objetivo_okr AS objetivo_okr , Okrs.fecha_inicia AS fecha_inicia, 
		Okrs.fecha_termina AS fecha_termina , Okrs.tipo AS tipo, Okrs.periodo AS periodo, 
		Okrs.objetivos_estrategicos AS objetivos_estrategicos, Okrs.anio AS anio, Okrs_Equipos.tipo AS tipo_role,  
		Empleados.nombre AS nombre_empleado, Okrs.id_empleado AS id_owner, EO.nombre AS nombre_owner
		FROM Okrs_Equipos
		LEFT JOIN Okrs ON Okrs.id = Okrs_Equipos.id_okrs 
		LEFT JOIN Okrs_Areas ON Okrs_Areas.id_okrs = Okrs_Equipos.id_okrs
		LEFT JOIN Okrs_Resultados ON Okrs_Resultados.id_okrs = Okrs_Equipos.id_okrs
		LEFT JOIN goforagile_admin.Empleados AS Empleados ON Empleados.id = Okrs_Equipos.id_empleado
		LEFT JOIN goforagile_admin.Empleados AS EO ON EO.id = Okrs.id_empleado		
		WHERE Okrs_Equipos.id_empresa = $id_empresa
        AND Okrs.anio = 2024
        GROUP BY Okrs.id
		ORDER BY Okrs.objetivo_okr ASC
        $adicional       
		");
        return $OkrsOrganizacion;
    }
    public static function Okrs($id)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $Okrs = DB::Select("SELECT * FROM Okrs WHERE id = $id");
        return $Okrs;
    }

    public static function SelectOkrs($id, $anio){
        $SelectOkrs = array();
        $SelectOkrs[''] = 'Seleccione Okr:';
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $selectOkrs = DB::Select("SELECT * FROM Okrs WHERE id_empresa = $id AND anio = $anio AND estado = 1 ORDER BY objetivo_okr");
        foreach($selectOkrs as $okr){
            $SelectOkrs[$okr->id] = $okr->objetivo_okr;
        }

        return $SelectOkrs;
    }


    //OBJETIVOS ESTRATEGICOS
    public static function SelectObjEstrategico($id, $anio){
        $objEstrategicos = array();
        $objEstrategicos[''] = 'Seleccione Objetivo Estratégico:';
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $selectOES = DB::Select("SELECT * FROM Objetivos_Estrategicos WHERE id_empresa = $id AND anio = $anio AND estado = 1  ORDER BY objetivo");
        foreach($selectOES as $oe){
            $objEstrategicos[$oe->id] = $oe->objetivo;
        }

        return $objEstrategicos;
    }


    //RESULTADOS
    public static function ResultadosOKR($id_okr)
    {
        $suma_resultado = 0;
        $conteo_resultado = 0;
        $resultado_prom_okr = 0;
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $ResultadosOKR = DB::Select("SELECT * FROM Okrs_Resultados WHERE id_okrs = $id_okr");
        // dd($ResultadosOKR);
        foreach ($ResultadosOKR as $resultado) {
            $avance = (int)$resultado->avance;
            $meta = (int)$resultado->meta;
            if ($meta > 0) {
                $porcentaje = ($avance * 100) / $meta;
            } else {
                $porcentaje = 0;
            }
            $suma_resultado += $porcentaje;
            $conteo_resultado++;
        }
        if ($suma_resultado > 0) {
            $resultado_prom_okr = ($suma_resultado / $conteo_resultado);
        } else {
            $resultado_prom_okr += 0;
        }

        $nodo = array(
            "promedio" => $resultado_prom_okr,
            "no_resultados" => count($ResultadosOKR)
        );

        return $nodo;
    }

    public static function OrderResultados($id_okr)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $ResultadosOKR = DB::Select("SELECT * FROM Okrs_Resultados WHERE id_okrs = $id_okr AND orden IS NULL");
        $array_resultados = array();
        $contRes = 0;
        foreach ($ResultadosOKR as $row) {
            $array_resultados[$contRes]['id'] = $row->id;
            $array_resultados[$contRes]['orden'] = $row->orden;
            $contRes++;
            for ($i = 0; $i < count($array_resultados); $i++) {
                $j = $i + 1;
                DB::Update("UPDATE Okrs_Resultados SET orden = $j WHERE id = '" . $array_resultados[$i]['id'] . "' ");
            }
        }
    }

    public static function ResultadosOKRFiltro($id_okr, $filtro)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $ResultadosOKR = DB::Select("SELECT * FROM Okrs_Resultados WHERE id_okrs = $id_okr $filtro ORDER BY orden ASC");
        return $ResultadosOKR;
    }

    public static function ComentariosKR($idResultado)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $ComentariosKR = DB::Select("SELECT * FROM Okrs_Comentarios WHERE id_resultado = $idResultado");
        return $ComentariosKR;
    }

    public static function GuardarAvanceKr($idKr, $avance, $idOkr, $idEmpresa, $idUser){
        $hoy = date("Y-m-d H:i:s");
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $resultado = DB::Select("SELECT * FROM Okrs_Resultados WHERE id = $idKr");
        foreach($resultado as $kr){
            $descripcionKr = $kr->descripcion;
            $avanceKr = $kr->avance;
        }

        $Okrs = DB::Select("SELECT * FROM Okrs WHERE id = $idOkr");
        foreach($Okrs as $okr){
            $tipoOkr = $okr->tipo;
        }

        $descripcion = "Actualización de avance de KR $descripcionKr del $avanceKr al $avance";
        DB::Insert("INSERT INTO Auditoria_Okrs (id_empresa,id_empleado,accion,descripcion,tipo_okr,id_okr,id_kr,id_iniciativa,created_at)
        VALUES ($idEmpresa, $idUser, 'ACTUALIZAR', '$descripcion', $tipoOkr, $idOkr, $idKr, 0, '$hoy')");

        $GuardarAvanceKr = DB::Update("UPDATE Okrs_Resultados SET avance = '$avance' WHERE id = $idKr");

        return $GuardarAvanceKr;
        
    }  

    //INICIATIVAS
    public static function IniciativasKR($id)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $ResultadosOKR = DB::Select("SELECT * FROM Okrs_Iniciativas WHERE id_resultado = $id");
        return $ResultadosOKR;
    }

    public static function PlanesIniciativa($id)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $PlanesIniciativa = DB::Select("SELECT * FROM Okrs_Actividades WHERE id_iniciativa = $id ");
        return $PlanesIniciativa;
    }

    public static function PlanesIniciativaRealizado($id)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $PlanesIniciativaRealizado = DB::Select("SELECT * FROM Okrs_Actividades WHERE id_iniciativa = $id AND checked = 'true'");
        return $PlanesIniciativaRealizado;
    }

    public static function DocumentosIniciativa($id)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $DocumentosIniciativa = DB::Select("SELECT * FROM Okrs_Documentos WHERE id_iniciativa = $id");
        return $DocumentosIniciativa;
    }

    public static function ComentariosIniciativa($id)
    {
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $ComentariosIniciativa = DB::Select("SELECT * FROM Okrs_Comentarios_Iniciativas WHERE id_iniciativa = $id");
        return $ComentariosIniciativa;
    }

    public static function ActualizaTendenciaIniciativa($tendencia,$id){
        DB::setDefaultConnection("mysql-goforagile_okrs");
        DB::Update("UPDATE Okrs_Iniciativas SET tendencia = $tendencia, updated_at = NOW() WHERE id = $id");
    }

    public static function GuardarAvanceIniciativa($id, $idKr, $avance, $idOkr, $idEmpresa, $idUser){
        $hoy = date("Y-m-d H:i:s");
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $iniciativas = DB::Select("SELECT * FROM Okrs_Iniciativas WHERE id = $id");
        foreach($iniciativas as $iniciativa){
            $descripcionIni = $iniciativa->descripcion;
            $avanceIni = $iniciativa->avance;
        }

        $Okrs = DB::Select("SELECT * FROM Okrs WHERE id = $idOkr");
        foreach($Okrs as $okr){
            $tipoOkr = $okr->tipo;
        }

        $descripcion = "Actualización de avance de iniciativa $descripcionIni del $avanceIni al $avance";
        DB::Insert("INSERT INTO Auditoria_Okrs (id_empresa,id_empleado,accion,descripcion,tipo_okr,id_okr,id_kr,id_iniciativa,created_at)
        VALUES ($idEmpresa, $idUser, 'ACTUALIZAR', '$descripcion', $tipoOkr, $idOkr, $idKr, $id, '$hoy')");

        $GuardarAvanceIniciativa = DB::Update("UPDATE Okrs_Iniciativas SET avance = '$avance' WHERE id = $id");

        return $GuardarAvanceIniciativa;
    }


    //EXTRAS
    public static function SelectResponsables($idUser, $idEmpresa){
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $equiposViews = DB::Select("SELECT * FROM Equipos_Views WHERE id_empresa = $idEmpresa AND id_empleado = $idUser");
        $OR_FILL = "";
        if($equiposViews){
            foreach($equiposViews as $EV){
                if ($OR_FILL == "") {
                    $OR_FILL = " OE.id_okrs = $EV->id_okrs";
                } else {
                    $OR_FILL .= " OR OE.id_okrs = $EV->id_okrs";
                }
            }
        }
        $filtroResponsables = array();
        $filtroResponsables[''] = 'Seleccione Responsable:';
        $queryColFiltro = DB::Select("SELECT OE.id_empleado, E.nombre
        FROM Okrs_Equipos OE
	    INNER JOIN goforagile_admin.Empleados E ON E.id = OE.id_empleado
        WHERE OE.id_empresa = $idEmpresa AND (" . $OR_FILL . ") GROUP BY OE.id_empleado ORDER BY E.nombre");

        foreach($queryColFiltro as $conFiltro){
            $empleado = GoForAgileAdmin::EmpleadoId($conFiltro->id_empleado);
            foreach($empleado as $em){
                $filtroResponsables[$em->id] = $em->nombre;
            }
        }

        return $filtroResponsables;

    }

    public static function SelectTipoOkr(){
        $TipoOkr = array();
        $TipoOkr[''] = 'Seleccione Tipo Okr:';
        $TipoOkr[2]  = 'Equipo';
        $TipoOkr[1]  = 'Organizacional';
        return $TipoOkr;
    }
}

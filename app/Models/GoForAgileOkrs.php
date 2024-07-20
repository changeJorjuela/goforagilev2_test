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
    public static function EscalaColor($porcentaje, $id_empresa)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");

        $Escala = DB::Select("SELECT * FROM Escala_Medicion WHERE id_empresa = $id_empresa");
        foreach ($Escala as $value) {
            $Porcentaje1 = $value->porcentaje_uno;
            $Porcentaje2 = $value->porcentaje_dos;
            $Porcentaje3 = $value->porcentaje_tres;
            $Porcentaje4 = $value->porcentaje_cuatro;
            $Porcentaje5 = $value->porcentaje_cinco;
            $Porcentaje6 = $value->porcentaje_seis;
            $Porcentaje7 = $value->porcentaje_siete;
            $Subtitulo1 = $value->subtitulo_uno;
            $Subtitulo2 = $value->subtitulo_dos;
            $Subtitulo3 = $value->subtitulo_tres;
            $Subtitulo4 = $value->subtitulo_cuatro;
            $Subtitulo5 = $value->subtitulo_cinco;
        }

        $color_bg = "#FF0000";
        $color_text = "#000000";
        $escala = array();

        if ($porcentaje >= $Porcentaje1 && $porcentaje < $Porcentaje3) {
            $color_bg = "#FF0000";
            $txt_subtitulo = $Subtitulo1;
            $color_text = "#F7F7F7";
        }
        if ($porcentaje >= $Porcentaje3 && $porcentaje < $Porcentaje5) {
            $color_bg = "#FFF200";
            $txt_subtitulo = $Subtitulo2;
        }
        if ($porcentaje >= $Porcentaje5 && $porcentaje < $Porcentaje7) {
            $color_bg = "#95FA03";
            $txt_subtitulo = $Subtitulo3;
        }
        if ($porcentaje >= $Porcentaje7 && $porcentaje <= 100) {
            $color_bg = "#14F209";
            $txt_subtitulo = $Subtitulo4;
        }
        // if( $porcentaje == 100 ){
        // 	$color_bg = "#0DF205";
        // 	$txt_subtitulo = $dataEscala['subtitulo_cinco'];
        // }
        if ($porcentaje > 100) {
            $color_bg = "#00D30A";
            $txt_subtitulo = $Subtitulo5;
        }

        $escala['color_bg'] = $color_bg;
        $escala['color_text'] = $color_text;
        $escala['txt_subtitulo'] = $txt_subtitulo;

        return $escala;
    }
}

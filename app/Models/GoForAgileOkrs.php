<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoForAgileOkrs extends Model
{
    protected $connection = 'mysql-goforagile_okrs';

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

    public static function OkrsOrganizacion($id_empresa)
    {
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
		WHERE Okrs_Equipos.id_empresa = '" . $id_empresa . "'
        GROUP BY Okrs.id
		ORDER BY Okrs.objetivo_okr ASC        
		");
        return $OkrsOrganizacion;
    }

    public static function ResultadosOKR($id_okr)
    {
        $suma_resultado = 0;
        $conteo_resultado = 0;
        $resultado_prom_okr = 0;
        DB::setDefaultConnection("mysql-goforagile_okrs");
        $ResultadosOKR = DB::Select("SELECT * FROM Okrs_Resultados WHERE id_okrs = $id_okr");

        foreach ($ResultadosOKR as $resultado) {
            $porcentaje = ($resultado->avance * 100) / $resultado->meta;
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
}

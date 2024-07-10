<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoForAgileAdmin extends Model
{
    protected $connection = 'mysql-goforagile_admin';

    // AREAS
    public static function ListarAreas($idEmpresa)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $areas = DB::Select("SELECT * FROM Areas WHERE id_empresa = $idEmpresa ORDER BY nombre ASC");
        return $areas;
    }

    public static function ListarAreasActivo($idEmpresa)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $areas = DB::Select("SELECT * FROM Areas WHERE id_empresa = $idEmpresa AND estado = 1 ORDER BY nombre ASC");
        return $areas;
    }

    public static function BuscarNombreArea($nombreArea, $idEmpresa)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $areas = DB::Select("SELECT * FROM Areas WHERE nombre = '$nombreArea' AND id_empresa = $idEmpresa");
        return $areas;
    }

    public static function BuscarNombreAreaUpd($nombreArea, $id)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $areas = DB::Select("SELECT * FROM Areas WHERE nombre = '$nombreArea' AND id NOT IN ($id)");
        return $areas;
    }

    public static function CrearArea($nombreArea, $padre, $jerarquia, $idEmpresa)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i:s');
        $crearArea = DB::Insert(
            'INSERT INTO Areas (nombre, padre, jerarquia, id_empresa, estado, created_at)
                                        VALUES (?,?,?,?,?,?)',
            [$nombreArea, $padre, $jerarquia, $idEmpresa, 1, $fecha_sistema]
        );
        return $crearArea;
    }

    public static function ActualizarArea($nombreArea, $padre, $jerarquia, $estado, $id)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i:s');
        $crearArea = DB::Insert(
            'UPDATE Areas SET nombre = ?, padre = ?, jerarquia = ?, estado = ?, updated_at = ? WHERE id = ?',
            [$nombreArea, $padre, $jerarquia, $estado, $fecha_sistema, $id]
        );
        return $crearArea;
    }

    // EMPLEADOS
    public static function BuscarUserLogin($user)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $usuario = DB::Select("SELECT * FROM Empleados WHERE correo = '$user'");
        return $usuario;
    }

    public static function BuscarPass($user, $password)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $pass = DB::Select("SELECT * FROM Empleados WHERE correo = '$user' AND password = '$password'");
        return $pass;
    }

    public static function EmpleadoId($IdEmpleado)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $usuario = DB::Select("SELECT * FROM Empleados WHERE id = $IdEmpleado");
        return $usuario;
    }

    public static function CardProfile($id){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $profile = array();
        $cont = 0;
        $empleado = DB::Select("SELECT * FROM Empleados WHERE id = $id");
        foreach($empleado as $row1){
            $profile[$cont]["nombre"] = $row1->nombre;
            $profile[$cont]["foto"] = $row1->foto;
            $areas = DB::Select("SELECT * FROM Areas WHERE id = $row1->area");
            if($areas){
                foreach($areas as $row2){
                    $profile[$cont]["area"] = $row2->nombre;
                }                
            }else{
                $profile[$cont]["area"] = $row1->area;
            }
            $cargos = DB::Select("SELECT * FROM Cargos WHERE id = $row1->id_cargo");
            if($cargos){
                foreach($cargos as $row3){
                    $profile[$cont]["cargo"] = $row3->nombre;
                }                
            }else{
                $profile[$cont]["cargo"] = $row1->cargo;
            }
            if($row1->unidad_corporativa){
                $vp = DB::Select("SELECT * FROM Vicepresidencia WHERE id = $row1->unidad_corporativa");
                if($vp){
                    foreach($vp as $row4){
                        $profile[$cont]["vicepresidencia"] = $row4->nombre;
                    }                
                }else{
                    $profile[$cont]["vicepresidencia"] = $row1->unidad_corporativa;
                }
            }else{
                $profile[$cont]["vicepresidencia"] = "";
            }        
        }
        return $profile;
    }

    // EMPRESAS
    public static function ListarEmpresa($idEmpresa)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $empresa = DB::Select("SELECT * FROM Empresas WHERE id = $idEmpresa");
        return $empresa;
    }

    // ROLES
    public static function ListarRoles($idRol)
    {
        DB::setDefaultConnection("mysql-goforagile_admin");
        $rol = DB::Select("SELECT * FROM Roles WHERE id = $idRol AND estado = 1");
        return $rol;
    }


    // EXTRAS
    public static function FechaAmigable($fecha)
    {
        $Array_Meses = array(
            array("01", "Ene"),
            array("02", "Feb"),
            array("03", "Mar"),
            array("04", "Abr"),
            array("05", "May"),
            array("06", "Jun"),
            array("07", "Jul"),
            array("08", "Ago"),
            array("09", "Sep"),
            array("10", "Oct"),
            array("11", "Nov"),
            array("12", "Dic"),
        );
        $partes = explode("-", $fecha);

        $txt_mes = "";
        foreach ($Array_Meses as $mes) {
            if ($mes[0] == $partes[1]) {
                $txt_mes = $mes[1];
            }
        }

        return $txt_mes . " " . $partes[2] . " de " . $partes[0];
    }
}

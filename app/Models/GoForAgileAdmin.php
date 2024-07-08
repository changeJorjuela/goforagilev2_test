<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoForAgileAdmin extends Model
{
    protected $connection = 'mysql-goforagile_admin';

    public static function BuscarUserLogin($user){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $usuario = DB::Select("SELECT * FROM Empleados WHERE correo = '$user'");        
        return $usuario;
    }

    public static function BuscarPass($user,$password){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $pass = DB::Select("SELECT * FROM Empleados WHERE correo = '$user' AND password = '$password'");        
        return $pass;
    }

    public static function ListarRoles($idRol){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $rol = DB::Select("SELECT * FROM Roles WHERE id = $idRol AND estado = 1");        
        return $rol;
    }

    public static function ListarEmpresa($idEmpresa){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $empresa = DB::Select("SELECT * FROM Empresas WHERE id = $idEmpresa");        
        return $empresa;
    }

    public static function ListarAreas($idEmpresa){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $areas = DB::Select("SELECT * FROM Areas WHERE id_empresa = $idEmpresa ORDER BY nombre ASC");        
        return $areas;
    }

    public static function ListarAreasActivo($idEmpresa){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $areas = DB::Select("SELECT * FROM Areas WHERE id_empresa = $idEmpresa AND estado = 1 ORDER BY nombre ASC");        
        return $areas;
    }

    public static function BuscarNombreArea($nombreArea,$idEmpresa){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $areas = DB::Select("SELECT * FROM Areas WHERE nombre = '$nombreArea' AND id_empresa = $idEmpresa");        
        return $areas;
    }

    public static function BuscarNombreAreaUpd($nombreArea,$id){
        DB::setDefaultConnection("mysql-goforagile_admin");
        $areas = DB::Select("SELECT * FROM Areas WHERE nombre = '$nombreArea' AND id NOT IN ($id)");        
        return $areas;
    }

    public static function CrearArea($nombreArea,$padre,$jerarquia,$idEmpresa){
        DB::setDefaultConnection("mysql-goforagile_admin");
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i:s');
        $crearArea = DB::Insert('INSERT INTO Areas (nombre, padre, jerarquia, id_empresa, estado, created_at)
                                        VALUES (?,?,?,?,?,?)',
            [$nombreArea,$padre,$jerarquia,$idEmpresa, 1,$fecha_sistema]);
            return $crearArea;
    }

    public static function ActualizarArea($nombreArea,$padre,$jerarquia,$estado,$id){
        DB::setDefaultConnection("mysql-goforagile_admin");
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i:s');
        $crearArea = DB::Insert('UPDATE Areas SET nombre = ?, padre = ?, jerarquia = ?, estado = ?, updated_at = ? WHERE id = ?',
            [$nombreArea,$padre,$jerarquia,$estado,$fecha_sistema,$id]);
        return $crearArea;
    }
}

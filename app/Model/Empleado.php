<?php


class Empleado
{
    public static function AltaEmpleado($nombre, $apellido, $clave)
    {
        $fecha = date('Y-m-d');
        $pass = password_hash($clave, PASSWORD_DEFAULT);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Empleado (Nombre,Apellido,Clave,FechaAlta) 
            VALUES (:nombre,:apellido,:clave,:fecha)"
        );
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $pass, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ModificarEmpleado($idEmpleado, $nombre, $apellido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Empleado SET Nombre = :nombre, Apellido = :apellido, WHERE ID = :idEmpleado"
        );
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function CambiarEstadoEmpleado($idEmpleado, $estado)
    {
        $fecha = date('Y-m-d');
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Empleado SET Estado = :estado, FechaBaja = :fecha WHERE ID = :idEmpleado"
        );
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();
    }


    public static function ListarEmpleados()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Empleado"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function ListarEmpleadosActivos()
    {
        $estado = 'Inactivo';
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Empleado WHERE Estado != :estado"
        );
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function ListarActivosPorSector($sector)
    {
        $estado = 'Inactivo';
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Empleado WHERE Sector = :sector AND Estado != :estado"
        );
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function AsignarSector($idEmpleado, $sector)
    {
        $estado = 'Inactivo';
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Empleado SET Sector = :sector WHERE ID = :idEmpleado AND Estado != :estado"
        );
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ValidarDatos($parametros)
    {
        $nombre = $parametros['Nombre'];
        $apellido = $parametros['Apellido'];
        if (isset($nombre) && isset($apellido))
        {
            if (is_string($nombre) && is_string($apellido))
            {
                if (preg_match('/^[a-zA-Z ]+$/', $nombre) && preg_match('/^[a-zA-Z ]+$/', $apellido))
                    return true;
            }
            else
                return false;
        }
    }

    public static function BuscarEmpleadoPorID($idEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Empleado WHERE ID != :idEmpleado"
        );
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function BuscarEmpleadoPorNombre($nombre, $apellido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Empleado WHERE Nombre != :nombre AND Apellido = :apellido"
        );
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function SumarOperacion($idEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consultaUpdate = $objAccesoDatos->prepararConsulta(
            "UPDATE Empleado SET Operaciones = Operaciones + 1 WHERE ID = :idEmpleado"
        );
        $consultaUpdate->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consultaUpdate->execute();
    }

    public static function ExisteEmpleado($nombre, $apellido)
    {
        $empleados = self::ListarEmpleados();
        foreach ($empleados as $e)
        {
            if ($e->Nombre == $nombre && $e->Apellido == $apellido)
                return true;
        }
        return false;
    }
}

<?php

include_once 'Empleado.php';
include_once 'Cliente.php';
include_once '../app/Utils/Utils.php';
//include_once 'Pedido.php';

class Mesa
{
    public static function AltaMesa($idPedido)
    {
        $cliente = Cliente::$clientes[rand(0, count(Cliente::$clientes) - 1)];
        $estado = 'Con cliente esperando pedido';
        $codigo = Utils::GenerarCodigo();
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consultaUpdate = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Mesa (Estado,CodigoUnico,ID_Pedido,Cliente,ID_Empleado) 
            VALUES (:estado,:codigoUnico,:idPedido,:cliente,idEmpleado)",
        );
        $consultaUpdate->bindValue(':id', $idMesa, PDO::PARAM_INT);
        $consultaUpdate->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consultaUpdate->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consultaUpdate->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consultaUpdate->bindValue(':cliente', $cliente, PDO::PARAM_STR);
        $consultaUpdate->bindValue(':codigoUnico', $codigo, PDO::PARAM_STR);
        $consultaUpdate->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ListarMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Mesa"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function CambiarEstadoMesa($idMesa, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Mesa SET Estado = :estado WHERE ID = :idMesa"
        );
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        return;
    }

    public static function CerrarMesa($idMesa)
    {
        $estado = "Cerrada";
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Mesa SET Estado = :estado WHERE ID = :idMesa"
        );
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        return;
    }

    public static function ExisteReserva($idCliente)
    {
        $mesas = self::ListarMesas();
        foreach ($mesas as $e)
        {
            if ($e->ID_CLiente == $idCliente)
                return true;
        }
        return false;
    }

    public static function TraerMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Mesa WHERE ID = :id;"
        );
        $consulta->bindValue(':id', $idMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function ExisteMesa($idMesa)
    {
        $mesas = self::ListarMesas();
        foreach ($mesas as $e)
        {
            if ($e->ID == $idMesa)
                return true;
        }
        return false;
    }

    public static function MesaLibre($idMesa)
    {
        $mesas = self::ListarMesas();
        foreach ($mesas as $e)
        {
            if ($e->ID == $idMesa)
                if ($e->Estado == "Libre")
                    return true;
        }
        return false;
    }

    public static function CargarImagen($idPedido, $archivo)
    {
        $extension = pathinfo($archivo->getClientFilename(), PATHINFO_EXTENSION);
        $archivo->moveTo("./ImagenesDePedidos/$idPedido.$extension");
        return true;
    }
}

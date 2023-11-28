<?php


class Comanda
{
    public static function AltaComanda($idMesa, $cliente, $idEmpleado, $idPedido, $pedidos)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Comanda (Fecha,Hora,ID_Mesa,ID_Empleado,ID_Pedido,Pedidos,NombreCliente,Estado) 
        VALUES (:fecha, :hora, :idMesa, :idEmpleado, :idPedido, :pedidos,:nombreCliente,:estado)"
        );
        $fecha = date('Y-m-d');
        $hora = date('H:i:sa');
        $estado = "En preparacion";
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->bindValue(':hora', $hora, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':pedidos', $pedidos, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $cliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function BajaComanda($idComanda)
    {
        $estado = "Cancelada";
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Comanda SET Estado = :estado WHERE ID = :idComanda"
        );
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ModificarComanda($idComanda, $idMesa, $cliente, $idPedido, $pedidos)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Comanda (Fecha,Hora,ID_Mesa,ID_Pedido,Pedidos,NombreCliente,Estado) 
        VALUES (:fecha, :hora, :idMesa, :idPedido, :pedidos,:nombreCliente,:estado)"
        );
        $fecha = date('Y-m-d');
        $hora = date('H:i:sa');
        $estado = "En preparacion";
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->bindValue(':hora', $hora, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':pedidos', $pedidos, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $cliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ListarComandas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Comanda"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function TraerPedido($idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT Pedidos FROM Comanda WHERE ID = :idComanda"
        );
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }

    public static function ExistePedidoEnComanda($idPedido)
    {
        $comandas = self::ListarComandas();
        foreach ($comandas as $e)
        {
            if ($e->ID_Pedido == $idPedido)
                return true;
        }
        return false;
    }

    public static function ExisteComanda($idComanda)
    {
        $comandas = self::ListarComandas();
        foreach ($comandas as $e)
        {
            if ($e->ID == $idComanda)
                return true;
        }
        return false;
    }

    public static function CambiarEstadoComanda($idComanda, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Comanda SET Estado = :estado WHERE ID = :idComanda"
        );
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function CambiarEstadoComandaPorSector($idComanda, $estado, $sector, $tiempo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        # OBTENER EL PEDIDO POR ID
        $pedido = self::TraerPedido($idComanda);
        if (!$pedido)
            return;

        # OBTENER LOS PRODUCTOS DEL PEDIDO
        $productos = json_decode($pedido[0]->Pedidos);
        foreach ($productos as $e)
        {
            if ($e->Sector == $sector)
            {
                $e->Estado = $estado;
                $e->Tiempo = $tiempo;
            }
            $productosMod[] = $e;
        }
        $tiempoEstimado = max(array_column($productosMod, 'Tiempo'));
        $aux = Utils::DameUnEmpleado($sector);
        Empleado::SumarOperacion($aux->ID);

        # ACTUALIZAR EL PEDIDO EN LA BASE DE DATOS
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Comanda SET Pedidos = :productos, TiempoEstimado = :tiempo WHERE ID = :id"
        );
        $consulta->bindValue(':id', $idComanda, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo', $tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':productos', json_encode($productosMod), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ComandaLista($idComanda)
    {
        $estado = 'Listo para servir';
        $pedido = self::TraerPedido($idComanda);

        $objetoComanda = json_decode($pedido[0]->Pedidos, true);

        foreach ($objetoComanda as $e)
        {
            if ($e['Estado'] != $estado)
                return;
        }
        self::CambiarEstadoComanda($idComanda, $estado);
    }

    public static function MostrarEstado($clavePedido, $claveMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT Pedidos, TiempoEstimado FROM Comanda WHERE ClavePedido = :clavePedido AND ClaveMesa = :claveMesa"
        );
        $consulta->bindValue(':clavePedido', $clavePedido, PDO::PARAM_STR);
        $consulta->bindValue(':claveMesa', $claveMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }
}

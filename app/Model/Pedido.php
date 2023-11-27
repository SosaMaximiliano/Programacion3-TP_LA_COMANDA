<?php


include_once 'Producto.php';
include_once '../app/Utils/Utils.php';

class Pedido
{
    public static function AltaPedido($productos, $idMesa)
    {
        $pedido = array();
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $tiempoEstimado = '00:00';
        $valorTotal = 0;
        foreach ($productos as $e)
        {
            $idProducto = $e['ID_Producto'];
            $cantidad = $e['Cantidad'];
            $producto = Producto::BuscarProductoID($idProducto);
            //$tiempoEstimado = self::CalcularTiempoEstimado($tiempoEstimado, $producto[0]->Tiempo);
            $pedido[] = [
                'Producto' => $producto[0]->Nombre,
                'Cantidad' => $cantidad,
                'Sector'   => $producto[0]->Sector,
                'Tiempo'   => $tiempoEstimado,
                'Estado'   => 'Pedido'
            ];
            $valorTotal += $producto[0]->Precio;
        }

        #PREPARO LA QUERY DEL PEDIDO
        $codigo = Utils::GenerarCodigo();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Pedido 
            (Productos,ID_Mesa,CodigoUnico,TiempoEstimado,ValorTotal) 
            VALUES (:productos,:idMesa,:codigo,:tiempo,:valorTotal)"
        );
        $consulta->bindValue(':tiempo', $tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(':productos', json_encode($pedido, true), PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':valorTotal', $valorTotal, PDO::PARAM_INT);
        $consulta->execute();

        return $codigo;
    }

    public static function BajaPedido($idPedido)
    {
        $estado = 'Cancelado';
        //PARA REPONER LOS PRODUCTOS AL STOCK MULTIPLICAR POR -1 LOS PRODUCTOS Y ACTUALIZAR STOCK 
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Pedido SET Estado = :estado WHERE ID = :idPedido"
        );
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function ModificarPedido($idPedido, $productos)
    {
        $pedido = array();
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $tiempoEstimado = '00:00';
        $valorTotal = 0;
        foreach ($productos as $e)
        {
            $idProducto = $e['Producto'];
            $producto = Producto::BuscarProductoID($idProducto);
            $productoNombre = $producto->Nombre;
            $sector = $producto->Sector;
            $estado = 'Pedido';
            $cantidad = $e['Cantidad'];
            $tiempoEstimado = self::CalcularTiempoEstimado($tiempoEstimado, $producto->Tiempo);
            $pedido[] = [
                'Producto' => $productoNombre,
                'Cantidad' => $cantidad,
                'Sector'   => $sector,
                'Tiempo'   => $tiempoEstimado,
                'Estado'   => $estado
            ];

            $valorTotal += $producto->Precio;
        }

        #PREPARO LA QUERY DEL PEDIDO
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Pedido SET Productos = :productos WHERE ID = :idPedido"
        );
        $consulta->bindValue(':tiempo', $tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(':productos', json_encode($pedido), PDO::PARAM_STR);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
        $consulta->bindValue(':valorTotal', $valorTotal, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function ListarPedidosPorSector($sector)
    {
        $pedidos = self::ListarPedidosObj();
        $psector = [];
        foreach ($pedidos as $pedido)
        {
            $productos = json_decode($pedido->Productos, true);
            foreach ($productos as $producto)
            {
                if ($producto['Sector'] === $sector)
                {
                    $psector[] = array(
                        'ID_Pedido' => $pedido->ID,
                        'Producto' => $producto['Producto'],
                        'Cantidad' => $producto['Cantidad'],
                        'Sector' => $producto['Sector'],
                        'Tiempo' => $producto['Tiempo'],
                        'Estado' => $producto['Estado'],
                    );
                }
            }
        }
        return $psector;
    }

    public static function ListarPedidosObj()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Pedido"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function ListarPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Pedido"
        );
        $consulta->execute();

        $pedidos = $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');

        foreach ($pedidos as $pedido)
        {
            $productos = json_decode($pedido->Productos);
            foreach ($productos as $producto)
            {
                $psector[] = $producto;
            }
        }

        return $psector;
    }

    public static function ListarPedidosPorEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Pedido WHERE Estado = :estado"
        );
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function TraerPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Pedido WHERE ID = :idPedido"
        );
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function TraerPedidoPorClave($clave)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Pedido WHERE CodigoUnico = :clave"
        );
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function TraerMesa($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Mesa WHERE ID_Pedido = :idPedido"
        );
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function CambiarEstadoPedido($idPedido, $estado)
    {
        $tiempoEstimado = self::EstimarTiempoPedido($idPedido);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Pedido SET Estado = :estado, TiempoEstimado = :tiempo WHERE ID = :id"
        );
        $consulta->bindValue(':id', $idPedido, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function CambiarEstadoPedidoPorSector($idPedido, $estado, $sector, $tiempo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        # OBTENER EL PEDIDO POR ID
        $pedido = self::TraerPedido($idPedido);
        if (!$pedido)
            return;

        # OBTENER LOS PRODUCTOS DEL PEDIDO
        $productos = json_decode($pedido[0]->Productos);

        foreach ($productos as $producto)
        {
            if ($producto->Sector == $sector)
            {
                $producto->Estado = $estado;
                $producto->Tiempo = $tiempo;
            }
            $productosMod[] = $producto;
        }

        $aux = Utils::DameUnEmpleado($sector);
        Empleado::SumarOperacion($aux->ID);

        # ACTUALIZAR EL PEDIDO EN LA BASE DE DATOS
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Pedido SET Productos = :productos WHERE ID = :id"
        );
        $consulta->bindValue(':id', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':productos', json_encode($productosMod), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ExistePedido($idPedido)
    {
        $pedidos = self::ListarPedidosObj();
        foreach ($pedidos as $e)
        {
            if ($e->ID == $idPedido)
                return true;
        }
        return false;
    }

    public static function TraerCliente($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Cliente WHERE ID_Pedido = :idPedido"
        );
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
    }

    private static function ActualizoStock($idProducto, $cantidad)
    {
        #ACTUALIZO LA CANTIDAD DE PRODUCTOS
        $producto = Producto::BuscarProductoID($idProducto);
        $cantAux = $producto->Cantidad - $cantidad;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consultaUpdate = $objAccesoDatos->prepararConsulta(
            "UPDATE Producto SET Cantidad = :cantidad WHERE ID = :id"
        );
        $consultaUpdate->bindValue(':cantidad', $cantAux, PDO::PARAM_INT);
        $consultaUpdate->bindValue(':id', $idProducto, PDO::PARAM_INT);
        $consultaUpdate->execute();
    }

    private static function CalcularTiempoEstimado($tPedido, $tProducto)
    {
        $tiempoPr = explode(':', $tProducto);
        $horasPr = intval($tiempoPr[0]);
        $minutosPr = intval($tiempoPr[1]);

        $tiempoPd = explode(':', $tPedido);
        $horasPd = intval($tiempoPd[0]);
        $minutosPd = intval($tiempoPd[1]);

        $tprAux = ($horasPr * 60 + $minutosPr);
        $tpdAux = ($horasPd * 60 + $minutosPd);

        if ($tprAux > $tpdAux)
            return sprintf("%02d:%02d", floor($tprAux / 60), $tprAux % 60);
        else
            return sprintf("%02d:%02d", floor($tpdAux / 60), $tpdAux % 60);
    }

    private static function EstimarTiempoPedido($idPedido)
    {
        #RECORRER LOS PRODUCTOS DEL PEDIDO Y TOMAR EL MAYOR TIEMPO
        $tiempoEstimado = '00:00';
        $pedido = self::TraerPedido($idPedido);
        if (!$pedido)
            return;

        # OBTENER LOS PRODUCTOS DEL PEDIDO
        $productos = json_decode($pedido[0]->Productos);

        foreach ($productos as $producto)
        {
            if ($producto->Tiempo > $tiempoEstimado)
            {
                $tiempoEstimado = $producto->Tiempo;
            }
        }
        return $tiempoEstimado;
    }
}

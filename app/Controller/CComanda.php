<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './Model/Comanda.php';
require_once './Model/Pedido.php';
require_once './Model/Mesa.php';

class CComanda
{
    public static function AltaComanda(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idMesa = $parametros['ID_Mesa'];
        $mesa = Mesa::TraerMesa($idMesa);
        $pedido = Pedido::TraerPedido($mesa[0]->ID_Pedido);
        try
        {
            Comanda::AltaComanda($idMesa, $mesa[0]->Cliente, $mesa[0]->ID_Empleado, $mesa[0]->ID_Pedido, $pedido[0]->Productos);
            $payload = json_encode("Comanda creada correctamente");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al crear comanda. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function CambiarEstadoComanda(Request $request, Response $response, $args)
    {
    }

    public static function CambiarEstadoComandaPorSector(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idComanda = $parametros['ID_Comanda'];
        $idPedido = $parametros['ID_Pedido'];
        $sector = $parametros['Sector'];
        $tiempo = $parametros['Tiempo'];
        $estado = $parametros['Estado'];
        $estados = array(
            "Pedido",
            "En preparacion",
            "Listo para servir",
            "Entregado"
        );
        if (Comanda::ExisteComanda($idComanda))
        {
            if (Pedido::ExistePedido($idPedido))
            {
                if (in_array($estado, $estados))
                {
                    try
                    {
                        Comanda::CambiarEstadoComandaPorSector($idComanda, $estado, $sector, $tiempo);
                        Comanda::ComandaLista($idComanda);
                        $payload = json_encode("Estado del pedido cambiado a {$estado}");
                        $response->getBody()->write($payload);
                        return $response->withHeader('Content-Type', 'application/json');
                    }
                    catch (Exception $e)
                    {
                        $payload = json_encode("Error al cambiar de estado. {$e->getMessage()}");
                        $response->getBody()->write($payload);
                        return $response->withHeader('Content-Type', 'application/json');
                    }
                }
                else
                    throw new Exception("Estado incorrecto", 200);
            }
            else
                throw new Exception("Pedido inexistente", 200);
        }
        else
            throw new Exception("Comanda inexistente", 200);
    }

    public static function ComandaLista(Request $request, Response $response, $args)
    {
        $parametros = $request->getQueryParams();
        $idComanda = $parametros['ID_Comanda'];
        try
        {
            $payload = json_encode(Comanda::ComandaLista($idComanda));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al consultar comanda. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function MostrarEstado(Request $request, Response $response, $args)
    {
        $parametros = $request->getQueryParams();
        $clavePedido = $parametros['ClavePedido'];
        $claveMesa = $parametros['ClaveMesa'];
        try
        {
            $payload = json_encode(Comanda::MostrarEstado($clavePedido, $claveMesa));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al consultar estado. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}

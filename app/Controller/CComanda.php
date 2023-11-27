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
}

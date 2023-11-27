<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/./db/AccesoDatos.php';
require __DIR__ . '/./Controller/CEmpleado.php';
require __DIR__ . '/./Controller/CProducto.php';
require __DIR__ . '/./Controller/CMesa.php';
require __DIR__ . '/./Controller/CPedido.php';
require __DIR__ . '/./Controller/CCliente.php';
require __DIR__ . '/./Controller/CComanda.php';
require __DIR__ . '/./Controller/CAuthJWT.php';
require __DIR__ . '/./Controller/CEncuesta.php';
require __DIR__ . '/./Middleware/AuthMiddleware.php';
require __DIR__ . '/./Middleware/AltaEmpleadoMiddleware.php';
require __DIR__ . '/./Middleware/CheckTokenMiddleware.php';
require __DIR__ . '/./Middleware/CheckMozoMiddleware.php';
require __DIR__ . '/./Middleware/CheckSocioMiddleware.php';
require __DIR__ . '/./Middleware/CheckCocineroMiddleware.php';
require __DIR__ . '/./Middleware/CheckBartenderMiddleware.php';
require __DIR__ . '/./Middleware/CheckCerveceroMiddleware.php';
require __DIR__ . '/./Middleware/EmpleadoActivoMiddleware.php';


// Instantiate App
$app = AppFactory::create();

// Set base path
$app->setBasePath('/Programacion3-TP_LA_COMANDA/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

$app->post('/login', \CAuthJWT::class . ':CrearTokenLogin'); //->add(new EmpleadoActivoMiddleware());

$app->group('/empleado', function (RouteCollectorProxy $group)
{
    #PRODUCTOS
    $group->post('/producto/alta', \CProducto::class . ':AgregarProducto');
    $group->get('/producto/listar', \CProducto::class . ':ListarProductos');
    $group->get('/producto/buscarporid', \CProducto::class . ':BuscarProductoID');
    $group->get('/producto/buscarpornombre', \CProducto::class . ':BuscarProductoNombre');

    #PEDIDOS
    $group->post('/pedido/alta', \CPedido::class . ':AltaPedido')->add(new CheckMozoMiddleware());
    $group->get('/pedido/traerpedido', \CPedido::class . ':TraerPedidoPorID');
    $group->get('/pedido/listar', \CPedido::class . ':ListarPedidos')->add(new CheckMozoMiddleware());
    $group->get('/pedido/listarporsector', \CPedido::class . ':ListarPedidosPorSector')
        ->add(new CheckCocineroMiddleware());
    // ->add(new CheckBartenderMiddleware());
    // ->add(new CheckCerveceroMiddleware());
    $group->get('/pedido/listarporcliente', \CPedido::class . ':ListarPedidosPorCliente')->add(new CheckMozoMiddleware());
    $group->put('/pedido/cambiarestado', \CPedido::class . ':CambiarEstadoPedido')->add(new CheckMozoMiddleware());
    $group->put('/pedido/cambiarestadoporsector', \CPedido::class . ':CambiarEstadoPedidoPorSector');
    $group->post('/pedido/cargarimagen', \CPedido::class . ':CargarImagen')->add(new CheckMozoMiddleware());

    #MESAS
    $group->post('/mesa/alta', \CMesa::class . ':AltaMesa')->add(new CheckMozoMiddleware());
    $group->get('/mesa/listar', \CMesa::class . ':ListarMesas')->add(new CheckMozoMiddleware());
    $group->put('/mesa/cambiarestado', \CMesa::class . ':CambiarEstadoMesa')->add(new CheckMozoMiddleware());
    //$group->get('/mesa/listarporcliente', \CMesa::class . ':ListarPedidosPorCliente');

    #COMANDA
    $group->post('/comanda/alta', \CComanda::class . ':AltaComanda')->add(new CheckMozoMiddleware());
    $group->post('/comanda/cambiarestado', \CComanda::class . ':CambiarEstado')->add(new CheckCocineroMiddleware());

    $group->post('/subirfotos', \CPedido::class . ':SubirFoto')->add(new CheckCocineroMiddleware());
})->add(new CheckTokenMiddleware());


$app->group('/socio', function (RouteCollectorProxy $group)
{
    $group->post('/mesa/crear', \CMesa::class . ':CrearMesa');
    $group->post('/cliente/alta', \CCliente::class . ':AltaCliente');
    $group->post('/empleado/alta', \CEmpleado::class . ':IngresarEmpleado')->add(new ValidadorMiddleware());
    $group->delete('/empleado/baja', \CEmpleado::class . ':BajaEmpleado');
    $group->get('/empleado/listar', \CEmpleado::class . ':ListarEmpleados');
    $group->get('/empleado/listarporsector', \CEmpleado::class . ':ListarEmpleadosPorSector');
    $group->put('/empleado/asignarsector', \CEmpleado::class . ':AsignarSector');
    $group->post('/producto/cargarcsv', \CProducto::class . ':CargarProductosCSV');
    $group->get('/producto/exportartabla', \CProducto::class . ':ExportarTabla');
})
    ->add(new CheckSocioMiddleware())
    ->add(new CheckTokenMiddleware());

$app->group('/cliente', function (RouteCollectorProxy $group)
{
    $group->get('/pedido', \CPedido::class . ':TraerPedidoPorClave');
    $group->post('/encuesta', \CEncuesta::class . ':RealizarEncuesta');
});

$app->get('[/]', function (Request $request, Response $response)
{
    $response->getBody()->write("La Comanda - TP Programacion III ");
    return $response;
});

$app->run();

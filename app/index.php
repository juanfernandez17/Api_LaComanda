<?php

error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Cookie\Cookie;

require __DIR__ . '/../vendor/autoload.php';
include_once './Controllers/EmpleadoController.php';
include_once './Controllers/ProductoController.php';
include_once './Controllers/MesaController.php';
include_once './Controllers/PedidoController.php';
include_once './DataBase/DataAccess.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/Autentificador.php';
require_once './middlewares/Logger.php';
require_once './middlewares/Validador.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$app = AppFactory::create();
$app->setBasePath('/PROG_TP_LaComanda');

$app->group('/empleado', function (RouteCollectorProxy $group) {
  $group->get('[/]', \EmpleadoController::class . '::TraerTodos');
  $group->get('/{id}', \EmpleadoController::class . '::TraerUno');
  $group->post('[/]', \EmpleadoController::class . '::CargarUno')->add(\Validador::class . '::ValidarNuevoUsuario')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \EmpleadoController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . '::TraerTodos');
  $group->get('/{id}', \ProductoController::class . '::TraerUno');
  $group->post('[/]', \ProductoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \ProductoController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \ProductoController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->post('/csv/cargarProducto', \ProductoController::class . '::CargarProductosCSV');
  $group->get('/csv/descargarProducto', \ProductoController::class . '::DescargarProductosCSV');
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . '::TraerTodos');
  $group->get('/{id}', \MesaController::class . '::TraerUno');
  $group->get('/cerrar/{id}', \MesaController::class . '::CerrarMesa')->add(\Autentificador::class . '::ValidarSocio');
  $group->post('[/]', \MesaController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \MesaController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \MesaController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . '::TraerTodos');
  $group->get('/{codigoPedido}', \PedidoController::class . '::TraerUno');
  $group->delete('/{codigoPedido}', \PedidoController::class . '::BorrarUno');
  $group->get('/pendientes/cargar', \PedidoController::class . '::TraerPendientes');
  $group->post('/inicio/{codigoPedido}', \PedidoController::class . '::IniciarPedido');
  $group->post('/final/{codigoPedido}', \PedidoController::class . '::FinalizarPedido');
  $group->post('[/]', \PedidoController::class . '::CargarUno');
});

$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EmpleadoController::class . '::Login')->add(\Logger::class . '::ValidarLogin');
});

$app->run();
?>
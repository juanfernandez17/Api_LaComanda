<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
include_once './Controllers/EmpleadoController.php';
include_once './Controllers/ProductoController.php';
include_once './Controllers/MesaController.php';
include_once './Controllers/PedidoController.php';
include_once './DataBase/DataAccess.php';

// Carga el archivo .env con la configuracion de la BD.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
$app->setBasePath('/PROG_TP_LaComanda');
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->group('/empleado', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EmpleadoController::class . '::CargarUno');
  $group->put('/{id}', \EmpleadoController::class . '::ModificarUno');
  $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno');
  $group->get('[/]', \EmpleadoController::class . '::TraerTodos');
  $group->get('/{id}', \EmpleadoController::class . '::TraerUno');
});

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->post('[/]', \ProductoController::class . '::CargarUno');
  $group->put('/{id}', \ProductoController::class . '::ModificarUno');
  $group->delete('/{id}', \ProductoController::class . '::BorrarUno');
  $group->get('[/]', \ProductoController::class . '::TraerTodos');
  $group->get('/{id}', \ProductoController::class . '::TraerUno');
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->post('[/]', \MesaController::class . '::CargarUno');
  $group->put('/{id}', \MesaController::class . '::ModificarUno');
  $group->delete('/{id}', \MesaController::class . '::BorrarUno');
  $group->get('[/]', \MesaController::class . '::TraerTodos');
  $group->get('/{id}', \MesaController::class . '::TraerUno');
});

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->post('[/]', \PedidoController::class . '::CargarUno');
  $group->put('/{id}', \PedidoController::class . '::ModificarUno');
  $group->delete('/{id}', \PedidoController::class . '::BorrarUno');
  $group->get('[/]', \PedidoController::class . '::TraerTodos');
  $group->get('/{id}', \PedidoController::class . '::TraerUno');
});

$app->run();
?>
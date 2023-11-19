<?php
use Slim\Http\Request;
use Slim\Http\Response;

require_once './models/Pedido_producto.php';
require_once './Interfaces/IInterfaceAPI.php';

class PedidoProductoController
{
    public static function CargarUno($pedido_producto)
    {
        crearPedidoProducto($pedido_producto);
    }
}
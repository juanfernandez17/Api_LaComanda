<?php
use Slim\Http\Request;
use Slim\Http\Response;

require_once './Controllers/PedidoProductoController.php';
require_once './models/Pedido_producto.php';
require_once './models/Pedido.php';
require_once './Interfaces/IInterfaceAPI.php';

class PedidoController extends Pedido implements IInterfaceAPI
{
    public static function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id_empleado = $params["id_empleado"];
        $estado = $params["estado"];
        $id_mesa = $params["id_mesa"];                   
        $productos = $params["productos"];        
        $tiempo_estimado = $params["tiempo_estimado"];
        
        $array = json_decode($productos, true);
        $empleado = Empleado::obtenerEmpleado($id_empleado);        
        $mesa = Mesa::obtenerMesa($id_mesa);
        $listaProductos = [];

        if($empleado != false && $array != null && $mesa != null)
        {
            $codigo_pedido = self::GenerarCodigoPedido();

            foreach ($array as $item) {
        
                $producto = Producto::obtenerProducto($item['id']);
               
                if($producto != null)
                {              
                    $pedido_producto = new Pedido_producto();
                    $pedido_producto->codigo_mesa = $mesa->codigo_mesa;
                    $pedido_producto->nombre_cliente = $item['nombre_cliente'];
                    $pedido_producto->id_producto = $producto->id;
                    $pedido_producto->estado = 'En preparación';
                    $pedido_producto->codigo_pedido = $codigo_pedido;
                    array_push($listaProductos, $pedido_producto);
                }
                else
                {
                     $payload = json_encode(array("mensaje" => "Error! ID de producto no válido."));
                     $response->getBody()->write($payload);
                }
            }

            foreach($listaProductos as $pedido_producto)
            {          
                Pedido_producto::crearPedidoProducto($pedido_producto);
            }
    
            $pedido = new Pedido();
            $pedido->setEstado($estado);
            $pedido->setCodigoPedido($codigo_pedido);        
            $pedido->setIdEmpleado($id_empleado);
            $pedido->setIdMesa($id_mesa);
            $pedido->setTiempoEstimado($tiempo_estimado);
            Pedido::crearPedido($pedido);
            
            $guardadojson = json_encode(array("mensaje" => "Pedido agregado con exito!"));
            $response->getBody()->write($guardadojson);
        }
        else
        {
             $payload = json_encode(array("mensaje" => "Error! ID de empleado, mesa o lista de productos ingresada no válida."));
             $response->getBody()->write($payload);
        }

       
       
        return $response->withHeader("Content-Type","application/json");        
    }

    public static function TraerUno($request, $response, $args)
    {                
        $codigo_pedido = $args['codigoPedido'];
        $pedido = Pedido::obtenerPedidoPorCodigo($codigo_pedido);
        
        if($pedido != null)
        {
            $payload = json_encode($pedido);
            $response->getBody()->write($payload);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error! El codigo ingresado no corresponde a ningun pedido."));
            $response->getBody()->write($payload);
        }
        return $response->withHeader("Content-Type","application/json");    
    }

    public static function TraerTodos($request,$response,$args)
    {      
        $pedidos = Pedido::obtenerPedidos();
        $payload = json_encode(array("listaPedidos" => $pedidos));
        
        $response->getBody()->write($payload);

        return $response->withHeader("Content-Type","application/json");    
    }

    public static function BorrarUno($request, $response, $args)
    {
        $codigo_pedido = $args['codigoPedido'];

        if (Pedido::obtenerPedidoPorCodigo($codigo_pedido) != false) 
        {
            Pedido::BorrarPedido($codigo_pedido);
            $payload = json_encode(array("mensaje" => "Pedido borrado con exito!"));
        } 
        else 
        {    
          $payload = json_encode(array("mensaje" => "No existe pedido con el ID ingresado."));
        }
    
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ModificarUno($request, $response, $args)
    {
        $id = $args['id'];
        $pedido = Pedido::obtenerPedido($id);
  
        if ($pedido) 
        {
            $params = $request->getParsedBody();
    
            $actualizado = false;
            if (isset($params['estado']))
            {
                $actualizado = true;
                $pedido->estado = $params['estado'];
            }
            if (isset($params['horario_fin']))
            {
                $actualizado = true;
                $pedido->horario_fin = $params['horario_fin'];
            }            
            if ($actualizado)
            {
                Pedido::modificarPedido($pedido);
                $payload = json_encode(array("mensaje" => "Se ha modificado el pedido con exito!"));
            } 
            else 
            {
                $payload = json_encode(array("mensaje" => "No se pudo modificar al pedido por no ingresar los datos correspondientes!"));
            }    
        } 
        else 
        {
            $payload = json_encode(array("error" => "No existe pedido con el ID ingresado."));
        }
    
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');    
    }

    public static function GenerarCodigoPedido()
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';

        $caracteresLength = strlen($caracteres);
       
        for ($i = 0; $i < 6; $i++) {
            $codigo .= $caracteres[random_int(0, $caracteresLength - 1)];
        }

        // Verificar que el código no exista
        $listaPedidos = Pedido::obtenerPedidos();

        foreach($listaPedidos as $pedidos)
        {
            if($pedidos->codigo_pedido == $codigo)
            {
                self::GenerarCodigoPedido();
            }
        }
        return $codigo;
    }
}

?>
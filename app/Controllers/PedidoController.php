<?php
use Slim\Http\Request;
use Slim\Http\Response;

require_once './models/Pedido.php';
require_once './Interfaces/IInterfaceAPI.php';

class PedidoController extends Pedido implements IInterfaceAPI
{
    public static function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $codigo_pedido = $params["codigo_pedido"];
        $estado = $params["estado"];
        $id_empleado = $params["id_empleado"];
        $id_mesa = $params["id_mesa"];                   
        $id_producto = $params["id_producto"];
        $nombre_cliente = $params["nombre_cliente"];
        $tiempo_estimado = $params["tiempo_estimado"];
        

        $pedido = new Pedido();
        $pedido->setCodigoPedido($codigo_pedido);        
        $pedido->setEstado($estado);
        $pedido->setIdEmpleado($id_empleado);
        $pedido->setIdMesa($id_mesa);
        $pedido->setIdProducto($id_producto);
        $pedido->setNombreCliente($nombre_cliente);
        $pedido->setTiempoEstimado($tiempo_estimado);
        Pedido::crearPedido($pedido);
        
        $guardadojson = json_encode(array("mensaje" => "Pedido agregado con exito!"));
        $response->getBody()->write($guardadojson);
       
        return $response->withHeader("Content-Type","application/json");        
    }

    public static function TraerUno($request, $response, $args)
    {        
        $id = $args['id'];
        $pedido = Pedido::obtenerPedido($id);
        
        $payload = json_encode($pedido);
        $response->getBody()->write($payload);
      
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
        $id = $args['id'];

        if (Pedido::obtenerPedido($id) != false) 
        {
            Pedido::borrarPedido($id);
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
}

?>
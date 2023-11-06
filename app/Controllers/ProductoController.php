<?php
use Slim\Http\Request;
use Slim\Http\Response;

require_once './models/Producto.php';
require_once './Interfaces/IInterfaceAPI.php';

class ProductoController extends Producto implements IInterfaceAPI
{
    public static function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $nombre = $params["nombre"];
        $sector = $params["sector"];
        $precio = $params["precio"];
        $cant_vendida= $params["cant_vendida"];           

        $producto = new Producto();
        $producto->setNombre($nombre);
        $producto->setSector($sector);
        $producto->setPrecio($precio) ;
        $producto->setCantVendida($cant_vendida);

        Producto::crearProducto($producto);
        
        $guardadojson = json_encode(array("mensaje" => "Producto agregado con exito!"));
        $response->getBody()->write($guardadojson);
       
        return $response->withHeader("Content-Type","application/json");        
    }

    public static function TraerUno($request, $response, $args)
    {        
        $id = $args['id'];
        $producto = Producto::obtenerProducto($id);
        
        $payload = json_encode($producto);
        $response->getBody()->write($payload);
      
        return $response->withHeader("Content-Type","application/json");    
    }

    public static function TraerTodos($request,$response,$args)
    {      
        $productos = Producto::obtenerProductos();
        $payload = json_encode(array("listaProductos" => $productos));
        
        $response->getBody()->write($payload);

        return $response->withHeader("Content-Type","application/json");    
    }

    public static function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];

        if (Producto::obtenerProducto($id) != false) 
        {
            Producto::borrarProducto($id);
            $payload = json_encode(array("mensaje" => "Producto borrado con exito!"));
        } 
        else 
        {    
          $payload = json_encode(array("mensaje" => "No existe producto con el ID ingresado."));
        }
    
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ModificarUno($request, $response, $args)
    {
        $id = $args['id'];
        $producto = Producto::obtenerProducto($id);
  
        if ($empleado) 
        {
            $params = $request->getParsedBody();
    
            $actualizado = false;
            if (isset($params['nombre']))
            {
                $actualizado = true;
                $producto->nombre = $params['nombre'];
            }
            if (isset($params['sector']))
            {
                $actualizado = true;
                $producto->sector = $params['sector'];
            }
            if(isset($params["precio"]))
            {
                $actualizado = true;
                $producto->precio = $params['precio'];
            }
            if(isset($params["cant_vendida"]))
            {
                $actualizado = true;
                $producto->cant_vendida = $params['cant_vendida'];
            }          
            if ($actualizado)
            {
                Producto::modificarProducto($producto);
                $payload = json_encode(array("mensaje" => "Se ha modificado el producto con exito!"));
            } 
            else 
            {
                $payload = json_encode(array("mensaje" => "No se pudo modificar al producto por no ingresar los datos correspondientes!"));
            }    
        } 
        else 
        {
            $payload = json_encode(array("error" => "No existe producto con el ID ingresado."));
        }
    
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');    
    }
}

?>
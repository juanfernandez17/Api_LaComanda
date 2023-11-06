<?php
use Slim\Http\Request;
use Slim\Http\Response;

require_once './models/Mesa.php';
require_once './Interfaces/IInterfaceAPI.php';

class MesaController extends Mesa implements IInterfaceAPI
{
    public static function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $codigo_mesa = $params["codigo_mesa"];
        $estado= $params["estado"];
        $cant_utilizada = $params["cant_utilizada"];
        $cant_facturacion = $params["cant_facturacion"];
        $mayor_importe = $params["mayor_importe"];
        $menor_importe = $params["menor_importe"];       

        $mesa = new Mesa();
        $mesa->codigo_mesa = $codigo_mesa;
        $mesa->estado = $estado;
        $mesa->cant_utilizada = $cant_utilizada;
        $mesa->cant_facturacion = $cant_facturacion;
        $mesa->mayor_importe = $mayor_importe;
        $mesa->menor_importe = $menor_importe;

        Mesa::crearMesa($mesa);
        
        $guardadojson = json_encode(array("mensaje" => "Mesa agregada con exito!"));
        $response->getBody()->write($guardadojson);
       
        return $response->withHeader("Content-Type","application/json");        
    }

    public static function TraerUno($request, $response, $args)
    {
        
        $id = $args['id'];
        $mesa = Mesa::obtenerMesa($id);
        
        $payload = json_encode($mesa);
        $response->getBody()->write($payload);
      
        return $response->withHeader("Content-Type","application/json");    
    }

    public static function TraerTodos($request,$response,$args)
    {      
        $mesas = Mesa::obtenerMesas();
        $payload = json_encode(array("listaMesas" => $mesas));
        
        $response->getBody()->write($payload);

        return $response->withHeader("Content-Type","application/json");    
    }

    public static function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];

        if (Mesa::obtenerMesa($id) != false) 
        {
            Mesa::borrarMesa($id);
            $payload = json_encode(array("mensaje" => "Mesa dada de baja con exito!"));
        } 
        else 
        {    
          $payload = json_encode(array("mensaje" => "No existe mesa con el ID ingresado."));
        }
    
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ModificarUno($request, $response, $args)
    {
        $id = $args['id'];
        $mesa = Mesa::obtenerMessa($id);
  
        if ($mesa) 
        {
            $params = $request->getParsedBody();
    
            $actualizado = false;
            if (isset($params['codigo_mesa']))
            {
                $actualizado = true;
                $mesa->codigo_mesa= $params['codigo_mesa'];
            }
            if (isset($params['estado']))
            {
                $actualizado = true;
                $mesa->estado = $params['estado'];
            }
            if(isset($params["cant_utilizada"]))
            {
                $actualizado = true;
                $mesa->cant_utilizada = $params['cant_utilizada'];
            }
            if(isset($params["cant_facturacion"]))
            {
                $actualizado = true;
                $mesa->cant_facturacion = $params['cant_facturacion'];
            }
            if(isset($params["mayor_importe"]))
            {
                $actualizado = true;
                $mesa->mayor_importe = $params['mayor_importe'];
            }  
            if(isset($params["menor_importe"]))
            {
                $actualizado = true;
                $mesa->menor_importe = $params['menor_importe'];
            }          
            if ($actualizado)
            {
                Mesa::modificar($mesa);
                $payload = json_encode(array("mensaje" => "Se ha modificado la mesa con exito!"));
            } 
            else 
            {
                $payload = json_encode(array("mensaje" => "No se pudo modificar la mesa por no ingresar los datos correspondientes!"));
            }    
        } 
        else 
        {
            $payload = json_encode(array("error" => "No existe mesa con el ID ingresado."));
        }
    
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');    
    }
}

?>
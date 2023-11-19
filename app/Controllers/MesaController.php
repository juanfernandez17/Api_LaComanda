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
        $cant_utilizada = $params["cant_utilizada"];
        $cant_facturacion = $params["cant_facturacion"];
        $mayor_importe = $params["mayor_importe"];
        $menor_importe = $params["menor_importe"];       

        $mesa = new Mesa();
        $mesa->codigo_mesa = self::GenerarCodigoMesa();
        echo $mesa->codigo_mesa;
        $mesa->estado = "Con cliente esperando pedido";
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
        
        if($mesa != null)
        {
            $payload = json_encode($mesa);
            $response->getBody()->write($payload);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error! El ID ingresado no corresponde a ninguna mesa."));
            $response->getBody()->write($payload);
        }      
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
        $response->withHeader('Content-Type', 'application/x-www-form-urlencoded'); 
        $id = $args['id'];
        $body = file_get_contents('php://input');
        parse_str($body, $parametros);
        $mesa = Mesa::obtenerMesa($id);
            
        if ($mesa != false) 
        {    
            $mesa->setEstado($parametros['estado']);
            $mesa->setCantUtilizada($parametros['cant_utilizada']);
            $mesa->setCantFacturacion($parametros['cant_facturacion']);
            $mesa->setMayorImporte($parametros['mayor_importe']);
            $mesa->setMenorImporte($parametros['menor_importe']);
            Mesa::modificarMesa($mesa);
    
            $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
            
        } else {
            $payload = json_encode(array("mensaje" => "Error! El ID ingresado no corresponde a ninguna mesa."));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    public static function cerrarMesa($request, $response, $args)
    {
        $id = $args['id'];
        $mesa = Mesa::obtenerMesa($id);
        
        if($mesa != null)
        {
            $mesa->estado = "Cerrada";
            Mesa::modificarMesa($mesa);
            $payload = json_encode(array("mensaje" => "Se cerro la mesa con exito!"));
            $response->getBody()->write($payload);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error! El ID ingresado no corresponde a ninguna mesa."));
            $response->getBody()->write($payload);
        }      
        return $response->withHeader("Content-Type","application/json");    
    }

    public static function GenerarCodigoMesa()
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';

        $caracteresLength = strlen($caracteres);
       
        for ($i = 0; $i < 6; $i++) {
            $codigo .= $caracteres[random_int(0, $caracteresLength - 1)];
        }

        // Verificar que el código no exista
        $listaMesas = Mesa::obtenerMesas();

        foreach($listaMesas as $mesas)
        {
            if($mesas->codigo_mesa == $codigo)
            {
                self::GenerarCodigoMesa();
            }
        }
        return $codigo;
    }
}

?>
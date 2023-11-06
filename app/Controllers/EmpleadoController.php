<?php
use Slim\Http\Request;
use Slim\Http\Response;

require_once './models/Empleado.php';
require_once './Interfaces/IInterfaceAPI.php';

class EmpleadoController extends Empleado implements IInterfaceAPI
{
    public static function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $nombre = $params["nombre"];
        $rol = $params["rol"];
        $cant_operaciones = $params["cant_operaciones"];
        $estado= $params["estado"];
        $fecha_alta = $params["fecha_alta"];       

        $empleado = new Empleado();
        $empleado->nombre = $nombre;
        $empleado->rol = $rol;
        $empleado->cant_operaciones = $cant_operaciones;
        $empleado->estado = $estado;
        $empleado->fecha_alta = $fecha_alta;

        Empleado::crearEmpleado($empleado);
        
        $guardadojson = json_encode(array("mensaje" => "Empleado creado con exito!"));
        $response->getBody()->write($guardadojson);
       
        return $response->withHeader("Content-Type","application/json");        
    }

    public static function TraerUno($request, $response, $args)
    {
        
        $id = $args['id'];
        $empleado = Empleado::obtenerEmpleado($id);
        
        $payload = json_encode($empleado);
        $response->getBody()->write($payload);
      
        return $response->withHeader("Content-Type","application/json");    
    }

    public static function TraerTodos($request,$response,$args)
    {      
        $empleados = Empleado::obtenerEmpleados();
        $payload = json_encode(array("listaEmpleados" => $empleados));
        
        $response->getBody()->write($payload);

        return $response->withHeader("Content-Type","application/json");    
    }

    public static function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];

        if (Empleado::obtenerEmpleado($id) != false) 
        {
            Empleado::DarDeBajaEmpleado($id);
            $payload = json_encode(array("mensaje" => "Empleado dado de baja con exito!"));
        } 
        else 
        {    
          $payload = json_encode(array("mensaje" => "No existe empleado con el ID ingresado."));
        }
    
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ModificarUno($request, $response, $args)
    {
        $id = $args['id'];
        $empleado = Empleado::obtenerEmpleado($id);
  
        if ($empleado) 
        {
            $params = $request->getParsedBody();
    
            $actualizado = false;
            if (isset($params['nombre']))
            {
                $actualizado = true;
                $empleado->nombre = $params['nombre'];
            }
            if (isset($params['rol']))
            {
                $actualizado = true;
                $empleado->rol = $params['rol'];
            }
            if(isset($params["cant_operaciones"]))
            {
                $actualizado = true;
                $empleado->cant_operaciones = $params['cant_operaciones'];
            }
            if(isset($params["estado"]))
            {
                $actualizado = true;
                $empleado->estado = $params['estado'];
            }
            if(isset($params["fecha_alta"]))
            {
                $actualizado = true;
                $empleado->fecha_alta = $params['fecha_alta'];
            }          
            if ($actualizado)
            {
                Empleado::modificar($empleado);
                $payload = json_encode(array("mensaje" => "Se ha modificado el emplado con exito!"));
            } 
            else 
            {
                $payload = json_encode(array("mensaje" => "No se pudo modificar al empleado por no ingresar los datos correspondientes!"));
            }    
        } 
        else 
        {
            $payload = json_encode(array("error" => "No existe empleado con el ID ingresado."));
        }
    
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');    
    }
}

?>
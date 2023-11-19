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
        $usuario = $params["usuario"];
        $clave = $params["clave"];
        $rol = $params["rol"];
        $cant_operaciones = $params["cant_operaciones"];
        $estado= $params["estado"];
        $fecha_alta = $params["fecha_alta"];       

        $empleado = new Empleado();
        $empleado->nombre = $nombre;
        $empleado->usuario = $usuario;
        $empleado->clave = $clave;
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
        $response->withHeader('Content-Type', 'application/x-www-form-urlencoded'); 
        $id = $args['id'];
        $body = file_get_contents('php://input');
        parse_str($body, $parametros);
        $empleado = Empleado::obtenerEmpleado($id);
            
        if ($empleado != false) 
        {    
            $empleado->setNombre($parametros['nombre']);
            $empleado->setUsuario($parametros['usuario']);
            $empleado->setClave($parametros['clave']);
            $empleado->setRol($parametros['rol']);
            $empleado->setEstado($parametros['estado']);
            Empleado::ModificarEmpleado($empleado);
    
            $payload = json_encode(array("mensaje" => "Empleado modificado con exito"));
            
        } else {
            $payload = json_encode(array("mensaje" => "Error! El ID ingresado no corresponde a ningun empleado."));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    public static function Login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        $empleado = Empleado::verificarUsuarioClave($usuario, $clave);

        if($empleado != null)
        {
            $data = array('empleado' => $empleado->usuario, 'rol' => $empleado->rol, 'contraseña' => $empleado->clave);
            $creacion = AutentificadorJWT::CrearToken($data);
        }
       
        $payload = json_encode(array("mensaje" => "Bienvenido $empleado->nombre!", "token" => $creacion['jwt']));

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}

?>
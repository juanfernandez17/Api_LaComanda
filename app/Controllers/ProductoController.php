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
        
        if($producto != null)
        {
            $payload = json_encode($producto);
            $response->getBody()->write($payload);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error! El ID ingresado no corresponde a un producto."));
            $response->getBody()->write($payload);
        }
       
      
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
        $response->withHeader('Content-Type', 'application/x-www-form-urlencoded'); 
        $id = $args['id'];
        $body = file_get_contents('php://input');
        parse_str($body, $parametros);
        $producto = Producto::obtenerProducto($id);
            
        if ($producto != false) 
        {    
            $producto->setNombre($parametros['nombre']);
            $producto->setSector($parametros['sector']);
            $producto->setPrecio($parametros['precio']);
            $producto->setCantVendida($parametros['cant_vendida']);        
            Producto::ModificarProducto($producto);
    
            $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
            
        } else {
            $payload = json_encode(array("mensaje" => "Error! El ID ingresado no corresponde a ningun producto."));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    public static function CargarProductosCSV($request, $response, $args)
    {
        $nombreArchivo = './archivoProductos.csv';
        $archivo = fopen($nombreArchivo, "r");      

        try 
        {
            Producto::truncateTable();
        
            while (!feof($archivo)) {

                $linea = fgets($archivo);
              //  echo $linea;
                if (!empty($linea)) 
                {                                     
                    $data = explode(",", $linea);      
                    
                    foreach ($data as &$elemento) {
                        $elemento = str_replace('"', '', $elemento);
                    }
                    $producto = new Producto();
                    $producto->setNombre($data[1]);
                    $producto->setSector($data[2]);
                    $producto->setPrecio($data[3]);
                    $producto->setCantVendida($data[4]);        
                    
                    Producto::crearProducto($producto);                    
                }
                $payload = json_encode(array("mensaje" => "Archivo cargado con exito!"));
            }
        } catch (Exception) {         
            $payload = json_encode(array("mensaje" => "Error al cargar archivo de productos!"));
        }finally{
            fclose($archivo);         
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function DescargarProductosCSV($request, $response, $args)
    {       
        $productos = Producto::obtenerProductos();     
        $nombreArchivo = "./archivoProductos.csv";
        $directorio = dirname($nombreArchivo, 1);
        
        try{
            if(!file_exists($directorio))
            {
                mkdir($directorio);
            }
            $archivo = fopen($nombreArchivo, "w+");
            
            foreach ($productos as $p)
            {            
                fputcsv($archivo, get_object_vars($p));
            }
            $payload = json_encode(array("mensaje" => "Archivo generado con exito!"));
        }
        catch (Expection)
        {
            echo "Error al generar archivo CSV";
            $payload = json_encode(array("mensaje" => "Error al descargar archivo!"));
        }
        finally
        {
            fclose($archivo);
        }       
        $response->getBody()->write($payload);
       
        return $response->withHeader("Content-Type","application/json");        
    }
}
?>
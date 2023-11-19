<?php

require_once "./models/Empleado.php";
require_once "./middlewares/AutentificadorJWT.php";
require_once "./DataBase/DataAccess.php";
require_once "./models/Empleado.php";

class Validador
{

    public static function ValidarNuevoUsuario($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];         
        
        $empleado = Empleado::verificarExistenciaUsuario($usuario);

        if($empleado == null) // El usuario no existe por lo tanto puedo crear uno nuevo 
        {
            return $handler->handle($request);
        }
        else
        {
            throw new Exception("Error! Nombre de usuario ya registrado.");
        }    
    }

    public static function VerificarArchivo($request, $handler)
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (isset($uploadedFiles['csv'])) {
          
            if (preg_match('/\.csv$/i', $uploadedFiles['csv']->getClientFilename()) == 0){
                throw new Exception("Debe ser un archivo CSV");
            }
            
            return $handler->handle($request);
        }
        throw new Exception("Error no se recibio el archivo");
    }
}
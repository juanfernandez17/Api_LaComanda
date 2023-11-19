<?php

require_once "./models/Empleado.php";
class Logger
{
    public static function ValidarLogin($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $empleado = Empleado::verificarUsuarioClave($usuario, $clave);

        if ($empleado != false) {
            return $handler->handle($request);
        }

        throw new Exception("Usuario y/o clave incorrectos!");
    }
}
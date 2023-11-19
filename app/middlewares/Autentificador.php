<?php

require_once "./models/Empleado.php";
require_once './middlewares/AutentificadorJWT.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class Autentificador
{

    public static function ValidarSocio($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            
            if ($data->rol == 'socio') {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Error! No es socio.")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Error! Token inválido!")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ValidarMozo($request, $handler)
    {
        $cookies = $request->getCookieParams();
        $token = $cookies['token'];

        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);


        if ($payload->rol == 'socio' || $payload->rol == 'mozo') {
            return $handler->handle($request);
        }
        throw new Exception("Token no valido");
    }
}
<?php
class Login
{
 	public $usuario;
  	public $password;

/* inicio  especiales para slimFramework*/

 	public function Loguearse($request, $response, $args) {     	
    	$elUsuario=usuario::TraerUnUsuario($);
     	$newResponse = $response->withJson($elUsuario, 200);  
    	return $newResponse;
    }
}
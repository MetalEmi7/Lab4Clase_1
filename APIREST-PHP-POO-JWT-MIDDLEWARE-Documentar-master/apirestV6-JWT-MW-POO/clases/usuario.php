<?php
class usuario
{
	public $id;
 	public $nombre;
  	public $apellido;
  	public $estado;


/* inicio  especiales para slimFramework*/

 	public function TraerUno($request, $response, $args) {
     	$id=$args['id'];
    	$elUsuario=usuario::TraerUnUsuario($id);
     	$newResponse = $response->withJson($elUsuario, 200);  
    	return $newResponse;
    }
     public function TraerTodos($request, $response, $args) {
      	$todosLosUsuarios=usuario::TraerTodoLosUsuarios();
     	$newResponse = $response->withJson($todosLosUsuarios, 200);  
    	return $newResponse;
    }
      public function CargarUno($request, $response, $args) {
     	$response->getBody()->write("<h1>Cargar uno nuevo</h1>");
      	return $response;
    }
      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$id=$ArrayDeParametros['id'];
     	$usuario= new usuario();
     	$usuario->id=$id;
     	$cantidadDeBorrados=$usuario->BorrarUsuario();

     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    if($cantidadDeBorrados>0)
	    	{
	    		 $objDelaRespuesta->resultado="algo borro!!!";
	    	}
	    	else
	    	{
	    		$objDelaRespuesta->resultado="no Borro nada!!!";
	    	}
	    $newResponse = $response->withJson($objDelaRespuesta, 200);  
      	return $newResponse;
    }
     public function ModificarUno($request, $response, $args) {
     	//$response->getBody()->write("<h1>Modificar  uno</h1>");
     	$ArrayDeParametros = $request->getParsedBody();
	    //var_dump($ArrayDeParametros);    	
	    $miUsuario = new usuario();
	    $miUsuario->id=$ArrayDeParametros['id'];
	    $miUsuario->nombre=$ArrayDeParametros['nombre'];
	    $miUsuario->apellido=$ArrayDeParametros['apellido'];
	    $miUsuario->estado=$ArrayDeParametros['estado'];

	   	$resultado =$miUsuario->ModificarUsuarioParametros();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
		return $response->withJson($objDelaRespuesta, 200);		
    }

/* final especiales para slimFramework*/
  	public function BorrarUsuario()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from usuarios 				
				WHERE id=:id");	
				$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }

	public static function BorrarUsuarioPorAnio($estado)
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from usuarios 				
				WHERE estado=:estado");	
				$consulta->bindValue(':estado',$estado, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();

	 }
	public function ModificarUsuario()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuarios 
				set nombre='$this->nombre',
				apellido='$this->apellido',
				estado='$this->estado'
				WHERE id='$this->id'");
			return $consulta->execute();

	 }
	
  
	 public function InsertarElUsuario()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (nombre,apellido,estado)values('$this->nombre','$this->apellido','$this->estado')");
				$consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
				

	 }

	  public function ModificarUsuarioParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuarios 
				set nombre=:nombre,
				apellido=:apellido,
				estado=:estado
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_INT);
			$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
			$consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
			return $consulta->execute();
	 }

	 public function InsertarElUsuarioParametros()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (nombre,apellido,estado)values(:nombre,:apellido,:estado)");
				$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_INT);
				$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
				$consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
				$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }
	 public function GuardarUsuario()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarUsuarioParametros();
	 		}else {
	 			$this->InsertarElUsuarioParametros();
	 		}

	 }


  	public static function TraerTodoLosUsuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id, nombre, apellido ,estado from usuarios");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");		
	}

	public static function TraerUnUsuario($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id, nombre as nombre, apellido as apellido,estado as estado from usuarios where id = $id");
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('usuario');
			return $usuarioBuscado;				

			
	}

	public static function TraerUnUsuarioAnio($id,$estado) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  nombre as nombre, apellido as apellido,estado as estado from usuarios  WHERE id=? AND estado=?");
			$consulta->execute(array($id, $estado));
			$usuarioBuscado= $consulta->fetchObject('usuario');
      		return $usuarioBuscado;				

			
	}

	public static function TraerUnUsuarioAnioParamNombre($id,$estado) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  nombre as nombre, apellido as apellido,estado as estado from usuarios  WHERE id=:id AND estado=:estado");
			$consulta->bindValue(':id', $id, PDO::PARAM_INT);
			$consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('usuario');
      		return $usuarioBuscado;				

			
	}
	
	public static function TraerUnUsuarioAnioParamNombreArray($id,$estado) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  nombre as nombre, apellido as apellido,estado as estado from usuarios  WHERE id=:id AND estado=:estado");
			$consulta->execute(array(':id'=> $id,':estado'=> $estado));
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('usuario');
      		return $usuarioBuscado;				

			
	}

	public function mostrarDatos()
	{
	  	return "Metodo mostar:".$this->nombre."  ".$this->apellido."  ".$this->estado;
	}

}
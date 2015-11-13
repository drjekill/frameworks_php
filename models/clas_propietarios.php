<?php
include_once('db.php');
class propietarios extends db {
	var $departamento;
	var $unidad;
	var $nombre;
	var $apeliido;
	var $email;
	var $telefono;
	var $telefono_alternativo;
	var $contraceña;
	var $contraceña_sin_encriptar;
	function  nuevo_propietario($var)
	{
		try {	
		$departamento = $this->departamento;
		$unidad = $this->unidad;
		$nombre = $this->nombre;
		$apeliido = $this->apeliido;
		$email =$this->email;
		$telefono=$this->telefono;
		$telefono_alternativo=$this->telefono_alternativo;
		$contraceña = $this->contraceña;
		$value="'".$departamento."','".$unidad."','".$nombre."','".$apeliido."','".$email."','".$telefono."','".$telefono_alternativo."','".$contraceña."'";
		if($this->insertar('vielmi_propietarios','id_departamento,unidad,nombre,apellido,email,telefono,telefono_alternativo,pass',$value)== true)
		{
		    $destino = $email;
			$asunto="Alta  correctamente en el sistema de vielmi";
			$cuerpo="<center><p>Usted se dio de alta correctamente en el nuevo sistema de Propietarios de vielmi <br/>
			Recuerde que su Usuario es :".$unidad."<br/>
			y su Password es :".$this->contraceña_sin_encriptar." <br/>
			Muchas gracias por usar el sistema de Vielmi Recuerde que solo puede darse de alta solo un departamento</p></center>";
			$cabeceras ="From: info@vielmi.com.ar \r\nContent-type: text/html\r\n";
			mail( $destino,$asunto,$cuerpo,$cabeceras);
			if($var=="m788m"){
			header ("Location:../adminprop/usuarios.php");	
			}else {
			header ("Location:registrarse_ok.php?id_dpto=$var");
			}
		}
		else 
		{
			throw new Exception();
		}
		}
		catch (Exception $e)
		{
		  echo "<h1>Error Usuario duplicado</h1>"; $e->getMessage();
		}


	}
    function modificar_articulo($id,$unidad,$nombre,$apellido,$email,$telefono,$telefono_alternativo,$pass,$consejo,$id_departamento)
	{
		if(empty($pass))
		{ 
		$campos="unidad='$unidad',nombre='$nombre',apellido='$apellido',email='$email',telefono='$telefono',telefono_alternativo ='$telefono_alternativo',pconsejo = '$consejo' ,id_departamento = '$id_departamento'";
		}
		else 
		{
		 $campos="unidad='$unidad',nombre='$nombre',apellido='$apellido',email='$email',telefono='$telefono',telefono_alternativo ='$telefono_alternativo',pconsejo = '$consejo',pass = '$pass',id_departamento = '$id_departamento'";	
		}
		echo $campos;
		$this->actualizar('vielmi_propietarios',$campos,$id);
		header ("Location:../adminprop/usuarios.php");
	
		}
}	

?>
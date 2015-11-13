<?php
include_once('db.php');
class admin extends db {
	var $departamento;
	var $nombre;
	var $email;
	var $contraceña;
	var $sector;
	var $contraceña_sin_encriptar;
	function  nuevo_adm()
	{
		try {	
		$value="'".$this->nombre."','".$this->contraceña."','".$this->email."','".$this->departamento."','".$this->sector."'";
		if($this->insertar('vielmi_usuarios_adm','usuario,pass,email,id_departamento,sector',$value)== true)
		{
		    $destino = $email;
			$asunto="Alta  correctamente en el sistema de vielmi";
			$cuerpo="<center><p>Usted se dio de alta correctamente en el nuevo sistema de Propietarios de vielmi <br/>
			Recuerde que su Usuario es :".$nombre."<br/>
			y su Password es :".$this->contraceña_sin_encriptar." <br/>
			Muchas gracias por usar el sistema de Vielmi Recuerde que solo puede darce de alta solo un departamento</p></center>";
			$cabeceras = "From: info@vielmi.com.ar \r\nContent-type: text/html\r\n";
			mail( $destino,$asunto,$cuerpo,$cabeceras);
			header ("Location:menu.php");
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
	function  edit_adm($id)
	{
		if(!empty($this->contraceña)){
				$campos="usuario='$this->nombre',pass='$this->contraceña',email='$this->email',id_departamento='$this->departamento',sector='$this->sector'";	
			}else{
				$campos="usuario='$this->nombre',email='$this->email',id_departamento='$this->departamento',sector='$this->sector'";	
			}
			$this->actualizar('vielmi_usuarios_adm',$campos,$id);
			header ("Location:menu.php");

	}
}
?>
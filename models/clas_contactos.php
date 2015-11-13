<?php
include_once('db.php');
class contactos extends  db 
{
	var $destino;
	var $fecha;
	var $descripcion;
	var $detalles;
	var $bandera;
	var $id_usuario;
	
	function  nueva_consulta ()
	{
	$values="'".$this->id_usuario."','".$this->fecha."','".$this->destino."','".$this->descripcion."','".$this->detalles."','".$this->bandera."'";
	$this->insertar('viemli_contactos','id_usuario,fecha,destino,descripcion,detalle,bandera',$values);
	$this->envio_mail($this->id_usuario,$this->destino);
	header("location:contacto.php");
	}
	
	
	function  respuesta($id_reclamo,$fecha,$respuesta,$id_usuario,$bandera)
	{
	$values="'".$id_reclamo."','".$fecha."','".$respuesta."','".$id_usuario."'";
    $this->insertar('vielmi_respuesta_contactos','id_reclamo,fecha,respuesta,usuarios',$values);
    $campos="bandera =".$bandera;
    $this->actualizar('viemli_contactos',$campos,$id_reclamo);
	//header ("Location:contacto_detalle.php?id=$id_reclamo");	

	header("location:contacto.php");	
	}
	
	
	
	function envio_mail($id_usuario,$sector)
	{
	$id_departamento=$this->unicoDato('id_departamento','vielmi_propietarios',$id_usuario,"id");	
	$destino=$this->unicoDato('email','vielmi_usuarios_adm',$id_departamento,"sector ='$sector' and  id_departamento ");
	$titulo="Nuevo mensaje";
	$this->select('nombre,apellido','vielmi_propietarios',$condicion1);
	$nombre=$this->fetch();
	$nombres=$nombre['nombre']." ".$nombre['apellido'];
    
	$cuerpo="<center><p>Tiene un nuevo mensaje de: <b>".$nombres."</b><br/> , para leerlo, ingrese al sistema.
			    <a href=\"http://propietarios.administracionvielmisa.com\">http://propietarios.administracionvielmisa.com</a></p></center>";
	
	$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
	
	mail( $destino,$titulo,$cuerpo,$cabeceras);
	}
	
	
	
	function mail_respuesta($id_usuario,$sector=false){
		if(empty($sector))
		{
		$destino=$this->unicoDato('email','vielmi_propietarios',$id_usuario,"id");
		
		}
		else {
	$id_departamento=$this->unicoDato('id_departamento','vielmi_propietarios',$id_usuario,"id");	
	$destino=$this->unicoDato('email','vielmi_usuarios_adm',$id_departamento,"sector ='$sector' and  id_departamento ");
	}
	$destino;
	$titulo="Nuevo mensaje";
	$cuerpo="<center><p>Tiene un nuevo mensaje<br/> , para leerlo, ingrese al sistema.
			    <a href=\"http://propietarios.administracionvielmisa.com\propietarios\">http://www.administracionvielmisa.com</a></p></center>";
	
	$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
	
	mail( $destino,$titulo,$cuerpo,$cabeceras);
		
	}
	
}
?>
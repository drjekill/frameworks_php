<?php
include_once('db.php');
class seguridad extends  db 
{
	var $destino;
	var $fecha;
	var $descripcion;
	var $detalles;
	var $id_edificio;
	var $bandera;
	var $id_usuario;
	
	function  nueva_consulta ()
	{
	$values="'".$this->id_usuario."','".$this->fecha."','".$this->destino."','".$this->descripcion."','".$this->detalles."','".$this->bandera."','".$this->id_edificio."'";
	$this->insertar('viemli_seguridad','id_usuario,fecha,destino,descripcion,detalle,bandera,id_departamento',$values);
	$this->envio_mail($this->id_usuario,$this->descripcion,$this->detalles);
	header("location:seguridad.php");
	}
	
	
	function  respuesta($id_reclamo,$fecha,$respuesta,$id_usuario,$bandera)
	{
	$values="'".$id_reclamo."','".$fecha."','".$respuesta."','".$id_usuario."'";
    $this->insertar('vielmi_respuesta_seguridad','id_reclamo,fecha,respuesta,usuarios',$values);
    
  //  $campos="bandera =".$bandera;
   // $this->actualizar('viemli_seguridad',$campos,$id_reclamo);
	//header ("Location:contacto_detalle.php?id=$id_reclamo");	

	header("location:seguridad.php");	
	}
	
	
	
	function envio_mail($id_usuario,$titulo,$detalle)
	{
	$id_departamento=$this->unicoDato('id_departamento','vielmi_propietarios',$id_usuario,"id");	
	$destino=$this->unicoDato('email','vielmi_usuarios_adm',$id_departamento,"sector ='admin' and  id_departamento ");
	$titulo="".$titulo."";
	$condicion1="id = $id_usuario";
	$this->select('nombre,apellido','vielmi_propietarios',$condicion1);
	$nombre=$this->fetch();
	$nombres=$nombre['nombre']." ".$nombre['apellido'];
    
	$cuerpo="<center><p>Tiene un nuevo Denuncia de: <b>".$nombres."</b><br/> , $detalle
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
<?php
class cartelera extends db 
{
	var $id_usuario;
	var $fecha;
	var $detalle;
	var $aprobado;
	var $id_departamento;
 function  alta_cartelera ($autorizacion = false)
 { 
 	$values="'".$this->id_usuario."','".$this->fecha."','".$this->detalle."','".$autorizacion."','".$this->id_departamento."'";
 	$this->insertar('vielmi_cartelera','usuario,fecha,detalle,uatorizacion,id_edificio',$values);
 	$this->enviar_mail($this->id_usuario,$this->id_departamento);
 	header("location:anuncio_ok.php");
 	 	
 }
 function autorizar($id)
 {
 	$campos="uatorizacion = '1'";
 	$this->actualizar('vielmi_cartelera',$campos,$id);
 	header("location:anuncios_scroll.php");
 }
 
 function eliminar_cartelera($id)
 {
 	$this->eliminar('vielmi_cartelera',$id,"id");
 	header("location:anuncios_scroll.php");
 }
 
  
 function  enviar_mail($id_usuario,$id_departamento)
	{
			$titulo="nuevo anuncio para aprobar";
			$condicion1="id = ".$id_usuario;
			$this->select('nombre,apellido','vielmi_propietarios',$condicion1);
			$nombre=$this->fetch();
			$nombres=$nombre['nombre']." ".$nombre['apellido'];
		  $condicion= "id_departamento =".$id_departamento." and sector = 'admin' or sector = 'intendente'";
			$this->select('email,id','vielmi_usuarios_adm',$condicion);
			while ($dconsejo=$this->fetch())
			{
			    $cuerpo="<center><p>El Propietario : <b>".$nombres."</b><br/>ha creado un nuevo anuncio.
				<br/> , Ingrese al sistema para realizar su voto <br/>
			    <a href=\"http://http://www.administracionvielmisa.com\">http://www.administracionvielmisa.com</a></p></center>";
				$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
	     	    mail( $dconsejo['email'],$titulo,$cuerpo,$cabeceras);
			
			}
		
		
	}
	
  function enviar_mail_rechazo($id_usaurio,$id_propuesta)
	{
		$destino=$this->unicoDato('email','vielmi_propietarios',$id_usaurio,"id");
		$propuesta=$this->unicoDato('descripcion','vielmi_propuestas',$id_propuesta,"id");
        $titulo="Votacion rechazada";
		$cuerpo="<center><p>La administración ha rechazado la siguiente votacion</b><br/>
				Votacion:".$propuesta."<br/> , Ingrese al sistema para realizar su voto.
			    <a href=\"http://www.administracionvielmisa.com\">http://www.administracionvielmisa.com</a></p></center>";
	
	$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
	mail( $destino,$titulo,$cuerpo,$cabeceras);
   }
   
}

?>
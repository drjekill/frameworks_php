<?php
include_once('db.php');
class reclamos extends db {
	var $id_departamento;
	var $fecha;
	var $id_tipo;
	var $descripcion;
	var $id_estado;
	var $id_usuario;
	var $detalle;
	
	function mail_admin($id,$id_usuario,$id_departamento,$sector)
	{

	$destino=$this->unicoDato('email','vielmi_usuarios_adm',$id_departamento,"sector ='$sector' and  id_departamento ");
	$dato=$this->unicoDato('descripcion','vielmi_reclamos',$id,"id");
	$asunto="Nuevo Reclamo:".$dato;
	$condicion="id = ".$id_usuario;
	$usuario=$this->select('nombre,apellido','vielmi_propietarios',$condicion);
	$valor=$this->fetch();
	
	$cuerpo="<center><p>El usuario : <b>".$valor['nombre']." ".$valor['apellido']."</b><br/>ha creado un reclamo, ingrese en el sistema para revisarlo.
    <a href=\"http://www.administracionvielmisa.com\">http://www.administracionvielmisa.com</a></p></center>";
	
	$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
	if($id_departamento=='73' && $sector=='admin'){
	$destino='gcaratti@administracionvielmisa.com; intendencialeparcpuertomadero@gmail.com; leparcmadero@administracionvielmisa.com';
	}
    mail( $destino,$asunto,$cuerpo,$cabeceras);
	}
	
	
	
	
	
	function mail_respuesta($id,$id_usuario,$id_edificio,$id_respuesta,$sector)
	{
	         $usuario=$this->unicoDato('id_usaurio','vielmi_reclamos',$id,"id");
		 $mail_usuario=$this->unicoDato('email','vielmi_propietarios',$usuario,"id");
		 $administrador=$this->unicoDato('email','vielmi_usuarios_adm',$id_departamento,"id_departamento and sector ='admin'");
		 $intendente=$this->unicoDato('email','vielmi_usuarios_adm',$id_departamento,"id_departamento and sector ='intendente'");
		
		switch($sector)
		{
		case  'intendente':
		$destino2=$mail_usuario;
		$destino1=$administrador;
		$creador=$id_usuario;
		break;
		case 'admin':
		$destino1=$mail_usuario;
		$destino2=$intendente;
		$creador=$id_usuario;
		break;
		default:
		$destino1=$administrador;
		$destino2=$intendente;
		$creador=$id_usuario;	
		break;
        }
		
		
		$dato=$this->unicoDato('descripcion','vielmi_reclamos',$id,"id");
		$titulo=$asunto="Reclamo:".$dato;
		
		$respuesta=$this->unicoDato('respuesta','vielmi_respuesta_reclamos',$id_respuesta,"id");
		$cuerpo="<center><p>".$creador." Ha respondido su reclamo: <b></b><br/>".$respuesta."<br/> Para responder este reclamo, ingrese en el sistema.
        <a href=\"http://www.administracionvielmisa.com\">http://www.administracionvielmisa.com</a></p></center>";
		
		$destino=$destino1.";".$destino2;
		$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
		
	   mail( $destino,$titulo,$cuerpo,$cabeceras);
				
	}

	
	
	function alta_reclamos()
	{
		$id_departamentos = $this->id_departamento;
		$fecha = $this->fecha;
		$id_tipo = $this->id_tipo;
		$descripcion=$this->descripcion;
		$id_usuario=$this->id_usuario;
		$detalle=$this->detalle;
		
		$values="'".$id_departamentos."','".$fecha."','".$id_tipo."','".$descripcion."','".$id_usuario."','".$detalle."'";
	    $id= $this->insertar('vielmi_reclamos','id_departamento,fecha,id_tipo,descripcion,id_usaurio,detalle',$values);
	    $this->mail_admin($id,$id_usuario,$id_departamentos,'admin');
        header ("Location:reclamo_ok.php");
	}
	
	
	
	function  respuesta($id_reclamo,$fecha,$respuesta,$id_usuario,$sector,$id_departamento,$archivo)
	{
	$values="'".$id_reclamo."','".$fecha."','".$respuesta."','".$id_usuario."','".$archivo."'";
    $id=$this->insertar('vielmi_respuesta_reclamos','id_reclamo,fecha,respuesta,usuarios,archivo',$values);
    $this->mail_respuesta($id_reclamo,$id_usuario,$id_departamento,$id,$sector);
    
	header ("Location:reclamo_ok.php");	
		
	}
	function  reclamo_intendente($id)
	{
		if($this->id_estado==1)
		{
			$id_usuario=$this->unicoDato('id_usaurio','vielmi_reclamos',$id,"id");
			$destino=$this->unicoDato('email','vielmi_propietarios',$id_usuario,"id");
			$asunto="Reclamo cerrado";
			$dato=$this->unicoDato('descripcion','vielmi_reclamos',$id,"id");
			$cuerpo="<center><p>Su Reclamo. : <b>".$dato."</b><br/>  Ha sido Cerrado</p></center>";
			$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
			mail( $destino,$asunto,$cuerpo,$cabeceras);
			
		}
		$campos="id_estado = '$this->id_estado'";
		$this->actualizar('vielmi_reclamos',$campos,$id);
		header ("Location:reclamos.php");
	}
}
?>
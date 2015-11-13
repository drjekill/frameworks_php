<?php
include_once('db.php');
class propuestas extends db {
 var $fecha_fin;
 var $sector;
 var $descripcion;
 var $autorizacion;
 var $id_usuario;
 var $id_departamento;
 var $opciones;
 var $archivo;
 var $id;
 
 function nueva_propuesta()
 {
 	$autorizado=intval($this->autorizacion);
 	$values="'".$this->fecha_fin."','".$this->sector."','".$this->descripcion."','".$this->id_usuario."','".$this->id_departamento."','".$autorizado."'";
 	$id= $this->insertar('vielmi_propuestas','fecha_fin,sector,descripcion,id_usuario,id_depto,autorizado',$values);
 	$valor='consejo';
 	$opciones=explode(",",$this->opciones);
 	for ($i=0;$i<count($opciones);$i++){
 		$values_opciones="'".$id."','".$opciones[$i]."'";
 		$this->insertar('vielmi_opciones_votacion','id_reclamo,nombre',$values_opciones);
 	}
 	
 	if(!empty($this->archivo)){
 		$values_archivo="'".$id."','".$this->archivo."'";
 		$this->insertar('vielmi_archivo_votacion','id_propuesta,archivo',$values_archivo);
 	}
 	$this->enviar_mail($this->sector,$this->id_usuario,$this->id_departamento,$id,$voto=false,$this->descripcion,$this->fecha_fin);
 	header("location:votacion_ok.php?sector=$valor");
 }
 
 
 
 
 function  enviar_mail($sector,$id_usuario,$id_departamento,$id,$voto=false,$descripcion,$fecha_fin)
	{
			$titulo="Nueva Votacion ".$sector;
			$condicion1="id = ".$id_usuario;
			$this->select('nombre,apellido','vielmi_propietarios',$condicion1);
			$nombre=$this->fetch();
			$nombres=$nombre['nombre']." ".$nombre['apellido'];
			$propuesta=$descripcion;
			$fecha_vencimiento=$fecha_fin;
		    $administrador=$this->unicoDato('email','vielmi_usuarios_adm',$id_departamento,"sector ='admin' and id_departamento");
			
			if($sector=='consejo' or $voto=='aceptada')
			{
		      $valor= "= 1";			

		      if ($voto =='aceptada'){
				 $valor=" >= 0";}
			
			
			$condicion= "id_departamento =".$id_departamento." and pconsejo  $valor";
			$this->select('email,id','vielmi_propietarios',$condicion);
			while ($dconsejo=$this->fetch())
			{
			    $cuerpo="<center><p>El Propietario : <b>".$nombres."</b><br/>ha creado una nueva votacion.
				Votacion:".$propuesta."<br/> Vencimiento :".$fecha_vencimiento."<br/> , Ingrese al sistema para realizar su voto.
			    <a href=\"http://propietarios.administracionvielmisa.com\">http://propietarios.administracionvielmisa.com</a></p></center>";
				$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
	     	  mail( $dconsejo['email'],$titulo,$cuerpo,$cabeceras);
			
			}
			}
			
				$cuerpo="<center><p>El Propietario : <b>".$nombres."</b><br/>ha creado una nueva votacion.
				Votacion:".$propuesta."<br/> Vencimiento :".$fecha_vencimiento."<br/> , Ingrese al sistema para realizar su voto.
				<a href=\"http://propietarios.administracionvielmisa.com\">http://propietarios.administracionvielmisa.com</a></p></center>";
			    $cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
				mail( $administrador,$titulo,$cuerpo,$cabeceras);
		
		
	}
	
 function enviar_mail_rechazo($id_usaurio,$id_propuesta)
	{
		$destino=$this->unicoDato('email','vielmi_propietarios',$id_usaurio,"id");
		$propuesta=$this->unicoDato('descripcion','vielmi_propuestas',$id_propuesta,"id");
        $titulo="Votacion rechazada";
		$cuerpo="<center><p>La administración ha rechazado la siguiente votacion</b><br/>
				Votacion:".$propuesta."<br/> , Ingrese al sistema para realizar su voto.
			    <a href=\"http://propietarios.administracionvielmisa.com\">http://propietarios.administracionvielmisa.com</a></p></center>";
	
	$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
	mail( $destino,$titulo,$cuerpo,$cabeceras);
   }
   
 function  modi_votacion($id){
 	
	$campos="fecha_fin='$this->fecha_fin',sector='$this->sector',descripcion='$this->descripcion',autorizado='$this->autorizacion',id_usuario='$this->id_usuario',id_depto='$this->id_departamento'";
	$this->actualizar('vielmi_propuestas',$campos,$id);
	$opciones=explode(",",$this->opciones);
	
	//$sql="delete from vielmi_opciones_votacion where id_reclamo = $id";
	//$this->query($sql);	 
	
	//$opciones=explode(",",$this->opciones);
 //	for ($i=0;$i<count($opciones);$i++){
 	//	$values_opciones="'".$id."','".$opciones[$i]."'";
 		//$this->insertar('vielmi_opciones_votacion','id_reclamo,nombre',$values_opciones);
 	//}
	 	
	 if(!empty($this->archivo)){
 		$campos_archivo="archivo='$this->archivo'";
 		$this->actualizar('vielmi_archivo_votacion',$campos_archivo,$id);
 	}	
 	$this->enviar_mail($this->sector,$this->id_usuario,$this->id_departamento,$id,$voto=false,$this->descripcion,$this->fecha_fin);
 	header("location:votaciones.php");
 }
 	
}
?>
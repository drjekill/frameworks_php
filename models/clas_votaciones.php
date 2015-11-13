<?php
include_once('db.php');
class votaciones extends db {
var $id_propuesta;
var $id_usuario;
var $votacion;
function nueva_votacion()
{
	$values="'".$this->id_propuesta."','".$this->id_usuario."','".$this->votacion."'";
	$this->insertar('vielmi_votaciones','id_propuesta,id_usuario,votacion',$values);
    header("location:votaciones.php");
}
	function aceptar($id)
	{
		$campos="autorizado = '1'";
		$this->actualizar('vielmi_propuestas',$campos,$id);
		header("location:votaciones.php");
		
	}
	
	
function selectopciones($id){
	$html="<select name=\"opciones\">
			<option value=\"0\" selected>Seleccione</option>";
	$condiciones="id_reclamo=$id";
	$this->select('id,nombre','vielmi_opciones_votacion',$condiciones);
	while ($row=$this->fetch()) {
		$html.="<option value=\"".$row['id']."\">".$row['nombre']."</option>";
	}
	$html.="</select>";
	return $html;
}
	
	
}


?>
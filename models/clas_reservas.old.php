<?php
class adm_reservas extends db 
{
	var $nombre;
	var $id_usaurio;
	var $id_edificio;
	var $choras;
	var $periodo;
	var $diasxperiodo;
	var $fecha;
	var $hora_ini;
	var $hora_fin;
	var $id_espacio;
	var $id_reserva;
	var $archivo;
	
	function alta_reserva()
	{   
		$cantidad=$this->choras;
	echo 	$values="'".$this->id_edificio."','".$this->nombre."','".$this->choras."','".$this->periodo."','".$this->diasxperiodo."'";
		$id = $this->insertar('vielmi_reservas_espacios','id_departamento,nombre_espacio,Cant_horas,periodo,cant_priodo',$values);
		if(!empty($this->archivo))
		{
			$values="'".$this->id_edificio."','".$id."','".$this->archivo."'";
			$this->insertar('vielmi_reglamento','id_departamento,id_espacio,archivo',$values);
		}
 	    header("location:reservas.php");
	}
	
	
	function modificar_reserva(){
		$campos="nombre_espacio='".$this->nombre."',Cant_horas = '".$this->choras."', periodo='".$this->periodo."',cant_priodo='".$this->diasxperiodo."'";
		$this->actualizar('vielmi_reservas_espacios',$campos,$this->id_reserva);
		header("location:reservas.php");	
			if(!empty($this->archivo))
		{
			$this->eliminar('vielmi_reglamento',$this->id_reserva,'id_espacio');
			$values="'".$this->id_edificio."','".$this->id_reserva."','".$this->archivo."'";
			$this->insertar('vielmi_reglamento','id_departamento,id_espacio,archivo',$values);
		}
 	    header("location:menu.php");
	}
	
	
	function  listar_Espacios($id_departamento)
	{
	$condicion="id_departamento =".$id_departamento; 
   $rsx=$this->select('id,nombre_espacio','vielmi_reservas_espacios',$condicion);
   
 $html="<select name=\"sector\" onchange=\"xajax_generar_select(document.formulario.sector.options[document.formulario.sector.selectedIndex].value)\"> <option selected>Seleccione</option>";
	    
 		while ($valores=$this->fetch($rsx)) 
	    {
	     $html.="<option value=\"".$valores['id']."\">".$valores['nombre_espacio']."</option>";	
	     } 
		$html.="</select>";
		echo $html; 
		 	 
	}
	function compara_fechas($fecha1,$fecha2)
            
{
            
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
            
              list($dia1,$mes1,$año1)=split("/",$fecha1);
            
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
            
              list($dia1,$mes1,$año1)=split("-",$fecha1);
        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
            
              list($dia2,$mes2,$año2)=split("/",$fecha2);
            
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
            
              list($dia2,$mes2,$año2)=split("-",$fecha2);
        $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
        return ($dif);                         
            
}

	function permiso($id_usuario,$sector,$fecha)
	{		
	$sql="select id_usuario from vielmi_reservas_pedido where id_espacios = $sector and id_usuario = $id_usuario and fecha > '".date("Y-n-j")."'";
	$rs=$this->query($sql);
    $cdeveces=$this->unicoDato('cant_priodo','vielmi_reservas_espacios',$sector,"id");
	$cant=$this->cuantasFilas($rs);
	if($cdeveces >= $cant)
	{
		$periodo=$this->unicoDato('periodo','vielmi_reservas_espacios',$sector,"id");
		$fecha2 = mktime(0,0,0,date("m"),date("d")+$periodo,date("Y"));
		$fecha_final = date("d-m-Y", $fecha2);
		$fecha1=explode("-",$fecha);
		$fecha_pedido=$fecha1[2]."-".$fecha1[1]."-".$fecha1[0];
        if ($this->compara_fechas($fecha_final,$fecha_pedido) >= 0)
        {
	    return true;
        }
        else 
        {
        	return  false;
        }
	}else {
		return false;
	}
		
	}
	
	function validar_hora ($id_edificio,$fecha,$horainicial,$horafinal,$espacios)
	{
	 	
	     $sql="SELECT id,horas_fin,horas_ini  FROM vielmi_reservas_pedido WHERE fecha = '$fecha' AND id_espacios = '$espacios' and  horas_ini <= $horainicial ORDER BY horas_ini  DESC LIMIT 1";
	 	$rs=$this->query($sql);
	 	$cant=$this->cuantasFilas($rs);
	 	if($cant==1)
	 	{
	 		$dato=$this->fetch($rs);
	 	if($dato['horas_fin']<= $horainicial)
	 	{
	 		$sql_1="SELECT id,horas_fin,horas_ini  FROM vielmi_reservas_pedido WHERE fecha = '$fecha' AND id_espacios = '$espacios' and  horas_ini >= $horainicial ORDER BY horas_ini  ASC LIMIT 1";
	 		$horasDereserva=$this->unicoDato('Cant_horas','vielmi_reservas_espacios',$espacios,"id");
	 		$rs_1=$this->query($sql_1);
	 		$row=$this->fetch($rs_1);
	 		$horai=$horainicial+$horasDereserva;
	 		
		 		if($horai <= $row['horas_ini']){
		 			return true;
		 		}else {
		 				
		 			return false;
		 		}
	 		
	 	}
	 		else {
	 		    
	 			return false;
	 		}
	 	 		
	 	}
	 	else 
	 	{
	 		$sql_1="SELECT id,horas_fin,horas_ini  FROM vielmi_reservas_pedido WHERE fecha = '$fecha' AND id_espacios = '$espacios' and  horas_ini >= $horainicial ORDER BY horas_ini  ASC LIMIT 1";
	 		$rs_1=$this->query($sql_1);
	 		$row=$this->fetch($rs_1);
	 		$horasDereserva=$this->unicoDato('Cant_horas','vielmi_reservas_espacios',$espacios,"id");
	 	 	$horaini=$row['horas_ini'] + $horasDereserva;
	  		$horaini_new=$horainicial+$horasDereserva;
	 	   	$periodo=$horaini - $horaini_new;
	 		if($periodo<$horasDereserva and $this->cuantasFilas($rs_1)!=0){
	 			
	 			return false;
	 		}else{
	 		$sql="SELECT id,horas_fin,horas_ini  FROM vielmi_reservas_pedido WHERE fecha = '$fecha' AND id_espacios = '$espacios' and  horas_fin <= $horafinal ORDER BY horas_ini  DESC LIMIT 1";
	 		$rs=$this->query($sql);
	 		$cant=$this->cuantasFilas($rs);
			if(empty($cant)){
			return true;	
			}
	 		}
	 	}
	 	
	 	
	 	
	 	
	 	
	 	$sql="SELECT id,horas_fin,horas_ini  FROM vielmi_reservas_pedido WHERE fecha = '$fecha' AND id_espacios = '$espacios' AND horas_ini >= $horainicial ORDER BY horas_ini  DESC LIMIT 1";
	 	$rs=$this->query($sql);
	 	$cant1=$this->cuantasFilas($rs);
	 	if($cant1==1)
	 	{
	 		$dato=$this->fetch($rs);
	 	if($dato['horas_ini']>=$horafinal)
	 	{
	 		return true;
	 	}
	 		else {
	 			echo "no3";
	 			return false;
	 		}
	 	 		
	 	}
	 	else 
	 	{
	 		if($valor1==0)
	 		{
	 			return true;
	 			
	 		}
	 	}
	 	
	 	
	 	
	}
	
	function pedir_reserva(){
       
	if($this->permiso($this->id_usaurio,$this->id_espacio,$this->fecha))
	{		
		if($this->validar_hora($this->id_edificio,$this->fecha,$this->hora_ini,$this->hora_fin,$this->id_espacio))
		{
			
     	$values="'".$this->id_edificio."','".$this->id_usaurio."','".$this->id_espacio."','".$this->fecha."','".$this->hora_ini."','".$this->hora_fin."'";
		$idr=$this->insertar('vielmi_reservas_pedido','id_departamento,id_usuario,id_espacios,fecha,horas_ini,horas_fin',$values);
		$this->generar_vaucher($this->id_usaurio,$idr);
		
          }
		else 
		{
		header("location:alta_de_reservas.php?error='SI'");
		}
		
	}else {
		header("location:alta_de_reservas.php?errorpermiso='SI'");
	}

	
	}
	
	function generar_vaucher($id_usuario,$id_reserva)
	{
		header("location:vauher.php?id_usu=$id_usuario&id_reserva=$id_reserva");
	}
	
}
?>
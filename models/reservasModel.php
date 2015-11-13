<?php
class reservasModel extends baseModel 
{
	var $nombre;
	var $id_usaurio;
	var $id_edificio;
	var $comentarios;
	var $choras;
	var $periodo;
	var $diasxperiodo;
	var $fecha;
	var $hora_ini;
	var $hora_fin;
	var $id_espacio;
	var $id_reserva;
	var $archivo;
	var $pagina;
	var $mail_envio;
	var $importe;
	

	function get_reservas($id_departamento){
		
		$condicion="id_departamento =".$id_departamento; 
		$rs = $this->select('id,nombre_espacio','vielmi_reservas_espacios',$condicion);
		$valores = $this->fetch($rs);
		return $valores;
	}

	function alta_reserva()
	{   
		$cantidad=$this->choras;
		$values="'".$this->id_edificio."','".$this->nombre."','".$this->comentarios."','".$this->choras."','".$this->periodo."','".$this->diasxperiodo."','".$this->mail_envio."','".$this->importe."'";
		$id = $this->insertar('vielmi_reservas_espacios','id_departamento,nombre_espacio,comentarios,Cant_horas,periodo,cant_priodo,envio_email,importe',$values);
		if(!empty($this->archivo))
		{
			$values="'".$this->id_edificio."','".$id."','".$this->archivo."'";
			$this->insertar('vielmi_reglamento','id_departamento,id_espacio,archivo',$values);
		}
 	    header("location:reservas.php");
	}
	
	
	function modificar_reserva(){
		
		$campos="nombre_espacio='".$this->nombre."',comentarios = '".$this->comentarios."',Cant_horas = '".$this->choras."', periodo='".$this->periodo."',cant_priodo='".$this->diasxperiodo."',envio_email='".$this->mail_envio."',importe='".$this->importe."'";
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
	
	function envio_mail($id_usuario,$id_espacio,$id,$titulo)
	{
	$id_departamento=$this->unicoDato('id_departamento','vielmi_propietarios',$id_usuario,"id");	
	$destino=$this->unicoDato('email','vielmi_usuarios_adm',$id_departamento,"sector ='admin' and  id_departamento ");
	$titulo=htmlentities($titulo);
	$condicion1="id = $id_usuario";
	$this->select('nombre,apellido','vielmi_propietarios',$condicion1);
	$nombre=$this->fetch();
	$nombres=$nombre['nombre']." ".$nombre['apellido'];
    $cuerpo="<center><p><b>".$nombres."</b>&nbsp; <b>".$titulo."</b><br/> , <strong> De:".$this->unicoDato('nombre_espacio','vielmi_reservas_espacios',$id_espacio,'id')."</strong><br/>
    <center><b>El :</b>".$this->Cambiar_fecha($this->unicoDato('fecha','vielmi_reservas_pedido',$id,'id'))."<b>Desde :</b>".$this->unicoDato('horas_ini','vielmi_reservas_pedido',$id,'id')."<b>  Hasta :</b>".$this->unicoDato('horas_fin','vielmi_reservas_pedido',$id,'id')."
			  <br/><a href=\"http://propietarios.administracionvielmisa.com\">http://propietarios.administracionvielmisa.com</a></p></center>";
	$cabeceras = "From: sistema@administracionvielmisa.com \r\nContent-type: text/html\r\n";
	
	mail( $destino,$titulo,$cuerpo,$cabeceras);
	}
	
	function  listar_Espacios($id_departamento)
	{
	$condicion="id_departamento =".$id_departamento; 
 	  $rsx=$this->select('id,nombre_espacio','vielmi_reservas_espacios',$condicion);
   
		 $html="<select name=\"sector\" onchange=\"xajax_generar_select(document.formulario.sector.options[document.formulario.sector.selectedIndex].value),xajax_generar_horas(document.formulario.sector.options[document.formulario.sector.selectedIndex].value),xajax_generar_informes(document.formulario.sector.options[document.formulario.sector.selectedIndex].value)\"> <option selected>Seleccione</option>";
	    
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

	function permiso($id_usuario,$sector,$fecha){

		$sql="select id_usuario from vielmi_reservas_pedido where id_espacios = $sector and id_usuario = $id_usuario and fecha >= '".date("Y-n-j")."'";
		$rs=$this->query($sql);
	    $cdeveces=$this->unicoDato('cant_priodo','vielmi_reservas_espacios',$sector,"id");
		$cant=$this->cuantasFilas($rs);
			        		$debag['cantidad de veces']=$cdeveces;
		$debag['cant alquiladas']=$cant;
		$debag['usuarios']=$id_usuario;
		$debag['sector']=$sector;
		$debag['fecha']=$fecha;
		$debag['periodo']=$periodo;
		$debag['fecha2']=$fecha2;
		$debag['fechacomparada']=$fechacomparada;
		$debag['sql']=$sql;
		
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
		}else 
			{

				return false;
			}
		
	}
	
	function validar_hora ($fecha,$horainicial,$horafinal,$espacios)
	{
		$hora_reservada=$horainicial - $horasDereserva=$this->unicoDato('Cant_horas','vielmi_reservas_espacios',$espacios,"id");
		$hora1=floatval($hora_reservada);
		$hora2=floatval($horafinal);
	    $sql="select * from vielmi_reservas_pedido where horas_ini > $hora1 and horas_ini < $hora2 and id_espacios = $espacios and fecha = '$fecha'";
		$rs=$this->query($sql);
		$num=$this->cuantasFilas($rs);
			if($num==0){
				//die($this->pagina);
				return true;
			}else{
				return false;
			}
	}
	
	function pedir_reserva(){
       
	if($this->permiso($this->id_usaurio,$this->id_espacio,$this->fecha))
	{		
		if($this->validar_hora($this->fecha,$this->hora_ini,$this->hora_fin,$this->id_espacio))
		{
			
     	$values="'".$this->id_edificio."','".$this->id_usaurio."','".$this->id_espacio."','".$this->fecha."','".$this->hora_ini."','".$this->hora_fin."'";
		$idr=$this->insertar('vielmi_reservas_pedido','id_departamento,id_usuario,id_espacios,fecha,horas_ini,horas_fin',$values);
		$dato=$this->unicoDato('envio_email','vielmi_reservas_espacios ',$this->id_espacio,"id");
				if($dato==1){
					$titulo="Ha realizado una reserva";
					$this->envio_mail($this->id_usaurio,$this->id_espacio,$idr,$titulo);
				}
		$this->generar_vaucher($this->id_usaurio,$idr);
		
          }
		else 
		{
			//die($refer = $_SERVER['HTTP_REFERER'] );
			//echo "error horas";
		header("location:error.php?error=SI");
		}
		
	}else {
		
			//die($refer = $_SERVER['HTTP_REFERER'] );
		//echo "error periodo de tiempo";
		header("location:error.php?errorpermiso=SI");
	}

	
	}
	
	function generar_vaucher($id_usuario,$id_reserva)
	{
		header("location:vauher.php?id_usu=$id_usuario&id_reserva=$id_reserva");
	}
	
	function horas($idespacio,$horainicial){
		
		
	}
	
	function comprobarPermiso($id_usuario,$dia,$id){
		$num1=$this->cantidad_reservas($id);
		$num2=$this->verificar_alquileres($id,$dia);

		if($num1 > $num2){
			return true;
		}else{
			return false;
			
		}
	}
	function cantidad_reservas($id){
		$numero=$this->unicoDato('cant_priodo','vielmi_reservas_espacios',$id,'id');
		return $numero;
	}
	
	function verificar_alquileres($id,$dia){
		$sql="SELECT id_usuario FROM vielmi_reservas_pedido WHERE id_usuario = $id AND fecha >='$dia'";
		$rs=$this->query($sql);
		$num=$this->cuantasFilas($rs);
		return $num;
	}
	
}
?>
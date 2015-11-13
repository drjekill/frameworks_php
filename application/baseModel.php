<?php
abstract class baseModel{

	public static $instancia = null;

	public $db = null;	
	public $debug = array();	
	public $result;
	public $data;
	
	public function __construct(){
		$this->conectar();
	}
	
	public static function getInstance(){
		if (!self::$instancia){
			self::$instancia = new db();
		}
		return self::$instancia;
	}
	
	public function conectar(){

		$mysqli = new mysqli(SERVER_DB, USER_DB, PASS_DB, NAME_DB);
		if ($mysqli->connect_errno){
			return FALSE;
		}
		
		$this->db = $mysqli;
	}
	
	function select($campos, $tabla, $condicion=false, $order=false, $limit=false, $return=false){

		if(is_int($limit) || $limit == false){
			$limit = intval($limit);
		}else{
			$extract = explode(",", $limit);
			if(count($extract) == 2){
				$limit = intval($extract[0]) . "," . intval($extract[1]);
			}else{
				die("Error en el LIMIT: $limit");
			}
		
		}
		
		$sql = "SELECT $campos FROM $tabla";
		if($condicion)
			$sql .= " WHERE $condicion";
		if($order)
			$sql .= " ORDER BY $order";
		if($limit)
			$sql .= " LIMIT $limit";
			
		$result = $this->query($sql);
		
		if($return){
			return $result;
		}else{
			$this->result = $result;
		}
		//echo $sql;
	}
	
	
	function selectUnicaFila($campos, $tabla, $id, $campo="id", $return=false){
		//Valido no usar * en la seleccion de campos
		/*if(eregi('\*', $campos)){
			die("No puede usar * en la selecci&oacute;n de campos");
		}*/
		
		$id = intval($id);
		
		$sql = "SELECT $campos FROM $tabla WHERE $campo = '$id' LIMIT 1";
		$result = $this->query($sql);
		//echo $sql;
		if($return){
			return $result;
		}else{
			$this->result = $result;
		}
	}
	
	function unicoDato($campos, $tabla, $id, $campo="id"){
		$result = $this->selectUnicaFila($campos, $tabla, $id, $campo, true);
		$data = $this->fetch($result);
		return $data[$campos];
		
	}
	
	function insertar($tabla, $campos, $values){
		$sql = "INSERT INTO $tabla ";
		if($campos){
			$sql .= "($campos) VALUES ($values)";
		}else{
			$sql .= "VALUES ($values)";
		}
		$this->query($sql);
		return mysqli_insert_id($this->db);
	}
	
	function eliminar($tabla, $id, $campo="id"){
		$sql = "DELETE FROM $tabla WHERE $campo = " . intval($id);
		$this->query($sql);
		//echo $sql;
	}
	
	function actualizar($tabla, $campos, $id){
		$sql = "UPDATE $tabla SET $campos WHERE id = " . intval($id);
		$this->query($sql);
		//echo $sql;
	}
	
	
	function query($sql){
		$this->debug[] = $sql;
		
		if(!$result = mysqli_query($sql, $this->db))
		{
			print_r($this->sql_error($this->db));
			print_r($this->debug);
		}

		return $result;
	}
	
	function cuantasFilas($result=false){
		if(!$result){
			$result = $this->result;
		}
		return mysqli_num_rows($result);
	}
	
	function fetch($result=false){

		if(!$result){
			$result = $this->result;
		}
		
		return mysqli_fetch_assoc($result);
	}
	
	function __destruct(){
		mysqli_close($this->db);
	}

	function sql_error($query_id = 0)
	{
		$result["message"] = mysqli_error($this->db);
		$result["code"] = mysqli_errno($this->db);

		print_r($result);
	}
	function consultarid($id){
		$r = $this->select('id', 'remeras', false, 'id DESC', '0,1', true);
		$dato=$this->fetch($r);
		$new_id = $dato['id'] + 1;
		
		$consulta= $this->select('id_Categoria,Nombre', 'categoria', "id_Categoria=$id");
		$dato=$this->fetch($consulta);
		$iniciales=$dato[Nombre][0].$dato[Nombre][1];
		$secuencia = $_POST[estilo]+001;
		$codigo=$iniciales.$new_id;
		return $codigo;
		 
		 
	}
	
	function Cambiar_fecha($fecha){
		$fecha=explode("-",$fecha);
		$fechafin=$fecha[2]."-".$fecha[1]."-".$fecha[0];
     	return  $fechafin;	
	}
	
	public function limitPaginado($cantidad, $pagina=false){
		if($pagina===false){
			$pagina = intval(objeto::getRequest('pagina'));
		}
		$sql = "LIMIT ";
		$desde = $cantidad * $pagina;
		$sql.= "$desde, $cantidad";
		
		return $sql;
	}

	public function  contar($tabla){
		$query = "SELECT COUNT(*) FROM $tabla";
        $reg = mysqli_query($query);
        return  $reg;
	}
	
}
?>

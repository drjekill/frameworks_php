<?php
class layoutLibrary{

	public $layout;

	public function set_layout($name){
		$file = SITE_PATH.'views/'.$name.'layout.php';
		if(is_readable($file)){
			$this->layout = $file;
		}
	}


	public function view($name,array $vars = null){

		$file = SITE_PATH.'views/'.$name.'View.php';
		if(is_readable($file)){
			$html['contenido'] = file_get_contents($file);
			extract($vars);
			require($this->layout);
			return true;
		}

		throw new Exception('No se encuentra el layout');
	}

}
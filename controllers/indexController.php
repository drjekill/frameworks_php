<?php
	class indexController extends baseController{
		
		public function __construct(){
			parent::__construct();
			$this->load->library('layout');
		}
		public function index(){

				$this->load->model('posts');

				$vars['title'] = 'Dynamic title';
				$vars['posts'] = $this->posts->getEntries();
				$this->load->view('index',$vars);	
		}

		public function testDb($data){
			$this->load->model('reservas');
			$data = $this->reservas->get_reservas(61);
			echo "<pre>";
				print_r($data);
			echo "</pre>";
		}

	}

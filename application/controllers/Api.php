<?php

class Api extends CI_Controller {

  public function __construct() {
    if (isset($_SERVER['HTTP_ORIGIN'])) {
      header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { // Access-Control headers are received during OPTIONS requests
      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
      exit(0);
    }
    parent::__construct();
  }

	public function index() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $postdata = file_get_contents("php://input");
      if(isset($postdata)) {
        $post     = json_decode($postdata, TRUE);
        $model = (isset($post['model'])) ? $post['model'] : DEFAULT_MODEL;
        print_r($this->pdata($model, $post['key'], (isset($post['table']) ? $post['table'] : ''), (isset($post['table_key']) ? $post['table_key'] : ''), (isset($post['page']) ? $post['page'] : 0), (isset($post['perpage']) ? $post['perpage'] : 0), (isset($post['data']) ? $post['data'] : ''), (isset($post['where']) ? $post['where'] : '')));
      }
    }elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $model = ($this->input->get('model')) ? $this->input->get('model') : DEFAULT_MODEL;
      print_r($this->pdata($model, $this->input->get('key'), $this->input->get('table'), $this->input->get('table_key'), $this->input->get('page'), $this->input->get('perpage'), json_decode($this->input->get('data'), true), json_decode($this->input->get('where'), true)));
    }
	}

  public function pdata($model, $key, $table = null, $table_key = null, $page = 0, $perpage = 10, $data = array(), $where = array()) {
    if (empty($key)) {
      $data = array(
        'success' => false,
        'data'    => '',
        'message' => 'ACTION_NOT_SET',
      );
    }elseif (empty($table)) {
      $data = array(
        'success' => false,
        'data'    => '',
        'message' => 'TABLE_NOT_SET',
      );
    }else {
      // if ($model != DEFAULT_MODEL) {
      //   $this->load->model($model);
      // }
      // print_r($model);
      // exit;
      $this->load->model($model);
      $data = $this->$model->_pre($key, $table, $table_key, $page, $perpage, $data, $where);
    }
    return json_encode($data);
  }

}

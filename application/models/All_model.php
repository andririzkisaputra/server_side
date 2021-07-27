<?php

class All_model extends CI_Model {

  protected $table       = '-';
  protected $table_key   = '-';
  protected $page        = 0;
  protected $perpage     = 15;
  protected $data        = array();
  protected $where       = array();
  protected $result      = array(
    'success'   => true,
    'data'      => [],
    'totaldata' => 0,
    'message'   => '',
  );


  public function _pre($key, $table, $table_key, $page, $perpage, $data, $where) {
    $this->table     = $table;
    $this->table_key = $table_key;
    $this->where     = $where;
    $this->page      = $page;
    $this->perpage   = $perpage;
    $this->data      = $data;

    if (!method_exists($this, $key)) {
      $this->result['success'] = false;
      $this->result['message'] = 'FUNC_NOT_FOUND';

      return $this->result;
    }
    return $this->$key();
  }

  public function get_kategori() {
    $this->db->from('produk');

    if (isset($this->where['kategori_id'])) {
      $this->db->where(['kategori_id' => $this->where['kategori_id']]);
    }

    if (isset($this->where['search'])) {
      $this->db->like('nama_produk', $this->where['search']);
    }

    $data = $this->db->get()->result();
    foreach ($data as $key => $value) {
      $value->harga_produk_f = $this->all_library->format_harga($value->harga_produk);
    }
    $this->result['kategori']  = $this->db->from('kategori')->get()->result();
    $this->result['data']      = $data;
    $this->result['totaldata'] = ($this->result['data']) ? 1 : 0;
    return $this->result;
  }


}

?>

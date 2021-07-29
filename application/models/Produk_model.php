<?php

class Produk_model extends CI_Model {

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

  public function get_produk_detail() {
    $data = $this->db->from('produk')->where(['produk_id' => $this->where['produk_id']])->get()->result();
    foreach ($data as $key => $value) {
      $value->harga_produk_f = $this->all_library->format_harga($value->harga_produk);
<<<<<<< HEAD
      $value->link_gambar    = base_url().'assets/produk/'.$value->gambar;
=======
>>>>>>> 33434023d17d30682f3f41185bffe47ee89bc41d
    }
    $this->result['data']      = $data;
    $this->result['totaldata'] = ($this->result['data']) ? 1 : 0;
    return $this->result;
  }

  public function get_kategori() {
    $data     = $this->kategori('result');
    $num_rows = $this->kategori('num_rows');
    $this->result['data']      = $data;
    $this->result['totaldata'] = $num_rows;
    return $this->result;
  }

  public function kategori($get) {
    $this->db->from('kategori');
    $this->db->where('is_deleted', '1');
    if (isset($this->where['search'])) {
      $this->db->like('kategori', $this->where['search']);
    }
    if ($get == 'result') {
      $this->db->limit($this->perpage, $this->page);
    }
    $data = $this->db->get()->$get();
    return $data;
  }

  public function set_kategori() {
    $data = array(
      'kategori'    => $this->data['kategori'],
      'is_deleted'  => '1',
      'modified_on' => date('Y-m-d H:i:s')
    );
    $insert = $this->db->insert($this->table, $data);
    return $this->result['success'] = ($insert) ? true : false;
  }

  public function hapus_kategori() {
    $data = array(
      'is_deleted'  => '0',
      'modified_on' => date('Y-m-d H:i:s')
    );
    $this->db->where('kategori_id', $this->where['kategori_id']);
    $update = $this->db->update($this->table, $data);
    return $this->result['success'] = ($update) ? true : false;
  }

  public function ubah_kategori() {
    $data = array(
      'kategori'    => $this->data['kategori'],
      'modified_on' => date('Y-m-d H:i:s')
    );
    $this->db->where('kategori_id', $this->where['kategori_id']);
    $update = $this->db->update($this->table, $data);
    return $this->result['success'] = ($update) ? true : false;
  }


}

?>

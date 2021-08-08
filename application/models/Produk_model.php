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
      $varian_id = json_decode($value->varian_id);
      if (count($varian_id) > 1) {
        foreach ($varian_id as $k => $v) {
          $value->varian[]   = $this->db->from('varian')->where(['varian_id' => $v])->get()->row();
        }
      }else {
        $value->varian[]     = $this->db->from('varian')->where(['varian_id' => $varian_id])->get()->row();
      }
      $value->harga_produk_f = $this->all_library->format_harga($value->harga_produk);
      $value->link_gambar    = base_url().'assets/produk/'.$value->gambar;
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

  public function get_varian() {
    $data     = $this->varian('result');
    $num_rows = $this->varian('num_rows');
    $this->result['data']      = $data;
    $this->result['totaldata'] = $num_rows;
    return $this->result;
  }

  public function varian($get) {
    $this->db->from('varian');
    $this->db->where('is_deleted', '1');
    if (isset($this->where['search'])) {
      $this->db->like('varian', $this->where['search']);
    }
    if ($get == 'result') {
      $this->db->limit($this->perpage, $this->page);
    }
    $data = $this->db->get()->$get();
    return $data;
  }

  public function set_varian() {
    $data = array(
      'varian'       => $this->data['varian'],
      'sub_varian'   => json_encode($this->data['sub_varian']),
      'harga_varian' => json_encode($this->data['harga_varian']),
      'jenis_varian' => $this->data['jenis_varian'],
      'is_deleted'   => '1',
      'modified_on'  => date('Y-m-d H:i:s')
    );
    $insert = $this->db->insert('varian', $data);
    return $this->result['success'] = ($insert) ? true : false;
  }

  public function hapus_varian() {
    $data = array(
      'is_deleted'  => '0',
      'modified_on' => date('Y-m-d H:i:s')
    );
    $this->db->where('varian_id', $this->where['varian_id']);
    $update = $this->db->update($this->table, $data);
    return $this->result['success'] = ($update) ? true : false;
  }

  public function ubah_varian() {
    $data = array(
      'varian'       => $this->data['varian'],
      'sub_varian'   => json_encode($this->data['sub_varian']),
      'harga_varian' => json_encode($this->data['harga_varian']),
      'jenis_varian' => json_encode($this->data['jenis_varian']),
      'modified_on'  => date('Y-m-d H:i:s')
    );
    $this->db->where('varian_id', $this->where['varian_id']);
    $update = $this->db->update('varian', $data);
    return $this->result['success'] = ($update) ? true : false;
  }

  public function set_bahanBaku() {
    $data = array(
      'bahan_baku'   => $this->data['bahan_baku'],
      'is_deleted'   => '1',
      'modified_on'  => date('Y-m-d H:i:s')
    );
    $insert = $this->db->insert('bahan_baku', $data);
    return $this->result['success'] = ($insert) ? true : false;
  }

  public function get_bahan_baku() {
    $data     = $this->bahan_baku('result');
    $num_rows = $this->bahan_baku('num_rows');
    $this->result['data']      = $data;
    $this->result['totaldata'] = $num_rows;
    return $this->result;
  }

  public function bahan_baku($get) {
    $this->db->from('bahan_baku');
    $this->db->where('is_deleted', '1');
    if (isset($this->where['search'])) {
      $this->db->like('bahan_baku', $this->where['search']);
    }
    if ($get == 'result') {
      $this->db->limit($this->perpage, $this->page);
    }
    $data = $this->db->get()->$get();
    return $data;
  }

  public function hapus_bahan_baku() {
    $data = array(
      'is_deleted'  => '0',
      'modified_on' => date('Y-m-d H:i:s')
    );
    $this->db->where('bahan_baku_id', $this->where['bahan_baku_id']);
    $update = $this->db->update($this->table, $data);
    return $this->result['success'] = ($update) ? true : false;
  }

  public function ubah_bahan_baku() {
    $data = array(
      'bahan_baku'   => $this->data['bahan_baku'],
      'modified_on'  => date('Y-m-d H:i:s')
    );
    $this->db->where('bahan_baku_id', $this->where['bahan_baku_id']);
    $update = $this->db->update('bahan_baku', $data);
    return $this->result['success'] = ($update) ? true : false;
  }

}

?>

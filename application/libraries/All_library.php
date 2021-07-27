<?php

class All_library {
  public function format_harga($val, $prefix = 'Rp ') {
    if(!empty($val)){
      $_val = $prefix.number_format((float)$val,0,',','.');
      return $_val;
    }
  }
}

?>

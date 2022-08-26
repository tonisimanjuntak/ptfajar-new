<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_model extends CI_Model {

    var $tabelview = 'pengaturan';
    var $tabel     = 'pengaturan';

    public function get_all()
    {
        return $this->db->get($this->tabelview);
    }

    public function update($data)
    {
        return $this->db->update($this->tabel, $data);
    }

}

/* End of file Pengaturan_model.php */
/* Location: ./application/models/Pengaturan_model.php */
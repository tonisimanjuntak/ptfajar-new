<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stokopname_model extends CI_Model {

	var $tabelview = 'v_stokopname';
    var $tabel     = 'stokopname';
    var $idstokopname = 'idstokopname';

    var $column_order = array(null,'tglstokopname', 'namapengguna', 'deskripsi', null );
    var $column_search = array('tglstokopname','deskripsi','namapengguna');
    var $order = array('idstokopname' => 'desc'); // default order 


    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get();        
    }

    private function _get_datatables_query()
    {   
        $this->db->from($this->tabelview);
        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {
                if($i===0) {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); 
            }
            $i++;
        }
        
        // -------------------------> Proses Order by        
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

    }

    function count_filtered()
    {
        $this->db->select('count(*) as jlh');
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->row()->jlh;
    }
 
    public function count_all()
    {
        $this->db->select('count(*) as jlh');
        return $this->db->get($this->tabelview)->row()->jlh;
    }

    public function get_all()
    {
        return $this->db->get($this->tabelview);
    }

    public function get_by_id($idstokopname)
    {
        $this->db->where('idstokopname', $idstokopname);
        return $this->db->get($this->tabelview);
    }

    public function hapus($idstokopname)
    {
    	$this->db->query("delete from stokopnamedetail where idstokopname='$idstokopname'");
        $this->db->where('idstokopname', $idstokopname);      
        return $this->db->delete($this->tabel);
    }

    public function simpan($data, $arrDetail)
    {       
        $this->db->trans_begin();

        $this->db->insert($this->tabel, $data);

        foreach ($arrDetail as $key => $row) {        	
        	//SO Detail
        	$dataStokopnameDetail= array(
        									'idstokopname' => $row['idstokopname'], 
        									'kodeakun' => $row['kodeakun'], 
        									'jumlahpersediaansistem' => $row['jumlahpersediaansistem'], 
        									'jumlahpersediaaninput' => $row['jumlahpersediaaninput'], 
        									'selisih' => $row['selisih']
        								);        	
	        $this->db->insert('stokopnamedetail', $dataStokopnameDetail);


	        //Kartu Stok
    		$jumlahmasuk = 0;
    		$jumlahkeluar = 0;
        	if ($row['selisih']>0) {
				$jumlahmasuk = $row['selisih'];
        	}

        	if ($row['selisih']<0) {
				$jumlahkeluar = $row['selisih'];
        	}
	        $idkartustok = $this->db->query("SELECT create_idkartustok('".date('Y-m-d')."') as idkartustok")->row()->idkartustok;
	        $dataKartuStok = array(
	        							'idkartustok' => $idkartustok, 
	        							'kodeakun' => $row['kodeakun'], 
	        							'tglinsert' => date('Y-m-d H:i:s'), 
	        							'idtransaksi' => $row['idstokopname'], 
	        							'tgltransaksi' => $data['tglstokopname'], 
	        							'jenistransaksi' => 'SO', 
	        							'aksi' => 'Insert', 
	        							'stokawal' => $row['jumlahpersediaansistem'], 
	        							'jumlahmasuk' => $jumlahmasuk, 
	        							'jumlahkeluar' => $jumlahkeluar, 
	        							'stokakhir' => $row['jumlahpersediaaninput']
	        						);
	        $this->db->insert('kartustok', $dataKartuStok);
        }

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }    

}

/* End of file Stokopname_model.php */
/* Location: ./application/models/Stokopname_model.php */
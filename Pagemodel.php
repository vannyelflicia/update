<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagemodel extends CI_Model
{
	function auth($username, $password){
		$sql=$this->db->query("SELECT * from user WHERE username='$username' AND password='$password' ");
		return $sql;
	}

    function getAPedit($no_transaksi){
        $data = $this->db->select('*')
                        ->from('op')
                        ->join('ap', 'op.no_transaksi = ap.no_transaksiap', 'left')
                        ->where('op.no_transaksi',$no_transaksi)
                        ->get()
                        ->result();
        return $data;
    }

    function getno_transaksiap($no_transaksi){
        $id = $this->db->from('ap')
                              ->where('ap.no_transaksiap',$no_transaksi)
                              ->get()
                              ->result();
            return $id;
    }

	function getOPedit($no_transaksi){
        $no_transaksi = $this->db->from('op')
                              ->where('op.no_transaksi',$no_transaksi)
                              ->get()
                              ->result();
            return $no_transaksi;
	}

    function getno_transaksiop($no_transaksi){
        $id = $this->db->from('op')
                              ->where('op.no_transaksi',$no_transaksi)
                              ->get()
                              ->result();
            return $id;
    }

    function getno_transaksiin($no_transaksi){
        $id = $this->db->from('op_in')
                              ->where('op_in.no_transaksi_in',$no_transaksi)
                              ->get()
                              ->result();
            return $id;
    }

    function select_no_transaksiop($no_transaksi)
    {
        $hasil=$this->db->query("SELECT op.no_transaksi FROM op WHERE op.no_transaksi IN (SELECT op_in.no_transaksiin from op_in,op WHERE op.no_transaksi = op_in.no_transaksi_in OR op.no_transaksi = '$no_transaksi')");
        if ($hasil ->num_rows() > 0) {
            return $hasil->row()->no_transaksi;
        } 
        return false;
    }   

    function getno_transaksiopin($id){
        $id = $this->db->from('op')
                              ->where('op.no_transaksi',$id)
                              ->get()
                              ->result();
            return $id;
    }


    function delTruck($no_transaksi)
    {
         $hasil=$this->db->query("DELETE FROM truck WHERE no_transaksitr='$no_transaksi'");
        return $hasil;
    }

    function delAR($no_transaksi)
    {
         $hasil=$this->db->query("DELETE FROM ar WHERE no_transaksiar='$no_transaksi'");
        return $hasil;
    }

    function delAP($no_transaksi)
    {
        $hasil=$this->db->query("DELETE FROM ap WHERE no_transaksiap='$no_transaksi'");
        return $hasil;
    }

    function delOP($no_transaksi)
    {
        $hasil=$this->db->query("DELETE op FROM op WHERE no_transaksi='$no_transaksi'");
        return $hasil;
    }

    // function delOPin($no_transaksi)
    // {
    //     $hasil=$this->db->query("DELETE FROM op_in WHERE no_transaksiin='$no_transaksi'");
    //     return $hasil;
    // }

    function delOPwherein($no_transaksi)
    {
        // $hasil=$this->db->query("DELETE FROM op_in WHERE no_transaksi_in IN (SELECT op.no_transaksi from op JOIN op_in ON op.no_transaksi = op_in.no_transaksi_in WHERE op_in.no_transaksiin = '$no_transaksi') ");

        $hasil=$this->db->query("DELETE FROM op_in WHERE no_transaksi_in IN 
            ( SELECT baru.no_transaksi_in FROM (SELECT * FROM op_in baru JOIN op ON op.no_transaksi = baru.no_transaksi_in WHERE baru.no_transaksiin = '$no_transaksi' ) AS baru
            ) ");
        return $hasil;
    }

    function delPettyCash($no_transaksi)
    {
         $hasil=$this->db->query("DELETE FROM pettycash WHERE no_transaksipc='$no_transaksi'");
        return $hasil;
    }

    function delMonitoring($no_transaksi)
    {
        $hasil=$this->db->query("DELETE FROM m_cont WHERE no_transaksimc='$no_transaksi'");
        return $hasil;
    }

    function delDokumen_OP($no_transaksi)
    {
        $hasil=$this->db->query("DELETE FROM dokumen_OP WHERE no_transaksidok='$no_transaksi'");
        return $hasil;
    }

	function getARedit($no_transaksi){
        $data = $this->db->select('*')
                        ->from('op')
                        ->join('ar', 'op.no_transaksi = ar.no_transaksiar', 'left')
                        ->where('op.no_transaksi',$no_transaksi)
                        ->get()
                        ->result();
        return $data;
	}

    function getno_transaksiar($no_transaksi){
        $id = $this->db->from('ar')
                              ->where('ar.no_transaksiar',$no_transaksi)
                              ->get()
                              ->result();
            return $id;
    }

	function getTruckedit($no_transaksi){
            $data = $this->db->select('*')
                        ->from('op')
                        ->join('truck', 'op.no_transaksi = truck.no_transaksitr', 'left')
                        ->where('op.no_transaksi',$no_transaksi)
                        ->get()
                        ->result();
        return $data;
    }

	function getno_transaksitr($no_transaksi){
        $id = $this->db->from('truck')
                              ->where('truck.no_transaksitr',$no_transaksi)
                              ->get()
                              ->result();
            return $id;
    }

	function getTruckid($no_transaksi){
		$data = $this->db->from('truck')
    				 ->join('op','truck.no_transaksitr=op.no_transaksi')
    				 ->where('truck.no_transaksitr', $no_transaksi)
    				->get()
                     ->result();
        return $data;
	}

    function getno_transaksipc($no_transaksi){
        $id = $this->db->from('pettycash')
                              ->where('pettycash.no_transaksipc',$no_transaksi)
                              ->get()
                              ->result();
            return $id;
    }

    function getPettyCashedit($no_transaksi){
            $data = $this->db->select('*')
                        ->from('op')
                        ->join('pettycash', 'op.no_transaksi = pettycash.no_transaksipc', 'left')
                        ->where('op.no_transaksi',$no_transaksi)
                        ->get()
                        ->result();
        return $data;
    }

    function getno_transaksimc($no_transaksi){
        $id = $this->db->from('m_cont')
                              ->where('m_cont.no_transaksimc',$no_transaksi)
                              ->get()
                              ->result();
            return $id;
    }

    
    function getMonitoringedit($no_transaksi){
            $data = $this->db->select('*')
                        ->from('op')
                        ->join('m_cont', 'op.no_transaksi = m_cont.no_transaksimc', 'left')
                        ->where('op.no_transaksi',$no_transaksi)
                        ->get()
                        ->result();
        return $data;
    }

    function getno_transaksidok($no_transaksi){
        $id = $this->db->from('dokumen_OP')
                              ->where('dokumen_OP.no_transaksidok',$no_transaksi)
                              ->get()
                              ->result();
            return $id;
    }

    
    function getDokumen_OPedit($no_transaksi){
            $data = $this->db->select('*')
                        ->from('op')
                        ->join('dokumen_OP', 'op.no_transaksi = dokumen_OP.no_transaksidok', 'left')
                        ->where('op.no_transaksi',$no_transaksi)
                        ->get()
                        ->result();
        return $data;
    }

	function getAll(){
		$this->db->select('*');
		$this->db->from('op');
        $this->db->join('op_in', 'op.no_transaksi = op_in.no_transaksi_in', 'left');
		$this->db->join('ar', 'op.no_transaksi = ar.no_transaksiar', 'left');
		$this->db->join('ap', 'op.no_transaksi = ap.no_transaksiap', 'left');
        $this->db->join('truck', 'op.no_transaksi = truck.no_transaksitr', 'left');
        $this->db->join('pettycash', 'op.no_transaksi = pettycash.no_transaksipc', 'left');
        $this->db->join('m_cont', 'op.no_transaksi = m_cont.no_transaksimc', 'left');
		$this->db->join('dokumen_op', 'op.no_transaksi = dokumen_op.no_transaksidok', 'left');
		$data = $this->db->get();
		return $data->result();
	}


	function ambilOP(){
		$this->db->select('op.no_transaksi, op.IMO, op.no_container, op.name_cust, op.no_shipment, op.no_shipment2, op.no_seal, op.full_empty, op.reefer_dry, op.stuff_date, op.tgl_kon_masuk, op.in_out, op.origin_town, op.dest_town, op.vessel_name, op.deliv_date, op.arv_at_dest, op.remark_op, op.tgl_door, op.no_ship_uli, op.tuj_ship_uli, op.tgl_door_uli, op.no_ship_uli2, op.tuj_ship_uli2, op.tgl_door_uli2, op_in.*');
        $this->db->from('op');
        $this->db->join('op_in', 'op.no_transaksi = op_in.no_transaksi_in', 'left');
        $data = $this->db->get();
        return $data->result();
	}

    function ambilOPmonitor(){
        $this->db->select('op.no_transaksi, op.IMO, op.no_container, op.name_cust, op.no_shipment, op.no_shipment2, op.no_seal, op.full_empty, op.reefer_dry, op.stuff_date, op.tgl_kon_masuk, op.in_out, op.origin_town, op.dest_town, op.vessel_name, op.deliv_date, op.arv_at_dest, op.remark_op, op.tgl_door, op.no_ship_uli, op.tuj_ship_uli, op.tgl_door_uli, op.no_ship_uli2, op.tuj_ship_uli2, op.tgl_door_uli2, op_in.*');
        $this->db->from('op');
        $this->db->join('op_in', 'op.no_transaksi = op_in.no_transaksi_in', 'left');
        $this->db->where('op.in_out','Out');
        $data = $this->db->get();
        return $data->result();
    }

	function getAR(){
		$this->db->select('*');
        $this->db->from('op');
        $this->db->join('ar', 'op.no_transaksi = ar.no_transaksiar', 'left');
        $data = $this->db->get();
        return $data->result();
	}

	function getAP(){
        $this->db->select('*');
        $this->db->from('op');
        $this->db->join('ap', 'op.no_transaksi = ap.no_transaksiap', 'left');
        $data = $this->db->get();
        return $data->result();
	}

	function getTruck(){
		$this->db->select('*');
        $this->db->from('op');
        $this->db->join('truck', 'op.no_transaksi = truck.no_transaksitr', 'left');
        $data = $this->db->get();
        return $data->result();
	}

    function getPettyCash(){
        $this->db->select('*');
        $this->db->from('op');
        $this->db->join('pettycash', 'op.no_transaksi = pettycash.no_transaksipc', 'left');
        $data = $this->db->get();
        return $data->result();
    }

    function getMonitoring(){
        $this->db->select('*');
        $this->db->from('op');
        $this->db->where('op.in_out','Out');
        $data = $this->db->get();
        return $data->result();

        // $this->db->select('*');
        // $this->db->from('op');
        // $this->db->join('m_cont', 'op.no_transaksi = m_cont.no_transaksimc', 'left');
        // $this->db->where('op.in_out','Out');
        // $data = $this->db->get();
        // return $data->result();
    }

    function getMonitoring2(){
        $this->db->select('*');
        $this->db->from('op');
        $this->db->where('op.in_out','In');
        $data = $this->db->get();
        return $data->result();
    }

    function getDokumen_OP(){
        $this->db->select('*');
        $this->db->from('op');
        $this->db->join('dokumen_OP', 'op.no_transaksi = dokumen_OP.no_transaksidok', 'left');
        $data = $this->db->get();
        return $data->result();
    }

	function getNO(){
		$this->db->select('op.no_container');
		$this->db->from('ap');
		$this->db->join('op', 'ap.no_transaksiap = op.no_transaksi', 'join');
		$data = $this->db->get();
		return $data->result();
	}

	function getIMO(){
		$this->db->select('op.IMO');
		$this->db->from('op');
		$data = $this->db->get();
		return $data->result();
	}

	public function Insert($table,$data){
		$res = $this->db->insert($table, $data);
		return $res;
	}

	public function ExportExcel(){
        // $query = $this->db->query("SELECT * from eimport");
        $this->db->select('*');
		$this->db->from('op');
		$this->db->join('ar', 'op.IMO = ar.IMO');
		$this->db->join('ap', 'op.IMO = ap.IMO');
		$data = $this->db->get();
		return $data->result();

        // if($data->num_rows() > 0){
        //     foreach($data->result() as $jumlah){
        //         $hasil[] = $jumlah;
        //     }
        //     return $hasil;
        // }
    }

    function getAll2(){
    	$this->db->select('*');
		$this->db->from('op');
		$this->db->join('ap2', 'op.IMO = ap2.IMO');
		$this->db->join('truck2', 'ap2.IMO_v2 = truck2.IMO_v2');
		$data = $this->db->get();
		return $data->result();
    }

    function getAP2(){
		$this->db->select('*');
		$this->db->from('ap2');
		$data = $this->db->get();
		return $data->result();
	}

	function getTruck2(){
		$this->db->select('*');
		$this->db->from('truck2');
		$data = $this->db->get();
		return $data->result();
	}

	function getIMO2(){
		$this->db->select('op.IMO');
		$this->db->from('op');
		$this->db->join('ap2', 'op.IMO = ap2.IMO');
		$data = $this->db->get();
		return $data->result();
	}

	function getIMO2All(){
		$this->db->select('ap2.IMO_v2');
		$this->db->from('ap2');
		$data = $this->db->get();
		return $data->result();
	}

	function getAPedit2($imo){
        $IMO = $this->db->from('ap2')
                              ->where('ap2.IMO_v2',$imo)
                              ->get()
                              ->result();
            return $IMO;
	}

	function simpanAP2(){
        $IMO_v2 = $this->input->post('IMO_v2');
 
        $data = array (
        	'IMO' => $_POST['IMO'],
        	'IMO_v2' => $_POST['IMO_v2'],
        	'name_cust' => $_POST['name_cust'],
			'inv_cust' => $_POST['inv_cust'],
			'inv_ag' => $_POST['inv_ag'],
			'pay_ag' => $_POST['pay_ag'],
			'inv_genset' => $_POST['inv_genset'],
			'pay_genset' => $_POST['pay_genset'],
			'inv_ship' => $_POST['inv_ship'],
			'pay_ship' => $_POST['pay_ship']
        	);
        if($IMO_v2){
        	$this->db->where('IMO_v2', $IMO_v2);
            $this->db->update('ap2', $data);
        }
        else
        {
        	$this->db->insert('ap2', $data);
        }
	}

	function getTruckedit2($imo){
        $IMO = $this->db->from('truck2')
                              ->join('ap2','truck2.IMO_v2 = ap2.IMO_v2')
                              ->where('truck2.IMO_v2',$imo)
                              ->get()
                              ->result();
            return $IMO;
	}

	function simpanTruck2(){
        $IMO = $this->input->post('IMO_v2');
        	
        $data = array (
        	'IMO_v2' => $_POST['IMO_v2'],
			'name_truck' => $_POST['name_truck'],
			'inv_truck' => $_POST['inv_truck'],
			'pay_truck' => $_POST['pay_truck']
			
        	);
        if($IMO){
        	$this->db->where('IMO_v2', $IMO_v2);
            $this->db->update('truck2', $data);
        }
        else
        {
        	$this->db->insert('truck2', $data);
        }
	}

	function getnoFaktur($imo){
        $IMO = $this->db->from('ar')
                              ->where('ar.IMO',$imo)
                              ->get()
                              ->result();
            return $IMO;
	}

	function simpannoFaktur(){
        $IMO = $this->input->post('IMO');
        	
        $data = array (
			'no_faktur' => $_POST['no_faktur']
			
        	);
        if($IMO){
        	$this->db->where('IMO', $IMO);
            $this->db->update('ar', $data);
        }
        else
        {
        	$this->db->insert('ar', $data);
        }
	}

	function getpay($imo){
        $IMO = $this->db->from('ar')
                              ->where('ar.IMO',$imo)
                              ->get()
                              ->result();
            return $IMO;
	}

	function simpanpayCust($IMO,$pay_inv){
        	
        $data = array (
			'pay_inv' => $pay_inv
        	);
        if($IMO){
        	$this->db->where('IMO', $IMO);
            $this->db->update('ar', $data);
        }
        else
        {
        	$this->db->insert('ar', $data);
        }
	}

    function getname_agen(){
        $this->db->select('agent.name_agen');
        $this->db->from('agent');
        $data = $this->db->get();
        return $data->result();
    }

    function getname_ship(){
        $this->db->select('ship.name_ship');
        $this->db->from('ship');
        $data = $this->db->get();
        return $data->result();
    }

    function getno_con(){
        $this->db->select('*');
        $this->db->from('container');
        $data = $this->db->get();
        return $data->result();
    }

    function getno_conin(){
        $this->db->select('*');
        $this->db->from('container');
        $data = $this->db->get();
        return $data->result();
    }


    function getno_transaksi(){
        $q = $this->db->query("SELECT MAX(RIGHT(no_transaksi,4)) AS kd_max FROM op WHERE DATE(tanggal)=CURDATE()");
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        return date('dmy').$kd;
    }

    function simpanData($where,$data,$table){
        $this->db->where($where);
        $this->db->update($table,$data);
    }

    function simpanDatain($wherein,$datain,$table){
        $this->db->where($wherein);
        $this->db->update($table,$datain);
    }


    function createData($data,$table){
        $this->db->insert($table,$data);
    }

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagecontrol extends CI_Controller {

	function __construct()
	{
	parent::__construct();
	$this->load->helper('form');
	$this->load->helper('url');
	$this->load->library('pdf');
	$this->load->model('pagemodel');
	}

	// LOGIN
	function index(){
		$this->load->view('login_v');
	}

	function login(){
		$username=htmlspecialchars($this->input->post('username',TRUE),ENT_QUOTES);
        $password=htmlspecialchars($this->input->post('password',TRUE),ENT_QUOTES);
		$this->load->model('pagemodel');

		$cek = $this->pagemodel->auth($username , $password);

		if($cek->num_rows() > 0){
			$data=$cek->row_array();
			$this->session->set_userdata('masuk', TRUE);
			if($data['job_tittle']=='OP'){
				$this->session->set_userdata('akses','Operasional');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashOP');
			}
			else if($data['job_tittle']=='AP'){
				$this->session->set_userdata('akses','Acount Payable');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashAP');
			}
			else if($data['job_tittle']=='AR'){
				$this->session->set_userdata('akses','Acount Receivable');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashAR');
			}
			else if($data['job_tittle']=='Supervisor'){
				$this->session->set_userdata('akses','Supervisor');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashboard');
			}
			else if($data['job_tittle']=='Truck'){
				$this->session->set_userdata('akses','Truck');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashTruck');
			}
			else if($data['job_tittle']=='Dokumen_OP'){
				$this->session->set_userdata('akses','Dokumen OP');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashDokumen_OP');
			}
			else if($data['job_tittle']=='PettyCash'){
				$this->session->set_userdata('akses','Petty Cash');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashPettyCash');
			}
			else if($data['job_tittle']=='Monitor'){
				$this->session->set_userdata('akses','Monitoring Container');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashMonitoring');
			}
			else if($data['job_tittle']=='Pajak'){
				$this->session->set_userdata('akses','Pajak');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Pagecontrol/dashAR');
			}
			else if($data['job_tittle']=='SuperAdmin'){
				$this->session->set_userdata('akses','ADMIN');
				$this->session->set_userdata('ses_nama',$data['name']);
				redirect('Admincontrol/user');
			}	
		}
		else {
			echo '<script>alert("Mohon Periksa Kembali Username dan Password");</script>';
			redirect(base_url(), 'refresh');
		}
	}

	function logout(){
		$this->session->sess_destroy();
		redirect(base_url(), 'refresh');
	}
	// LOGIN

	function dashboard(){
		if ($this->session->userdata('akses')=='Supervisor' ){
		$this->load->model('pagemodel');
		$data['datahasil'] = $this->pagemodel->getAll();
		$this->load->view('dashboard_v', $data);
	}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function deldashboard($imo){
		$this->load->model('pagemodel');
		$where = array('IMO' => $imo);
		$this->pagemodel->delTruck($where, 'truck');
		$this->pagemodel->delAP($where, 'ap');
		$this->pagemodel->delAR($where, 'ar');
		$this->pagemodel->delOP($where, 'op');
		redirect('Pagecontrol/dashboard');
	}

	// OP
	function formOP(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Operasional'){
		$this->load->helper('url');
		$this->load->model('pagemodel');
		$data['nocon'] = $this->pagemodel->getno_con();
		$data['notrans'] = $this->pagemodel->getno_transaksi();
		$this->load->view('createOP_v', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function formOP_in($no_transak){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Operasional'){
		$this->load->helper('url');
		$this->load->model('pagemodel');
		$data['notrans'] = $this->pagemodel->getno_transaksi();
		$data['notransin'] = $this->pagemodel->getno_transaksiop($no_transak);
		$this->load->view('createOP_in', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function insertno_con(){
		$this->load->model('pagemodel');
		$hidden_nocon=$this->input->post('hidden_nocon');
		$no_container=$this->input->post('no_container');
		echo json_encode($data);	
	}

	function insertOP(){
		$this->load->model('pagemodel');
		$cek = $this->db->query("SELECT * FROM op where no_transaksi='".$this->input->post('no_transaksi')."'")->num_rows();
		if ($cek<=0) {
		$data = array(
			'no_transaksi' => $this->input->post('no_transaksi'),
			'IMO' => $this->input->post('IMO'),
			'no_container' => $this->input->post('hidden_nocon'),
			'name_cust' => $this->input->post('name_cust'),
			'no_shipment' => $this->input->post('no_shipment'),
			'no_shipment2' => $this->input->post('no_shipment2'),
			'no_seal' => $this->input->post('no_seal'),
			'full_empty' => $this->input->post('full_empty'),
			'reefer_dry' => $this->input->post('reefer_dry'),
			'stuff_date' => $this->input->post('stuff_date'),
			'in_out' => $this->input->post('in_out'),
			'origin_town' => $this->input->post('origin_town'),
			'dest_town' => $this->input->post('dest_town'),
			'vessel_name' => $this->input->post('vessel_name'),
			'deliv_date' => $this->input->post('deliv_date'),
			'arv_at_dest' => $this->input->post('arv_at_dest'),
			'remark_op' => $this->input->post('remark_op'),
			'tgl_door' => $this->input->post('tgl_door'),
			'tgl_kon_masuk' => $this->input->post('tgl_kon_masuk'),
			'no_ship_uli' => $this->input->post('no_ship_uli'),
			'tuj_ship_uli' => $this->input->post('tuj_ship_uli'),
			'tgl_door_uli' => $this->input->post('tgl_door_uli'),
			'no_ship_uli2' => $this->input->post('no_ship_uli2'),
			'tuj_ship_uli2' => $this->input->post('tuj_ship_uli2'),
			'tgl_door_uli2' => $this->input->post('tgl_door_uli2')
			);
		$data = $this->pagemodel->Insert('op', $data);
		echo '<script>alert("Data Operasional Berhasil Ditambahkan!");</script>';
		redirect('Pagecontrol/dashOP', 'refresh');
		} else
		{
			echo '<script>alert("Data Operasional Gagal Ditambahkan!");</script>';
			redirect('Pagecontrol/formOP', 'refresh');
		}
	}

	function insertOP_in(){
		$this->load->model('pagemodel');
		$cek = $this->db->query("SELECT * FROM op_in where no_transaksi_in='".$this->input->post('no_transaksi')."'")->num_rows();
		if ($cek>0)
		{
			echo '<script>alert("Data In Dengan No. Container Sudah Ada! Mohon dicek kembali");</script>';
			redirect('Pagecontrol/dashOP', 'refresh');
		} else 
		{
		$data = array(
			'no_transaksi_in' => $this->input->post('no_transaksi'),
			'no_transaksiin' => $this->input->post('no_transaksi_in'),
			'IMO_in' => $this->input->post('IMO_in'),
			'no_container_in' => $this->input->post('no_container_in'),
			'name_cust_in' => $this->input->post('name_cust_in'),
			'no_shipment_in' => $this->input->post('no_shipment_in'),
			'no_shipment2_in' => $this->input->post('no_shipment2_in'),
			'no_seal_in' => $this->input->post('no_seal_in'),
			'full_empty_in' => $this->input->post('full_empty_in'),
			'reefer_dry_in' => $this->input->post('reefer_dry_in'),
			'stuff_date_in' => $this->input->post('stuff_date_in'),
			'in_out_in' => $this->input->post('in_out_in'),
			'origin_town_in' => $this->input->post('origin_town_in'),
			'dest_town_in' => $this->input->post('dest_town_in'),
			'vessel_name_in' => $this->input->post('vessel_name_in'),
			'deliv_date_in' => $this->input->post('deliv_date_in'),
			'arv_at_dest_in' => $this->input->post('arv_at_dest_in'),
			'remark_op_in' => $this->input->post('remark_op_in'),
			'tgl_door_in' => $this->input->post('tgl_door_in'),
			'tgl_kon_masuk_in' => $this->input->post('tgl_kon_masuk_in'),
			'no_ship_uli_in' => $this->input->post('no_ship_uli_in'),
			'tuj_ship_uli_in' => $this->input->post('tuj_ship_uli_in'),
			'tgl_door_uli_in' => $this->input->post('tgl_door_uli_in'),
			'no_ship_uli2_in' => $this->input->post('no_ship_uli2_in'),
			'tuj_ship_uli2_in' => $this->input->post('tuj_ship_uli2_in'),
			'tgl_door_uli2_in' => $this->input->post('tgl_door_uli2_in')
			);
		$this->pagemodel->Insert('op_in', $data);
		$data2 = array(
			'no_transaksi' => $this->input->post('no_transaksi_in'),
			'IMO' => $this->input->post('IMO_in'),
			'no_container' => $this->input->post('no_container_in'),
			'name_cust' => $this->input->post('name_cust_in'),
			'no_shipment' => $this->input->post('no_shipment_in'),
			'no_shipment2' => $this->input->post('no_shipment2_in'),
			'no_seal' => $this->input->post('no_seal_in'),
			'full_empty' => $this->input->post('full_empty_in'),
			'reefer_dry' => $this->input->post('reefer_dry_in'),
			'stuff_date' => $this->input->post('stuff_date_in'),
			'in_out' => $this->input->post('in_out_in'),
			'origin_town' => $this->input->post('origin_town_in'),
			'dest_town' => $this->input->post('dest_town_in'),
			'vessel_name' => $this->input->post('vessel_name_in'),
			'deliv_date' => $this->input->post('deliv_date_in'),
			'arv_at_dest' => $this->input->post('arv_at_dest_in'),
			'remark_op' => $this->input->post('remark_op_in'),
			'tgl_door' => $this->input->post('tgl_door_in'),
			'tgl_kon_masuk' => $this->input->post('tgl_kon_masuk_in'),
			'no_ship_uli' => $this->input->post('no_ship_uli_in'),
			'tuj_ship_uli' => $this->input->post('tuj_ship_uli_in'),
			'tgl_door_uli' => $this->input->post('tgl_door_uli_in'),
			'no_ship_uli2' => $this->input->post('no_ship_uli2_in'),
			'tuj_ship_uli2' => $this->input->post('tuj_ship_uli2_in'),
			'tgl_door_uli2' => $this->input->post('tgl_door_uli2_in')
			);
		$this->pagemodel->Insert('op', $data2);
		echo '<script>alert("Data Operasional Berhasil Ditambahkan!");</script>';
		redirect('Pagecontrol/dashOP', 'refresh');
		}
		
	}

	function dashOP(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Operasional'){
		$this->load->model('pagemodel');
		$data['dataOP'] = $this->pagemodel->ambilOP();
		$this->load->view('dashboard_OP', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function dashOPAll(){
		if ($this->session->userdata('akses')=='Acount Payable' || $this->session->userdata('akses')=='Acount Receivable' || $this->session->userdata('akses')=='Truck' || $this->session->userdata('akses')=='Dokumen OP' ){
		$this->load->model('pagemodel');
		$data['dataOP'] = $this->pagemodel->ambilOP();
		$this->load->view('dashboardAll_OP', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function editOP($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Operasional'){
		$this->load->model('pagemodel');
		$data['nocon'] = $this->pagemodel->getno_con();
		$data['editop'] = $this->pagemodel->getOPedit($no_transaksi);
		$data['nomer'] =$this->pagemodel->select_no_transaksiop($no_transaksi);
		$this->load->view('edit_dashOP', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function delOP($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Operasional'){
		$this->load->model('pagemodel');
		$this->pagemodel->delTruck($no_transaksi);
		$this->pagemodel->delPettyCash($no_transaksi);
		$this->pagemodel->delDokumen_OP($no_transaksi);
		$this->pagemodel->delMonitoring($no_transaksi);
		$this->pagemodel->delAP($no_transaksi);
		$this->pagemodel->delAR($no_transaksi);
		$this->pagemodel->delOP($no_transaksi);
		$this->pagemodel->delOPwherein($no_transaksi);
		echo '<script>alert("Data Operasional berhasil dihapus!");</script>';
		redirect('Pagecontrol/dashOP', 'refresh');
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function simpanOP(){
		$this->load->model('pagemodel');

			$no_transaksi = $this->input->post('no_transaksi');
			$nomer = $this->input->post('nomer');
			$IMO = $this->input->post('IMO');
            $no_container = $this->input->post('no_container');
			$name_cust = $this->input->post('name_cust');
            $no_shipment = $this->input->post('no_shipment');
			$no_shipment2 = $this->input->post('no_shipment2');
            $no_seal = $this->input->post('no_seal');
            $full_empty = $this->input->post('full_empty');
			$reefer_dry = $this->input->post('reefer_dry');
            $stuff_date = $this->input->post('stuff_date');
			$in_out = $this->input->post('in_out');
			$origin_town = $this->input->post('origin_town');
            $dest_town = $this->input->post('dest_town');
            $vessel_name = $this->input->post('vessel_name');
			$deliv_date = $this->input->post('deliv_date');
            $arv_at_dest = $this->input->post('arv_at_dest');
            $remark_op = $this->input->post('remark_op');
            $tgl_door = $this->input->post('tgl_door');
            $tgl_kon_masuk = $this->input->post('tgl_kon_masuk');
            $no_ship_uli = $this->input->post('no_ship_uli');
            $tuj_ship_uli = $this->input->post('tuj_ship_uli');
			$tgl_door_uli = $this->input->post('tgl_door_uli');
            $no_ship_uli2 = $this->input->post('no_ship_uli2');
            $tuj_ship_uli2 = $this->input->post('tuj_ship_uli2');
            $tgl_door_uli2 = $this->input->post('tgl_door_uli2');

		$data = array (
            'no_transaksi' => $this->input->post('no_transaksi'),
        	'IMO' => $this->input->post('IMO'),
            'no_container' => $this->input->post('no_container'),
			'name_cust' => $this->input->post('name_cust'),
            'no_shipment' => $this->input->post('no_shipment'),
			'no_shipment2' => $this->input->post('no_shipment2'),
            'no_seal' => $this->input->post('no_seal'),
            'full_empty' => $this->input->post('full_empty'),
			'reefer_dry' => $this->input->post('reefer_dry'),
            'stuff_date' => $this->input->post('stuff_date'),
			'in_out' => $this->input->post('in_out'),
			'origin_town' => $this->input->post('origin_town'),
            'dest_town' => $this->input->post('dest_town'),
            'vessel_name' => $this->input->post('vessel_name'),
			'deliv_date' => $this->input->post('deliv_date'),
            'arv_at_dest' => $this->input->post('arv_at_dest'),
            'remark_op' => $this->input->post('remark_op'),
            'tgl_door' => $this->input->post('tgl_door'),
            'tgl_kon_masuk' => $this->input->post('tgl_kon_masuk'),
            'no_ship_uli' => $this->input->post('no_ship_uli'),
            'tuj_ship_uli' => $this->input->post('tuj_ship_uli'),
			'tgl_door_uli' => $this->input->post('tgl_door_uli'),
            'no_ship_uli2' => $this->input->post('no_ship_uli2'),
            'tuj_ship_uli2' => $this->input->post('tuj_ship_uli2'),
            'tgl_door_uli2' => $this->input->post('tgl_door_uli2')
        	);

		$where = array (
			'no_transaksi'=> $no_transaksi );

		$rule = $this->pagemodel->getno_transaksiop($no_transaksi);
		if ($rule)  {
			$data['editop'] = $this->pagemodel->simpanData($where,$data,'op');  }
		else {
			$data['editop'] = $this->pagemodel->createData($data,'op'); }
		//edit di tabel OP_IN
		$no_container_in = $this->input->post('no_container');

		$data2 = array (
            'no_container_in' => $this->input->post('no_container')
        	);

		$where2 = array (
			'no_transaksi_in'=> $no_transaksi );

		$rulein = $this->pagemodel->getno_transaksiin($no_transaksi);
		if ($rulein)  {
		$this->pagemodel->simpanData($where2,$data2,'op_in');  }
		//edit di tabel OP where in_out == in
		$id = $this->pagemodel->select_no_transaksiop($no_transaksi);
		// $nomer = $this->input->post('id');

		$data3 = array (
            'no_container' => $this->input->post('no_container')
        	);

		$where3 = array (
			'no_transaksi' => $nomer);

		$rule3 = $this->pagemodel->getno_transaksiopin($nomer);
		if ($rule3)  {
		$this->pagemodel->simpanData($where3,$data3,'op');  }

		echo '<script>alert("Data Operasional berhasil diperbaharui!");</script>';
		redirect('Pagecontrol/dashOP', 'refresh');
	}
	// OP

	// AR
	function formAR(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Receivable'){
		$this->load->helper('url');
		$this->load->model('pagemodel');
		$data['noIMO'] = $this->pagemodel->getIMO();
		$this->load->view('createAR_v', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	 public function dashAR(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Receivable' || $this->session->userdata('akses')=='Pajak'){
		$this->load->model('pagemodel');
		$data['dataAR'] = $this->pagemodel->getAR();
		$this->load->view('dashboard_AR', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function editAR($imo){
	if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Receivable'){
		$this->load->model('pagemodel');
		$data['editar'] = $this->pagemodel->getARedit($imo);
		$this->load->view('edit_dashAR', $data);
		}
	else if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Pajak'){
		$this->load->model('pagemodel');
		$data['editar'] = $this->pagemodel->getARedit($imo);
		$this->load->view('edit_dashPajak', $data);
		}
	else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function delAR($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Receivable'){
		$this->load->model('pagemodel');
		$this->pagemodel->delAR($no_transaksi);
		echo '<script>alert("Data Acount Receivable berhasil Dihapus!");</script>';
		redirect('Pagecontrol/dashAR', 'refresh');
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function simpanAR(){
		$this->load->model('pagemodel');
	
			$no_transaksiar = $this->input->post('no_transaksiar');
            $IMOar = $this->input->post('IMOar');
			$no_inv_ship = $this->input->post('no_inv_ship');
			$tgl_dok_ship = $this->input->post('tgl_dok_ship');
			$tgl_inv_ship = $this->input->post('tgl_inv_ship');
            $inv_ship_am = $this->input->post('inv_ship_am');
            $no_faktur_ship = $this->input->post('no_faktur_ship');
            $tgl_inv_ship_pay = $this->input->post('tgl_inv_ship_pay');
            $inv_ship_paid_am = $this->input->post('inv_ship_paid_am');
            $no_inv_ship2 = $this->input->post('no_inv_ship2');
            $tgl_dok_ship2 = $this->input->post('tgl_dok_ship2');
            $tgl_inv_ship2 = $this->input->post('tgl_inv_ship2');
            $inv_ship_am2 = $this->input->post('inv_ship_am2');
            $no_faktur_ship2 = $this->input->post('no_faktur_ship2');
            $tgl_inv_ship_pay2 = $this->input->post('tgl_inv_ship_pay2');
            $inv_ship_paid_am2 = $this->input->post('inv_ship_paid_am2');
            $no_inv_uli = $this->input->post('no_inv_uli');
            $tgl_dok_uli = $this->input->post('tgl_dok_uli');
            $tgl_inv_uli = $this->input->post('tgl_inv_uli');
           	$inv_uli_am = $this->input->post('inv_uli_am');
            $no_faktur_uli = $this->input->post('no_faktur_uli');
            $tgl_inv_uli_pay = $this->input->post('tgl_inv_uli_pay');
            $inv_uli_paid_am = $this->input->post('inv_uli_paid_am');
            $no_inv_uli2 = $this->input->post('no_inv_uli2');
            $tgl_dok_uli2 = $this->input->post('tgl_dok_uli2');
            $tgl_inv_uli2 = $this->input->post('tgl_inv_uli2');
            $inv_uli_am2 = $this->input->post('inv_uli_am2');
            $no_faktur_uli2 = $this->input->post('no_faktur_uli2');
            $tgl_inv_uli_pay2 = $this->input->post('tgl_inv_uli_pay2');
            $inv_uli_paid_am2 = $this->input->post('inv_uli_paid_am2');
            $no_plug = $this->input->post('no_plug');
            $inv_plugar_date = $this->input->post('inv_plugar_date');
            $tgl_dok_plug = $this->input->post('tgl_dok_plug');
            $inv_plugar_am = $this->input->post('inv_plugar_am');
            $plug_pay_date = $this->input->post('plug_pay_date');
            $pay_plug_paid = $this->input->post('pay_plug_paid');
            $rc_ship = $this->input->post('rc_ship');
            $rc_ship2 = $this->input->post('rc_ship2');
            $rc_uli = $this->input->post('rc_uli');
            $rc_uli2 = $this->input->post('rc_uli2');
            $rc_plug = $this->input->post('rc_plug');

		$data = array (
            'no_transaksiar' => $this->input->post('no_transaksiar'),
            'IMOar' => $this->input->post('IMOar'),
			'no_inv_ship' => $this->input->post('no_inv_ship'),
			'tgl_dok_ship' => $this->input->post('tgl_dok_ship'),
			'tgl_inv_ship' => $this->input->post('tgl_inv_ship'),
            'inv_ship_am' => $this->input->post('inv_ship_am'),
            'no_faktur_ship' => $this->input->post('no_faktur_ship'),
            'tgl_inv_ship_pay' => $this->input->post('tgl_inv_ship_pay'),
            'inv_ship_paid_am' => $this->input->post('inv_ship_paid_am'),
            'no_inv_ship2' => $this->input->post('no_inv_ship2'),
            'tgl_dok_ship2' => $this->input->post('tgl_dok_ship2'),
            'tgl_inv_ship2' => $this->input->post('tgl_inv_ship2'),
            'inv_ship_am2' => $this->input->post('inv_ship_am2'),
            'no_faktur_ship2' => $this->input->post('no_faktur_ship2'),
            'tgl_inv_ship_pay2' => $this->input->post('tgl_inv_ship_pay2'),
            'inv_ship_paid_am2' => $this->input->post('inv_ship_paid_am2'),
            'no_inv_uli' => $this->input->post('no_inv_uli'),
            'tgl_dok_uli' => $this->input->post('tgl_dok_uli'),
            'tgl_inv_uli' => $this->input->post('tgl_inv_uli'),
            'inv_uli_am' => $this->input->post('inv_uli_am'),
            'no_faktur_uli' => $this->input->post('no_faktur_uli'),
            'tgl_inv_uli_pay' => $this->input->post('tgl_inv_uli_pay'),
            'inv_uli_paid_am' => $this->input->post('inv_uli_paid_am'),
            'no_inv_uli2' => $this->input->post('no_inv_uli2'),
            'tgl_dok_uli2' => $this->input->post('tgl_dok_uli2'),
            'tgl_inv_uli2' => $this->input->post('tgl_inv_uli2'),
            'inv_uli_am2' => $this->input->post('inv_uli_am2'),
            'no_faktur_uli2' => $this->input->post('no_faktur_uli2'),
            'tgl_inv_uli_pay2' => $this->input->post('tgl_inv_uli_pay2'),
            'inv_uli_paid_am2' => $this->input->post('inv_uli_paid_am2'),
            'no_plug' => $this->input->post('no_plug'),
            'inv_plugar_date' => $this->input->post('inv_plugar_date'),
            'tgl_dok_plug' => $this->input->post('tgl_dok_plug'),
            'inv_plugar_am' => $this->input->post('inv_plugar_am'),
            'plug_pay_date' => $this->input->post('plug_pay_date'),
            'pay_plug_paid' => $this->input->post('pay_plug_paid'),
            'rc_ship' => $this->input->post('rc_ship'),
            'rc_ship2' => $this->input->post('rc_ship2'),
            'rc_uli' => $this->input->post('rc_uli'),
            'rc_uli2' => $this->input->post('rc_uli2'),
            'rc_plug' => $this->input->post('rc_plug')
        	);

		$where = array (
			'no_transaksiar'=> $no_transaksiar );

		$rule = $this->pagemodel->getno_transaksiar($no_transaksiar);
		if ($rule)  {
			$data['editar'] = $this->pagemodel->simpanData($where,$data,'ar');  }
		else {
			$data['editar'] = $this->pagemodel->createData($data,'ar'); }
		
		echo '<script>alert("Data Acount Receivable berhasil Diperbaharui!");</script>';
		redirect('Pagecontrol/dashAR', 'refresh');
	}

	function detCust($imo){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Receivable'){
		$this->load->model('pagemodel');
		$data['detCust'] = $this->pagemodel->getpay($imo);
		$data['hasil']= 0;
		$this->load->view('det_Cust', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function hitung()
	{
		$bil1 = $this->input->post('bil1');
		$bil2 = $this->input->post('bil2');
		$hitung = $this->input->post('hitung');
		$hasil = 0;
		if($hitung == 'PPN 1%'){
			$hasil = $bil1 + ($bil1*(0.01)) + $bil2 - ($bil1*(0.02));
		}
		if ($hitung == 'PPN 10%') {
			$hasil = $bil1 + ($bil1*(0.1)) + $bil2 - ($bil1*(0.02));
		}
		$nilai['hasil'] = $hasil;
		echo json_encode($nilai);
	}

	function konversi()
	{
		$id = $this->input->post('id');
		$data = number_format($id,2,',','.');
		echo json_encode($data);
	}

	function simpandetCust(){
		$IMO=$this->input->post('IMO');
		$this->load->model('pagemodel');
		$pay_inv = $this->input->post('value');
		$data['detCust'] = $this->pagemodel->simpanpayCust($IMO,$pay_inv);
		redirect('Pagecontrol/dashAR');
	}
	// AR

	// AP
	function formAP(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Payable'){
		$this->load->helper('url');
		$this->load->model('pagemodel');
		$data['noIMO'] = $this->pagemodel->getIMO();
		$data['nameAgen'] = $this->pagemodel->getname_agen();
		$data['nameShip'] = $this->pagemodel->getname_ship();
		$this->load->view('createAP_v', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function dashAP(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Payable'){
		$this->load->model('pagemodel');
		$data['dataAP'] = $this->pagemodel->getAP();
		$this->load->view('dashboard_AP', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function editAP($no_transaksi){
	if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Payable'){
		$this->load->model('pagemodel');
		// $this->pagemodel->getvarAPedit($no_transaksi,$IMO);
		$data['editap'] = $this->pagemodel->getAPedit($no_transaksi);
		$data['name_agen'] = $this->pagemodel->getname_agen();
		$data['name_ship'] = $this->pagemodel->getname_ship();
		$this->load->view('edit_dashAP', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function delAP($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Acount Payable'){
		$this->load->model('pagemodel');
		$this->pagemodel->delAP($no_transaksi);
		echo '<script>alert("Data Acount Payable berhasil Dihapus!");</script>';
		redirect('Pagecontrol/dashAP', 'refresh');
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function simpanAP(){
		$this->load->model('pagemodel');

			$no_transaksiap = $this->input->post('no_transaksiap');
            $IMOap = $this->input->post('IMOap');
            $inv_ag = $this->input->post('inv_ag');
        	$name_ag = $this->input->post('name_ag');
            $inv_ag = $this->input->post('inv_ag');
			$inv_ag_date = $this->input->post('inv_ag_date');
            $tgl_trima_inv_ag = $this->input->post('tgl_trima_inv_ag');
            $inv_ag_am = $this->input->post('inv_ag_am');
            $pay_ag = $this->input->post('pay_ag');
			$date_ag = $this->input->post('date_ag');

			$inv_genset = $this->input->post('inv_genset');
            $inv_genset_date = $this->input->post('inv_genset_date');
            $tgl_trima_inv_genset = $this->input->post('tgl_trima_inv_genset');
            $inv_genset_am = $this->input->post('inv_genset_am');
            $pay_genset = $this->input->post('pay_genset');
            $date_genset = $this->input->post('date_genset');

            $inv_bjti = $this->input->post('inv_bjti');
            $inv_date_bjti = $this->input->post('inv_date_bjti');
            $tgl_trima_inv_bjti = $this->input->post('tgl_trima_inv_bjti');
            $inv_bjti_am = $this->input->post('inv_bjti_am');
            $pay_bjti = $this->input->post('pay_bjti');
             $date_bjti = $this->input->post('date_bjti');

			$name_ship = $this->input->post('name_ship');
            $inv_ship = $this->input->post('inv_ship');
            $inv_ship_date = $this->input->post('inv_ship_date');
            $tgl_trima_inv_ship = $this->input->post('tgl_trima_inv_ship');
            $inv_ship_am = $this->input->post('inv_ship_am');
            $pay_ship = $this->input->post('pay_ship');
            $date_ship = $this->input->post('date_ship');

            $inv_thc = $this->input->post('inv_thc');
			$inv_thc_date = $this->input->post('inv_thc_date');
            $tgl_trima_inv_thc = $this->input->post('tgl_trima_inv_thc');
            $inv_thc_am = $this->input->post('inv_thc_am');
            $pay_thc = $this->input->post('pay_thc');
			$date_thc = $this->input->post('date_thc');

            $inv_plug = $this->input->post('inv_plug');
			$inv_plug_date = $this->input->post('inv_plug_date');
            $tgl_trima_inv_plug = $this->input->post('tgl_trima_inv_plug');
            $inv_plug_am = $this->input->post('inv_plug_am');
            $pay_plug = $this->input->post('pay_plug');
			$date_plug = $this->input->post('date_plug');
			$tgl_kirim_plug_ar = $this->input->post('tgl_kirim_plug_ar');

            $inv_buruh = $this->input->post('inv_buruh');
            $inv_buruh_date = $this->input->post('inv_buruh_date');
            $tgl_trima_inv_buruh = $this->input->post('tgl_trima_inv_buruh');
            $inv_buruh_am = $this->input->post('inv_buruh_am');
            $pay_buruh = $this->input->post('pay_buruh');
            $date_buruh = $this->input->post('date_buruh');

            $inv_uli = $this->input->post('inv_uli');
            $tgl_inv_uli = $this->input->post('tgl_inv_uli');
            $tgl_trima_uli = $this->input->post('tgl_trima_uli');
            $inv_uli_am = $this->input->post('inv_uli_am');
            $pay_ship_uli = $this->input->post('pay_ship_uli');
            $pay_date_uli = $this->input->post('pay_date_uli');
            $inv_uli2 = $this->input->post('inv_uli2');
            $tgl_inv_uli2 = $this->input->post('tgl_inv_uli2');
            $tgl_trima_uli2 = $this->input->post('tgl_trima_uli2');
            $inv_uli_am2 = $this->input->post('inv_uli_am2');
            $pay_ship_uli2 = $this->input->post('pay_ship_uli2');
            $pay_date_uli2 = $this->input->post('pay_date_uli2');

            $inv_lain = $this->input->post('inv_lain');
			$inv_lain_date = $this->input->post('inv_lain_date');
            $tgl_trima_inv_lain = $this->input->post('tgl_trima_inv_lain');
            $inv_lain_am = $this->input->post('inv_lain_am');
            $pay_lain = $this->input->post('pay_lain');
			$date_lain = $this->input->post('date_lain');	
			$no_bl = $this->input->post('no_bl');
			$no_pem = $this->input->post('no_pem');		
	

		$data = array (
            'no_transaksiap' => $this->input->post('no_transaksiap'),
            'IMOap' => $this->input->post('IMOap'),
            'inv_ag' => $this->input->post('inv_ag'),
        	'name_ag' => $this->input->post('name_ag'),
            'inv_ag' => $this->input->post('inv_ag'),
			'inv_ag_date' => $this->input->post('inv_ag_date'),
            'tgl_trima_inv_ag' => $this->input->post('tgl_trima_inv_ag'),
            'inv_ag_am' => $this->input->post('inv_ag_am'),
            'pay_ag' => $this->input->post('pay_ag'),
			'date_ag' => $this->input->post('date_ag'),

			'inv_genset' => $this->input->post('inv_genset'),
            'inv_genset_date' => $this->input->post('inv_genset_date'),
            'tgl_trima_inv_genset' => $this->input->post('tgl_trima_inv_genset'),
            'inv_genset_am' => $this->input->post('inv_genset_am'),
            'pay_genset' => $this->input->post('pay_genset'),
            'date_genset' => $this->input->post('date_genset'),

            'inv_bjti' => $this->input->post('inv_bjti'),
            'inv_date_bjti' => $this->input->post('inv_date_bjti'),
            'tgl_trima_inv_bjti' => $this->input->post('tgl_trima_inv_bjti'),
            'inv_bjti_am' => $this->input->post('inv_bjti_am'),
            'pay_bjti' => $this->input->post('pay_bjti'),
            'date_bjti' => $this->input->post('date_bjti'),

			'name_ship' => $this->input->post('name_ship'),
            'inv_ship' => $this->input->post('inv_ship'),
            'inv_ship_date' => $this->input->post('inv_ship_date'),
            'tgl_trima_inv_ship' => $this->input->post('tgl_trima_inv_ship'),
            'inv_ship_am' => $this->input->post('inv_ship_am'),
            'pay_ship' => $this->input->post('pay_ship'),
            'date_ship' => $this->input->post('date_ship'),

            'inv_thc' => $this->input->post('inv_thc'),
			'inv_thc_date' => $this->input->post('inv_thc_date'),
            'tgl_trima_inv_thc' => $this->input->post('tgl_trima_inv_thc'),
            'inv_thc_am' => $this->input->post('inv_thc_am'),
            'pay_thc' => $this->input->post('pay_thc'),
			'date_thc' => $this->input->post('date_thc'),

            'inv_plug' => $this->input->post('inv_plug'),
			'inv_plug_date' => $this->input->post('inv_plug_date'),
            'tgl_trima_inv_plug' => $this->input->post('tgl_trima_inv_plug'),
            'inv_plug_am' => $this->input->post('inv_plug_am'),
            'pay_plug' => $this->input->post('pay_plug'),
			'date_plug' => $this->input->post('date_plug'),
			'tgl_kirim_plug_ar' => $this->input->post('tgl_kirim_plug_ar'),

            'inv_buruh' => $this->input->post('inv_buruh'),
            'inv_buruh_date' => $this->input->post('inv_buruh_date'),
            'tgl_trima_inv_buruh' => $this->input->post('tgl_trima_inv_buruh'),
            'inv_buruh_am' => $this->input->post('inv_buruh_am'),
            'pay_buruh' => $this->input->post('pay_buruh'),
            'date_buruh' => $this->input->post('date_buruh'),

            'inv_uli' => $this->input->post('inv_uli'),
            'tgl_inv_uli' => $this->input->post('tgl_inv_uli'),
            'tgl_trima_uli' => $this->input->post('tgl_trima_uli'),
            'inv_uli_am' => $this->input->post('inv_uli_am'),
            'pay_ship_uli' => $this->input->post('pay_ship_uli'),
            'pay_date_uli' => $this->input->post('pay_date_uli'),
            'inv_uli2' => $this->input->post('inv_uli2'),
            'tgl_inv_uli2' => $this->input->post('tgl_inv_uli2'),
            'tgl_trima_uli2' => $this->input->post('tgl_trima_uli2'),
            'inv_uli_am2' => $this->input->post('inv_uli_am2'),
            'pay_ship_uli2' => $this->input->post('pay_ship_uli2'),
            'pay_date_uli2' => $this->input->post('pay_date_uli2'),

            'inv_lain' => $this->input->post('inv_lain'),
			'inv_lain_date' => $this->input->post('inv_lain_date'),
            'tgl_trima_inv_lain' => $this->input->post('tgl_trima_inv_lain'),
            'inv_lain_am' => $this->input->post('inv_lain_am'),
            'pay_lain' => $this->input->post('pay_lain'),
			'date_lain' => $this->input->post('date_lain'),
			'no_bl' => $this->input->post('no_bl'),
			'no_pem' => $this->input->post('no_pem')
        	);

		$where = array (
			'no_transaksiap'=> $no_transaksiap );

		$rule = $this->pagemodel->getno_transaksiap($no_transaksiap);
		if ($rule)  {
			$data['editap'] = $this->pagemodel->simpanData($where,$data,'ap');  }
		else {
			$data['editap'] = $this->pagemodel->createData($data,'ap'); }

		echo '<script>alert("Data Acount Payable berhasil Diperbaharui!");</script>';
		redirect('Pagecontrol/dashAP', 'refresh');
	} 
	// AP

	//Truck
	function formTruck(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Truck'){
		$this->load->helper('url');
		$this->load->model('pagemodel');
		$data['noIMO'] = $this->pagemodel->getIMO();
		$this->load->view('createTruck_v', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function dashTruck(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Truck'){
		$this->load->model('pagemodel');
		$data['dataTruck'] = $this->pagemodel->getTruck();
		$this->load->view('dashboard_Truck', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function editTruck($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Truck'){
		$this->load->model('pagemodel');
		$data['edittruck'] = $this->pagemodel->getTruckedit($no_transaksi);
		$this->load->view('edit_dashTruck', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function delTruck($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Truck'){
		$this->load->model('pagemodel');
		$this->pagemodel->delTruck($no_transaksi);
		echo '<script>alert("Data Truck berhasil dihapus!");</script>';
		redirect('Pagecontrol/dashTruck', 'refresh');
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function simpanTruck(){
		$this->load->model('pagemodel');
			$no_transaksitr = $this->input->post('no_transaksitr');
        	$IMOtr = $this->input->post('IMOtr');
        	$inv_truck = $this->input->post('inv_truck');
			$name = $this->input->post('name');
			$date = $this->input->post('date');
			$tujuan = $this->input->post('tujuan');
			$pesanan = $this->input->post('pesanan');
			$no_pol = $this->input->post('no_pol');
			$jam = $this->input->post('jam');
			$muatan = $this->input->post('muatan');
			$ukuran = $this->input->post('ukuran');
			$b_jajan = $this->input->post('b_jajan');
			$b_kom = $this->input->post('b_kom');
			$b_kawal = $this->input->post('b_kawal');
			$b_laintr = $this->input->post('b_laintr');

		$data = array (
            'no_transaksitr' => $no_transaksitr,
        	'IMOtr' => $IMOtr,
        	'inv_truck' => $inv_truck,
			'name' => $name,
			'date' => $date,
			'tujuan' => $tujuan,
			'pesanan' => $pesanan,
			'no_pol' => $no_pol,
			'jam' => $jam,
			'muatan' => $muatan,
			'ukuran' => $ukuran,
			'b_jajan' => $b_jajan,
			'b_kom' => $b_kom,
			'b_kawal' => $b_kawal,
			'b_laintr' => $b_laintr
        	);

		$where = array (
			'no_transaksitr'=> $no_transaksitr );

		$rule = $this->pagemodel->getno_transaksitr($no_transaksitr);
		if ($rule)  {
			$data['edittruck'] = $this->pagemodel->simpanData($where,$data,'truck');  }
		else {
			$data['edittruck'] = $this->pagemodel->createData($data,'truck'); }

		echo '<script>alert("Data Truck berhasil diperbaharui!");</script>';
		redirect('Pagecontrol/dashTruck', 'refresh');
	}
	//Truck

	//Petty Cash
	function dashPettyCash(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Petty Cash'){
		$this->load->model('pagemodel');
		$data['datapettycash'] = $this->pagemodel->getPettyCash();
		$this->load->view('dashboard_PettyCash', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function editPettyCash($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Petty Cash'){
		$this->load->model('pagemodel');
		$data['editpettycash'] = $this->pagemodel->getPettyCashedit($no_transaksi);
		$this->load->view('edit_dashPettyCash', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function delPettyCash($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Petty Cash'){
		$this->load->model('pagemodel');
		$this->pagemodel->delPettyCash($no_transaksi);
		echo '<script>alert("Data Petty Cash berhasil dihapus!");</script>';
		redirect('Pagecontrol/dashPettyCash', 'refresh');
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function simpanPettyCash(){
		$this->load->model('pagemodel');

            $no_transaksipc =$this->input->post('no_transaksipc');
            $IMOpc =$this->input->post('IMOpc');
            $b_stuff = $this->input->post('b_stuff');
            $plus_b_stuff =$this->input->post('plus_b_stuff');
            $electricson =$this->input->post('electricson');
            $b_karantina =$this->input->post('b_karantina');
            $b_handlfull =$this->input->post('b_handlfull');
            $b_lolo =$this->input->post('b_lolo');
            $b_adm =$this->input->post('b_adm');
            $b_lain =$this->input->post('b_lain');

		$data = array (
            'no_transaksipc' => $no_transaksipc,
            'IMOpc' => $IMOpc,
            'b_stuff' => $b_stuff,
            'plus_b_stuff' => $plus_b_stuff,
            'electricson' => $electricson,
            'b_karantina' => $b_karantina,
            'b_handlfull' => $b_handlfull,
            'b_lolo' => $b_lolo,
            'b_adm' => $b_adm,
            'b_lain' => $b_lain
            );

		$where = array (
			'no_transaksipc'=> $no_transaksipc );

		$rule = $this->pagemodel->getno_transaksipc($no_transaksipc);
		if ($rule)  {
			$data['editpettycash'] = $this->pagemodel->simpanData($where,$data,'pettycash');  }
		else {
			$data['editpettycash'] = $this->pagemodel->createData($data,'pettycash'); }

		echo '<script>alert("Data Petty Cash berhasil diperbaharui!");</script>';
		redirect('Pagecontrol/dashPettyCash', 'refresh');
	}
	//Petty Cash

	//Monitoring Container
	function dashMonitoring(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Monitoring Container'){
		$this->load->model('pagemodel');
		$data['datamonit'] = $this->pagemodel->ambilOPmonitor();
		$this->load->view('dashboard_Monitoring', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function editMonitoring($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Monitoring Container'){
		$this->load->model('pagemodel');
		$data['editmonitoring'] = $this->pagemodel->getMonitoringedit($no_transaksi);
		$this->load->view('edit_dashMonitoring', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function delMonitoring($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Monitoring Container'){
		$this->load->model('pagemodel');
		$this->pagemodel->delMonitoring($no_transaksi);
		echo '<script>alert("Data Monitoring berhasil dihapus!");</script>';
		redirect('Pagecontrol/dashMonitoring', 'refresh');
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function simpanMonitoring(){
		$this->load->model('pagemodel');

            $no_transaksimc = $this->input->post('no_transaksimc');
            $IMOmc = $this->input->post('IMOmc');
            $full_emptymc = $this->input->post('full_emptymc');
            $cust_balik = $this->input->post('cust_balik');
            $no_sealmc = $this->input->post('no_sealmc');
            $tuj_balik = $this->input->post('tuj_balik');
            $kapal_balik = $this->input->post('kapal_balik');
            $stuff_date_balik = $this->input->post('stuff_date_balik');
            $ETD_balik = $this->input->post('ETD_balik');
            $ETA_balik = $this->input->post('ETA_balik');
            $tgl_dooring = $this->input->post('tgl_dooring');

		$data = array (
            'no_transaksimc' => $no_transaksimc,
            'IMOmc' => $IMOmc,
            'full_emptymc' => $full_emptymc,
            'cust_balik' => $cust_balik,
            'no_sealmc' => $no_sealmc,
            'tuj_balik' => $tuj_balik,
            'kapal_balik' => $kapal_balik,
            'stuff_date_balik' => $stuff_date_balik,
            'ETD_balik' => $ETD_balik,
            'ETA_balik' => $ETA_balik,
            'tgl_dooring' => $tgl_dooring
            );

		$where = array (
			'no_transaksimc'=> $no_transaksimc );

		$rule = $this->pagemodel->getno_transaksimc($no_transaksimc);
		if ($rule)  {
			$data['editmonitoring'] = $this->pagemodel->simpanData($where,$data,'m_cont');  }
		else {
			$data['editmonitoring'] = $this->pagemodel->createData($data,'m_cont'); }

		echo '<script>alert("Data Monitoring berhasil diperbaharui!");</script>';
		redirect('Pagecontrol/dashMonitoring', 'refresh');
	}
	//Monitoring Container

	//Dokumen OP
	function dashDokumen_OP(){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Dokumen OP'){
		$this->load->model('pagemodel');
		$data['dataDokumen_OP'] = $this->pagemodel->getDokumen_OP();
		$this->load->view('dashboard_Dokumen_OP', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function editDokumen_OP($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Dokumen OP'){
		$this->load->model('pagemodel');
		$data['editDokumen_OP'] = $this->pagemodel->getDokumen_OPedit($no_transaksi);
		$this->load->view('edit_dashDokumen_OP', $data);
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function delDokumen_OP($no_transaksi){
		if ($this->session->userdata('akses')=='Supervisor' || $this->session->userdata('akses')=='Dokumen OP'){
		$this->load->model('pagemodel');
		$this->pagemodel->delDokumen_OP($no_transaksi);
		echo '<script>alert("Data Dokumen OP berhasil dihapus!");</script>';
		redirect('Pagecontrol/dashDokumen_OP', 'refresh');
		}else{
			echo "Anda tidak berhak mengakses halaman ini";
		}
	}

	function simpanDokumen_OP(){
		$this->load->model('pagemodel');

            $no_transaksidok = $this->input->post('no_transaksidok');
            $IMOdok = $this->input->post('IMOdok');
            $tgl_dok = $this->input->post('tgl_dok');
            $tgl_dok_ship_ar = $this->input->post('tgl_dok_ship_ar');
            $tgl_dok2 = $this->input->post('tgl_dok2');
            $tgl_dok_ship_ar2 = $this->input->post('tgl_dok_ship_ar2');
            $tgl_dok_uli = $this->input->post('tgl_dok_uli');
            $tgl_kirim_dok_uli = $this->input->post('tgl_kirim_dok_uli');
            $tgl_dok_uli2 = $this->input->post('tgl_dok_uli2');
            $tgl_kirim_dok_uli2 = $this->input->post('tgl_kirim_dok_uli2');
            $remark_dok = $this->input->post('remark_dok');
            $remark_dok2 = $this->input->post('remark_dok2');
            $remark_uli = $this->input->post('remark_uli');
            $remark_uli2 = $this->input->post('remark_uli2');

		$data = array (
            'no_transaksidok' => $no_transaksidok,
            'IMOdok' => $IMOdok,
            'tgl_dok' => $tgl_dok,
            'tgl_dok_ship_ar' => $tgl_dok_ship_ar,
            'tgl_dok2' => $tgl_dok2,
            'tgl_dok_ship_ar2' => $tgl_dok_ship_ar2,
            'tgl_kirim_dok_uli' => $tgl_kirim_dok_uli,
            'tgl_dok_uli' => $tgl_dok_uli,
            'tgl_kirim_dok_uli2' => $tgl_kirim_dok_uli2,
            'tgl_dok_uli2' => $tgl_dok_uli2,
            'remark_dok' => $remark_dok,
            'remark_dok2' => $remark_dok2,
            'remark_uli' => $remark_uli,
            'remark_uli2' => $remark_uli2,
            );

		$where = array (
			'no_transaksidok'=> $no_transaksidok );

		$rule = $this->pagemodel->getno_transaksidok($no_transaksidok);
		if ($rule)  {
			$data['editdokumen_OP'] = $this->pagemodel->simpanData($where,$data,'dokumen_OP');  }
		else {
			$data['editdokumen_OP'] = $this->pagemodel->createData($data,'dokumen_OP'); }

		echo '<script>alert("Data Dokumen OP berhasil diperbaharui!");</script>';
		redirect('Pagecontrol/dashDokumen_OP', 'refresh');
	}
	//Dokumen OP

	// EXPORT TO EXCEL
	function cetak(){
		$this->load->helper('url');
		$this->load->model('pagemodel');
		$this->load->library("excel");
		$object = new PHPExcel();
		$object->setActiveSheetIndex(0);
		$object->getActiveSheet()->setTitle('Sample Sheet');
        $object->getActiveSheet()->getStyle("A1:E1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#4169E1')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
        $object->getActiveSheet()->getStyle("F1:H1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#CD5C5C')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
        $object->getActiveSheet()->getStyle("I1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#4169E1')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
        $object->getActiveSheet()->getStyle("J1:L1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#CD5C5C')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
			$object->getActiveSheet()->getStyle("M1:Z1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#4169E1')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            $object->getActiveSheet()->getStyle("AA1:AC1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#CD5C5C')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            $object->getActiveSheet()->getStyle("AD1:AF1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#4169E1')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            $object->getActiveSheet()->getStyle("AG1:AI1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#CD5C5C')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            $object->getActiveSheet()->getStyle("AJ1:AK1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#4169E1')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            
            //OP dan DOK OP stop
            $object->getActiveSheet()->getStyle("AL1:CX1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#BDB76B')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            //AP stop
            $object->getActiveSheet()->getStyle("CY1:EL1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#483D8B')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            //AR stop
            $object->getActiveSheet()->getStyle("EM1:EY1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#778899')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            //Truck stop
            $object->getActiveSheet()->getStyle("EZ1:FG1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#CD853F')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            //pettycash stop
            $object->getActiveSheet()->getStyle("FH1:GF1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#483D8B')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            //monitoring stop
            
		$table_columns = array("No Transaksi" , "IMO" , "No. & Size Container" , "Name CUST" , "No. Shipment 1" ,"Tanggal Terima Dokumen Shipment 1" ,"Tanggal Kirim Dokumen Shipment 1 ke AR" ,"Remark Shipment 1 ke AR", "No. Shipment 2", "Tanggal Terima Dokumen Shipment 2" , "Tanggal Kirim Dokumen Shipment 2 ke AR" , "Remark Shipment 2 ke AR","No. Seal" , "Full / Empty" ,"Reefer / Dry" ,"Stuffing Date" ,"Container In / Out" ,"Origin Town" , "Destination Town" , "Vessel Name" , "Delivery From JKT (ETD)" , "Arv At Dest (ETA)" ,"Tanggal Container Masuk", "Remark","Tanggal Dooring Container" , "No. Shipment ULI HUB 1" ,"Tanggal Terima Dokumen Shipment ULI HUB 1" ,  "Tanggal Kirim Dokumen Shipment ULI HUB 1" ,"Remark Shipment ULI HUB 1","Tujuan Shipment ULI HUB 1" , "Tgl Dooring Shipment ULI HUB 1" ,"No. Shipment ULI HUB 2" ,"Tanggal Terima Dokumen Shipment ULI HUB 2" , "Tanggal Kirim Dokumen Shipment ULI HUB 2" ,"Remark Shipment ULI HUB 2","Tujuan Shipment ULI HUB 2" ,"Tgl Dooring Shipment ULI HUB 2" ,
			//25 & 12
			"Name AGEN" ,"Invoice No. AGEN" ,"Invoice AGEN date" ,"Tgl Terima Invoice" ,"Invoice AGEN Amount" ,"Payment amount AGEN" ,"Payment Date AGEN" ,"No B/L","Pembayaran", 
			"Invoice No. GENSET" , "Invoice GENSET date" , "Tgl Terima Invoice" , "Invoice GENSET Amount" , "Payment GENSET" , "Payment Date GENSET" ,
			"Invoice No. BJTI" , "Invoice BJTI date" , "Tgl Terima Invoice" , "Invoice BJTI Amount" , "Payment BJTI" , "Payment Date BJTI" , 
			"Name SHIP" , "Invoice No. SHIP" , "Invoice SHIP date" , "Tgl Terima Invoice" , "Invoice SHIP Amount" , "Payment SHIP" , "Payment Date SHIP" , 
			"Invoice No. THC" , "Invoice THC date" , "Tgl Terima Invoice" , "Invoice THC Amount" , "Payment THC" , "Payment Date THC" , 
			"Invoice No. PLUG" , "Invoice PLUG date" , "Tgl Terima Invoice" , "Invoice PLUG Amount" , "Payment PLUG" , "Payment Date PLUG" ,  "Tanggal Kirim Dokumen ke AR" ,
			"Invoice No. Buruh" , "Invoice Buruh date" , "Tgl Terima Invoice" , "Invoice Buruh Amount" , "Payment Buruh" , "Payment Date Buruh" , 
			"No. Invoice Shipment ULI HUB 1" , "Tgl Invoice Shipment ULI HUB 1" , "Tgl Terima Invoice" , "Invoice Shipment ULI HUB 1 Amount" , "Payment Shipment ULI HUB 1" , "Payment Date Shipment ULI HUB 1" , 
			"No. Invoice Shipment ULI HUB 2" , "Tgl Invoice Shipment ULI HUB 2" , "Tgl Terima Invoice" , "Invoice Shipment ULI HUB 2 Amount" , "Payment Shipment ULI HUB 2" ,"Payment Date Shipment ULI HUB 2" , 
			"Invoice No. LAIN" , "Invoice LAIN date" , "Tgl Terima Invoice" , "Invoice LAIN Amount" , "Payment LAIN" , "Payment Date LAIN" ,
			//65
			"No. Invoice Shipment 1", "Tanggal Terima Dokumen Shipment 1", "Invoice Shipment 1 Date", "Invoice Shipment 1 Amount", "No. Faktur Pajak Shipment 1", "Invoice Shipment 1 Payment Date","No. RC Shipment 1" ,"Invoice Shipment 1 Paid Amount", 
			"No. Invoice Shipment 2", "Tanggal Terima Dokumen Shipment 2", "Invoice Shipment 2 Date", "Invoice Shipment 2 Amount", "No. Faktur Pajak Shipment 2", "Invoice Shipment 2 Payment Date", "No. RC Shipment 2","Invoice Shipment 2 Paid Amount", 
			"No. Invoice ULI HUB Shipment 1", "Tanggal Terima Dokumen  ULI HUB Shipment 1", "Invoice ULI HUB Shipment 1 Date", "Invoice ULI HUB Shipment 1 Amount", "No. Faktur Pajak ULI HUB Shipment 1", "Invoice ULI HUB Shipment 1 Payment Date","No. RC ULI HUB Shipment 1", "Invoice ULI HUB Shipment 1 Paid Amount", 
			"No. Invoice ULI HUB Shipment 2", "Tanggal Terima Dokumen  ULI HUB Shipment 2", "Invoice ULI HUB Shipment 2 Date", "Invoice ULI HUB Shipment 2 Amount", "No. Faktur Pajak ULI HUB Shipment 2", "Invoice ULI HUB Shipment 2 Payment Date","No. RC Shipment 2", "Invoice ULI HUB Shipment 2 Paid Amount", 
			"No. Plug", "Invoice Plug Date", "Tanggal Terima Dokumen Plug", "Invoice Plug Amount", "Plug Payment Date","No. RC Plug", "Plug Paid Amount", "No. Faktur Pajak" ,
			//40
			"No. Invoice", "Name", "Date", "Tujuan", "Pesanan", "No Polisi", "Jam", "Muatan", "Ukuran", "Biaya Jalan", "Biaya Komisi", "Biaya Kawalan", "Biaya Lain2" ,
			//13
			"Biaya Stuffing" , "Tambahan Biaya Stuffing" , "Electrison" , "Biaya Karantina" , "Biaya Handling Full" , "Biaya Lolo" , "Biaya Adm" , "Biaya Lain-Lain" ,
			//8
		 	"No Transaksi" , "IMO" , "No. & Size Container" , "Name CUST" , "No. Shipment 1" ,"No. Shipment 2", "No. Seal" , "Full / Empty" ,"Reefer / Dry" ,"Stuffing Date" ,"Container In / Out" ,"Origin Town" , "Destination Town" , "Vessel Name" , "Delivery From JKT (ETD)" , "Arv At Dest (ETA)" ,"Tanggal Container Masuk", "Remark","Tanggal Dooring Container" , "No. Shipment ULI HUB 1" ,"Tujuan Shipment ULI HUB 1" , "Tgl Dooring Shipment ULI HUB 1" ,"No. Shipment ULI HUB 2" ,"Tujuan Shipment ULI HUB 2" ,"Tgl Dooring Shipment ULI HUB 2" ,
		 	//25
			);
		$column = 0;

		foreach($table_columns as $field)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
			$column++;
		}

		$AllData = $this->pagemodel->getAll();

		$excel_row = 2;

		foreach($AllData as $row)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->no_transaksi);
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->IMO);
			$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->no_container);
			$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->name_cust); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->no_shipment); 

			$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->tgl_dok); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->tgl_dok_ship_ar); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->remark_dok);

			$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->no_shipment2); 

			$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->tgl_dok2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->tgl_dok_ship_ar2); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->remark_dok2);

			$object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->no_seal); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->full_empty); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->reefer_dry); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row->stuff_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $row->in_out); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->origin_town);
			$object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $row->dest_town);
			$object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->vessel_name);
			$object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $row->deliv_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, $row->arv_at_dest);
			$object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $row->tgl_kon_masuk);
			$object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->remark_op);
			$object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, $row->tgl_door);
			$object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->no_ship_uli );

			$object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, $row->tgl_dok_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, $row->tgl_kirim_dok_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(28, $excel_row, $row->remark_uli);

			$object->getActiveSheet()->setCellValueByColumnAndRow(29, $excel_row, $row->tuj_ship_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(30, $excel_row, $row->tgl_door_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(31, $excel_row, $row->no_ship_uli2);

			$object->getActiveSheet()->setCellValueByColumnAndRow(32, $excel_row, $row->tgl_dok_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(33, $excel_row, $row->tgl_kirim_dok_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(34, $excel_row, $row->remark_uli2);
			
			$object->getActiveSheet()->setCellValueByColumnAndRow(35, $excel_row, $row->tuj_ship_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(36, $excel_row, $row->tgl_door_uli2);
			//OP
			$object->getActiveSheet()->setCellValueByColumnAndRow(37, $excel_row, $row->name_ag);
			$object->getActiveSheet()->setCellValueByColumnAndRow(38, $excel_row, $row->inv_ag);
			$object->getActiveSheet()->setCellValueByColumnAndRow(39, $excel_row, $row->inv_ag_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(40, $excel_row, $row->tgl_trima_inv_ag);
			$object->getActiveSheet()->setCellValueByColumnAndRow(41, $excel_row, $row->inv_ag_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(42, $excel_row, $row->pay_ag);
			$object->getActiveSheet()->setCellValueByColumnAndRow(43, $excel_row, $row->date_ag);
			$object->getActiveSheet()->setCellValueByColumnAndRow(44, $excel_row, $row->no_bl);
			$object->getActiveSheet()->setCellValueByColumnAndRow(45, $excel_row, $row->no_pem);

			$object->getActiveSheet()->setCellValueByColumnAndRow(46, $excel_row, $row->inv_genset);
			$object->getActiveSheet()->setCellValueByColumnAndRow(47, $excel_row, $row->inv_genset_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(48, $excel_row, $row->tgl_trima_inv_genset);
			$object->getActiveSheet()->setCellValueByColumnAndRow(49, $excel_row, $row->inv_genset_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(50, $excel_row, $row->pay_genset);
			$object->getActiveSheet()->setCellValueByColumnAndRow(51, $excel_row, $row->date_genset);

			$object->getActiveSheet()->setCellValueByColumnAndRow(52, $excel_row, $row->inv_bjti);
			$object->getActiveSheet()->setCellValueByColumnAndRow(53, $excel_row, $row->inv_date_bjti);
			$object->getActiveSheet()->setCellValueByColumnAndRow(54, $excel_row, $row->tgl_trima_inv_bjti);
			$object->getActiveSheet()->setCellValueByColumnAndRow(55, $excel_row, $row->inv_bjti_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(56, $excel_row, $row->pay_bjti);
			$object->getActiveSheet()->setCellValueByColumnAndRow(57, $excel_row, $row->date_bjti);

			$object->getActiveSheet()->setCellValueByColumnAndRow(58, $excel_row, $row->name_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(59, $excel_row, $row->inv_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(60, $excel_row, $row->inv_ship_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(61, $excel_row, $row->tgl_trima_inv_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(62, $excel_row, $row->inv_ship_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(63, $excel_row, $row->pay_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(64, $excel_row, $row->date_ship);

			$object->getActiveSheet()->setCellValueByColumnAndRow(65, $excel_row, $row->inv_thc);
			$object->getActiveSheet()->setCellValueByColumnAndRow(66, $excel_row, $row->inv_thc_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(67, $excel_row, $row->tgl_trima_inv_thc);
			$object->getActiveSheet()->setCellValueByColumnAndRow(68, $excel_row, $row->inv_thc_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(69, $excel_row, $row->pay_thc);
			$object->getActiveSheet()->setCellValueByColumnAndRow(70, $excel_row, $row->date_thc);

			$object->getActiveSheet()->setCellValueByColumnAndRow(71, $excel_row, $row->inv_plug);
			$object->getActiveSheet()->setCellValueByColumnAndRow(72, $excel_row, $row->inv_plug_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(73, $excel_row, $row->tgl_trima_inv_plug);
			$object->getActiveSheet()->setCellValueByColumnAndRow(74, $excel_row, $row->inv_plug_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(75, $excel_row, $row->pay_plug);
			$object->getActiveSheet()->setCellValueByColumnAndRow(76, $excel_row, $row->date_plug);
			$object->getActiveSheet()->setCellValueByColumnAndRow(77, $excel_row, $row->tgl_kirim_plug_ar);

			$object->getActiveSheet()->setCellValueByColumnAndRow(78, $excel_row, $row->inv_buruh);
			$object->getActiveSheet()->setCellValueByColumnAndRow(79, $excel_row, $row->inv_buruh_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(80, $excel_row, $row->tgl_trima_inv_buruh);
			$object->getActiveSheet()->setCellValueByColumnAndRow(81, $excel_row, $row->inv_buruh_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(82, $excel_row, $row->pay_buruh);
			$object->getActiveSheet()->setCellValueByColumnAndRow(83, $excel_row, $row->date_buruh);

			$object->getActiveSheet()->setCellValueByColumnAndRow(84, $excel_row, $row->inv_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(85, $excel_row, $row->tgl_inv_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(86, $excel_row, $row->tgl_trima_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(87, $excel_row, $row->inv_uli_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(88, $excel_row, $row->pay_ship_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(89, $excel_row, $row->pay_date_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(90, $excel_row, $row->inv_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(91, $excel_row, $row->tgl_inv_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(92, $excel_row, $row->tgl_trima_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(93, $excel_row, $row->inv_uli_am2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(94, $excel_row, $row->pay_ship_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(95, $excel_row, $row->pay_date_uli2);

			$object->getActiveSheet()->setCellValueByColumnAndRow(96, $excel_row, $row->inv_lain);
			$object->getActiveSheet()->setCellValueByColumnAndRow(97, $excel_row, $row->inv_lain_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(98, $excel_row, $row->tgl_trima_inv_lain);
			$object->getActiveSheet()->setCellValueByColumnAndRow(99, $excel_row, $row->inv_lain_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(100, $excel_row, $row->pay_lain);
			$object->getActiveSheet()->setCellValueByColumnAndRow(101, $excel_row, $row->date_lain);
			//AP
			$object->getActiveSheet()->setCellValueByColumnAndRow(102, $excel_row, $row->no_inv_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(103, $excel_row, $row->tgl_dok_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(104, $excel_row, $row->tgl_inv_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(105, $excel_row, $row->inv_ship_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(106, $excel_row, $row->no_faktur_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(107, $excel_row, $row->tgl_inv_ship_pay);
			$object->getActiveSheet()->setCellValueByColumnAndRow(108, $excel_row, $row->rc_ship);
			$object->getActiveSheet()->setCellValueByColumnAndRow(109, $excel_row, $row->inv_ship_paid_am);

			$object->getActiveSheet()->setCellValueByColumnAndRow(110, $excel_row, $row->no_inv_ship2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(111, $excel_row, $row->tgl_dok_ship2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(112, $excel_row, $row->tgl_inv_ship2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(113, $excel_row, $row->inv_ship_am2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(114, $excel_row, $row->no_faktur_ship2); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(115, $excel_row, $row->tgl_inv_ship_pay2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(116, $excel_row, $row->rc_ship2); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(117, $excel_row, $row->inv_ship_paid_am2);

			$object->getActiveSheet()->setCellValueByColumnAndRow(118, $excel_row, $row->no_inv_uli); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(119, $excel_row, $row->tgl_dok_uli); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(120, $excel_row, $row->tgl_inv_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(121, $excel_row, $row->inv_uli_am); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(122, $excel_row, $row->no_faktur_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(123, $excel_row, $row->tgl_inv_uli_pay);
			$object->getActiveSheet()->setCellValueByColumnAndRow(124, $excel_row, $row->rc_uli);
			$object->getActiveSheet()->setCellValueByColumnAndRow(125, $excel_row, $row->inv_uli_paid_am);

			$object->getActiveSheet()->setCellValueByColumnAndRow(126, $excel_row, $row->no_inv_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(127, $excel_row, $row->tgl_dok_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(128, $excel_row, $row->tgl_inv_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(129, $excel_row, $row->inv_uli_am2 );
			$object->getActiveSheet()->setCellValueByColumnAndRow(130, $excel_row, $row->no_faktur_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(131, $excel_row, $row->tgl_inv_uli_pay2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(132, $excel_row, $row->rc_uli2);
			$object->getActiveSheet()->setCellValueByColumnAndRow(133, $excel_row, $row->inv_uli_paid_am2);

			$object->getActiveSheet()->setCellValueByColumnAndRow(134, $excel_row, $row->no_plug);
			$object->getActiveSheet()->setCellValueByColumnAndRow(135, $excel_row, $row->inv_plugar_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(136, $excel_row, $row->tgl_dok_plug);
			$object->getActiveSheet()->setCellValueByColumnAndRow(137, $excel_row, $row->inv_plugar_am);
			$object->getActiveSheet()->setCellValueByColumnAndRow(138, $excel_row, $row->plug_pay_date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(139, $excel_row, $row->rc_plug);
			$object->getActiveSheet()->setCellValueByColumnAndRow(140, $excel_row, $row->pay_plug_paid);
			$object->getActiveSheet()->setCellValueByColumnAndRow(141, $excel_row, "-");
			//AR
			$object->getActiveSheet()->setCellValueByColumnAndRow(142, $excel_row, $row->inv_truck);
			$object->getActiveSheet()->setCellValueByColumnAndRow(143, $excel_row, $row->name);
			$object->getActiveSheet()->setCellValueByColumnAndRow(144, $excel_row, $row->date);
			$object->getActiveSheet()->setCellValueByColumnAndRow(145, $excel_row, $row->tujuan);
			$object->getActiveSheet()->setCellValueByColumnAndRow(146, $excel_row, $row->pesanan);
			$object->getActiveSheet()->setCellValueByColumnAndRow(147, $excel_row, $row->no_pol);
			$object->getActiveSheet()->setCellValueByColumnAndRow(148, $excel_row, $row->jam);
			$object->getActiveSheet()->setCellValueByColumnAndRow(149, $excel_row, $row->muatan);
			$object->getActiveSheet()->setCellValueByColumnAndRow(150, $excel_row, $row->ukuran);
			$object->getActiveSheet()->setCellValueByColumnAndRow(151, $excel_row, $row->b_jajan);
			$object->getActiveSheet()->setCellValueByColumnAndRow(152, $excel_row, $row->b_kom);
			$object->getActiveSheet()->setCellValueByColumnAndRow(153, $excel_row, $row->b_kawal);
			$object->getActiveSheet()->setCellValueByColumnAndRow(154, $excel_row, $row->b_laintr);
			//TRUCK
			$object->getActiveSheet()->setCellValueByColumnAndRow(155, $excel_row, $row->b_stuff);
			$object->getActiveSheet()->setCellValueByColumnAndRow(156, $excel_row, $row->plus_b_stuff);
			$object->getActiveSheet()->setCellValueByColumnAndRow(157, $excel_row, $row->electricson);
			$object->getActiveSheet()->setCellValueByColumnAndRow(158, $excel_row, $row->b_karantina);
			$object->getActiveSheet()->setCellValueByColumnAndRow(159, $excel_row, $row->b_handlfull);
			$object->getActiveSheet()->setCellValueByColumnAndRow(160, $excel_row, $row->b_lolo);
			$object->getActiveSheet()->setCellValueByColumnAndRow(161, $excel_row, $row->b_adm);
			$object->getActiveSheet()->setCellValueByColumnAndRow(162, $excel_row, $row->b_lain);
			//PETTY CASH
			$object->getActiveSheet()->setCellValueByColumnAndRow(163, $excel_row, $row->no_transaksiin);
			$object->getActiveSheet()->setCellValueByColumnAndRow(164, $excel_row, $row->IMO_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(165, $excel_row, $row->no_container_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(166, $excel_row, $row->name_cust_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(167, $excel_row, $row->no_shipment_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(168, $excel_row, $row->no_shipment2_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(169, $excel_row, $row->no_seal_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(170, $excel_row, $row->full_empty_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(171, $excel_row, $row->reefer_dry_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(172, $excel_row, $row->stuff_date_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(173, $excel_row, $row->in_out_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(174, $excel_row, $row->origin_town_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(175, $excel_row, $row->dest_town_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(176, $excel_row, $row->vessel_name_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(177, $excel_row, $row->deliv_date_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(178, $excel_row, $row->arv_at_dest_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(179, $excel_row, $row->tgl_kon_masuk_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(180, $excel_row, $row->remark_op_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(181, $excel_row, $row->tgl_door_in); 
			$object->getActiveSheet()->setCellValueByColumnAndRow(182, $excel_row, $row->no_ship_uli_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(183, $excel_row, $row->tuj_ship_uli_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(184, $excel_row, $row->tgl_door_uli_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(185, $excel_row, $row->no_ship_uli2_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(186, $excel_row, $row->tuj_ship_uli2_in);
			$object->getActiveSheet()->setCellValueByColumnAndRow(187, $excel_row, $row->tgl_door_uli2_in);
			//MONITORING CONTAINER
			$excel_row++;
		}

		$object->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AQ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AR')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AS')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AT')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AU')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AV')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AW')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AX')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AY')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('AZ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BA')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BB')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BC')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BD')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BE')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BF')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BG')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BH')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BI')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BJ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BK')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BL')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BM')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BN')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BO')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BP')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BQ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BR')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BS')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BT')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BU')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BV')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BW')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BX')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BY')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('BZ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CA')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CB')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CC')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CD')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CE')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CF')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CG')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CH')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CI')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CJ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CK')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CL')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CM')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CN')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CO')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CP')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CQ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CR')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CS')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CT')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CU')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CV')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CW')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CX')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CY')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('CZ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DA')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DB')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DC')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DD')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DE')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DF')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DG')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DH')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DI')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DJ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DK')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DL')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DM')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DN')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DO')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DP')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DQ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DR')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DS')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DT')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DU')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DV')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DW')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DX')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DY')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('DZ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EA')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EB')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EC')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('ED')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EE')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EF')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EG')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EH')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EI')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EJ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EK')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EL')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EM')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EN')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EO')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EP')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EQ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('ER')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('ES')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('ET')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EU')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EV')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EW')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EX')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EY')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('EZ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FA')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FB')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FC')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FD')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FE')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FF')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FG')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FH')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FI')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FJ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FK')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FL')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FM')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FN')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FO')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FP')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FQ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FR')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FS')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FT')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FU')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FV')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FW')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FX')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FY')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('FZ')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('GA')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('GB')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('GC')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('GD')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('GE')->setAutoSize(true);
		$object->getActiveSheet()->getColumnDimension('GF')->setAutoSize(true);

		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Dispotition: attachment;filename="Delivery From JKT.xls"');
		$object_writer->save('php://output');	
	}
		
	// EXPORT TO EXCEL

	//EXPORT TO PDF
    function exportTruck($no_transaksi){
    	$this->load->model('pagemodel');
       	$data = $this->pagemodel->getTruckid($no_transaksi);
       	 foreach ($data as $row) {
        $pdf = new FPDF('L','mm','A5');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        
        // mencetak string 
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(20,5,'NO. IMO : '.$row->IMO);
        $pdf->Cell(150,5,'NO.   :      '.$row->inv_truck,0,1,'R');
        $pdf->Cell(170,5,'TGL. : '.$row->date,0,1,'R');
        $pdf->Ln(1);
        $pdf->SetFont('Arial','B',16);
        // $pdf->Cell(10,7,'',0,1);
        $pdf->Cell(190,5,'SPKs',0,1,'C');

        // $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,5,'SURAT PERINTAH KERJA (SOPIR)',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        // $pdf->Cell(190,7,'Email: rsiasakinaidaman@gmail.com',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,2,'',0,1);
        $pdf->SetFont('Arial','',12);
        // $date= date('l, d  F  Y');
        $pdf->Cell(190,7,'Ditugaskan Kepada :',0,1,'L');
        
            $pdf->SetFont('Arial','',11);
            $pdf->Cell(40,7,'Nama');
            $pdf->Cell(10,7,$row->name);
            $pdf->Cell(60,7,'No. Mobil',0,0,'R');
            $pdf->Cell(30,7,$row->no_pol,0,0,'R');
            $pdf->Ln(5);
            $pdf->Cell(40,7,'Tanggal');
            $pdf->Cell(10,7,$row->date);
            $pdf->Cell(50,7,'Jam',0,0,'R');
            $pdf->Cell(28,7,$row->jam,0,0,'R');
            $pdf->Ln(5);
            $pdf->Cell(40,7,'Tujuan');
            $pdf->Cell(45,7,$row->tujuan);
            $pdf->Ln(5);
            $pdf->Cell(40,7,'Order');
            $pdf->Cell(45,7,$row->pesanan);
            $pdf->Ln(5);
            $pdf->Cell(40,7,'No. Mobil');
            $pdf->Cell(37,7,$row->no_pol);
            $pdf->Ln(5);
            $pdf->Cell(40,7,'Jam');
            $pdf->Cell(35,7,$row->jam); 
            $pdf->Ln(10);
            $pdf->Cell(40,7,'Muatan');
            $pdf->Cell(35,7,$row->muatan); 
            $pdf->Cell(31,7,'Ukuran',0,0,'R');
            $pdf->Cell(22,7,$row->ukuran,0,0,'R');
            $pdf->Ln(5);
            $pdf->Cell(40,7,'Biaya');
            $pdf->Cell(53,7,'a. Uang Jalan',0,0,'L'); 
            $pdf->Cell(30,7,'Rp. '.$row->b_jajan,0,0,'L'); 
            $pdf->Ln(5);
            $pdf->Cell(40,7,'');
            $pdf->Cell(53,7,'a. Komisi',0,0,'L'); 
            $pdf->Cell(30,7,'Rp. '.$row->b_kom,0,0,'L'); 
            $pdf->Ln(5);
            $pdf->Cell(40,7,'');
            $pdf->Cell(53,7,'a. Kawalan',0,0,'L'); 
            $pdf->Cell(30,7,'Rp. '.$row->b_kawal,0,0,'L'); 
            $pdf->Ln(5);
            $pdf->Cell(40,7,'');
            $pdf->Cell(53,7,'a. .................',0,0,'L'); 
            $pdf->Cell(30,7,'Rp. '.$row->b_laintr,0,0,'L'); 
            $pdf->Ln(12);
        // }

         $pdf->Cell(10,3,'',0,1);

         $pdf->SetFont('Arial','B',10);
        // $pdf->Cell(7,6,'No',1,0);
        $pdf->Cell(45,2,'Sopir',0,0,'C');
        $pdf->Cell(65,2,'Pengurus',0,0,'C');
        $pdf->Cell(35,2,'Disetujui Oleh',0,1,'C');
        $pdf->Ln(10);
        // $no=0;
        $pdf->SetFont('Arial','',10);

        // foreach ($pemeriksaan as $row){ 
         // $no++;
            // $pdf->Cell(7,6, $row->b_lain ,1,0);
            $pdf->Cell(45,2,'_______________',0,0,'C');
            $pdf->Cell(65,2,'_______________',0,0,'C');
            $pdf->Cell(37,2,'_______________',0,1,'C');
        }

        $pdf->Output('');
  }
	//EXPORT TO PDF

  	//export to rupiah
  function Rupiah($angka){
  	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
  }

}

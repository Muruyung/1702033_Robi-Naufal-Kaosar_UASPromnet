<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_karyawan extends CI_Controller {

	var $API ="";

	function __construct() {
		parent::__construct();
		$this->API="http://localhost/web_service/service/index.php";
	}

	// proses yang akan di buka saat pertama masuk ke controller
	public function index()
	{
		$data['karyawan'] = json_decode($this->curl->simple_get($this->API.'/Karyawan'));
		$data['jabatan'] = json_decode($this->curl->simple_get($this->API.'/Jabatan'));
		$data['detail'] = json_decode($this->curl->simple_get($this->API.'/Detail', array('id'=>'-1'), array(CURLOPT_BUFFERSIZE => 10)));

		$this->load->view('V_karyawan', $data);
	}

	// proses untuk menambah data
	// insert data kontak
	function add(){

		$data = array(
			'id'      =>  $this->input->post('id'),
			'name'    =>  $this->input->post('name'),
			'email'	  =>  $this->input->post('email'),
			'address' =>  $this->input->post('address'),
			'phone'	  =>  $this->input->post('phone')
		);
		$insert =  $this->curl->simple_post($this->API.'/Karyawan', $data, array(CURLOPT_BUFFERSIZE => 0));

		$idKaryawan = json_decode($this->curl->simple_get($this->API.'/Detail'));

		$data = array(
			'id'      =>  $this->input->post('id'),
			'id_jabatan'	=>	$this->input->post('jabatan'),
			'id_karyawan' =>	$idKaryawan[0]->id
		);
		$insert =  $this->curl->simple_post($this->API.'/Detail', $data, array(CURLOPT_BUFFERSIZE => 0));

		if($insert)
		{
			$this->session->set_flashdata('hasil','Insert Data Berhasil');
		}else
		{
			$this->session->set_flashdata('hasil','Insert Data Gagal');
		}

		redirect('C_karyawan');

	}


	// proses untuk menghapus data pada database
	function delete($id){
		if(empty($id)){
			redirect('C_karyawan');
		}else{
			$delete =  $this->curl->simple_delete($this->API.'/Karyawan', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));
			if($delete)
			{
				$this->session->set_flashdata('hasil','Delete Data Berhasil');
			}else
			{
				$this->session->set_flashdata('hasil','Delete Data Gagal');
			}

			redirect('C_karyawan');
		}
	}

	// Proses mengupdate data
	function update($id){
		$data = array(
			'id' 			=>  $id,
			'name'    =>  $this->input->post('name'),
			'email'	  =>  $this->input->post('email'),
			'address' =>  $this->input->post('address'),
			'phone'	  =>  $this->input->post('phone'));
		$update =  $this->curl->simple_put($this->API.'/Karyawan', $data, array(CURLOPT_BUFFERSIZE => 0));

		$idKaryawan = json_decode($this->curl->simple_get($this->API.'/Detail', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10)));

		$data = array(
			'id'      =>  $this->input->post('id'),
			'id_jabatan'	=>	$this->input->post('jabatan'),
			'id_karyawan' =>	$idKaryawan[0]->id
		);
		$update =  $this->curl->simple_put($this->API.'/Detail', $data, array(CURLOPT_BUFFERSIZE => 0));

		if($update)
		{
			$this->session->set_flashdata('hasil','Insert Data Berhasil');
		}else
		{
			$this->session->set_flashdata('hasil','Insert Data Gagal');
		}

		redirect('C_karyawan');
	}
	//TUGAS : bikin fungsi update di client menggunakan service
	//
	//
}

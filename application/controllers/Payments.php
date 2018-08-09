<?php
date_default_timezone_set('Asia/Manila');
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {

	public function __construct()
	{ 
		parent::__construct(); 
		$this->load->model('Crud'); 
	}

	public function index()
	{
		if ($this->uri->segment(3)) {
			$title = "My Reservations";
			$transactionID = $this->uri->segment(3);
			$this->load->view('includes/materialize/header', compact('title','transactionID'));
			$this->load->view('payments');
			$this->load->view('includes/materialize/footer');
		}else{
			redirect('Welcome');
		}
	}

	public function updatePayment()
	{
		$reservation_key = $this->uri->segment(3); 
		$config['upload_path']          = './assets/uploads/payments';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 1000;


		$this->load->library('upload', $config);

		if ( !$this->upload->do_upload('file'))
		{
			$error = array('error' => strip_tags($this->upload->display_errors()));
			echo json_encode($error);
		}
		else
		{ 
			$data = array('upload_data' => $this->upload->data());  
			$updateData = array(
				'reservation_payment_status'=>1,
				'reservation_updated_at' => strtotime('now'),
				'reservation_payment_photo'=>$data["upload_data"]['file_name']
			);
			if ($this->Crud->update('reservation',$updateData,array('reservation_key'=>$reservation_key))) {
				echo json_encode(true);
			}else{
				echo json_encode(false);
			}


		}
	}

	public function updateBills()
	{
		$post = $this->input->post(array('changesTotal','reservation_key','adultCount','childCount','sbRoomCount','dbRoomCount','mattressCount'));


		$resSbUpdateData = array(
			'reservation_adult' => $post['adultCount'],
			'reservation_child' => $post['childCount'],
			'reservation_roomCount' => $post['sbRoomCount'],
			'reservation_updated_at' => strtotime('now'), 
		);	
		$resDbUpdateData = array(
			'reservation_adult' => $post['adultCount'],
			'reservation_child' => $post['childCount'],
			'reservation_roomCount' => $post['dbRoomCount'],
			'reservation_updated_at' => strtotime('now'), 
		);
		$billingData = array(
			'billing_price'=>$post['changesTotal'],
			'billing_name'=>"Reservation Update",
			'billing_quantity'=>1,
			'reservation_key'=>$post['reservation_key'],
		);
		$billingMattressData = array(
			'billing_price'=>350,
			'billing_name'=>"Misc. Mattress ",
			'billing_quantity'=>$post['mattressCount'],
			'reservation_key'=>$post['reservation_key'],
		);
		$billingMattressUpdateData = array(
			'billing_quantity'=>$post['mattressCount'],
		);
		$where_sb = array('reservation_key'=>$post['reservation_key'], 'room_type_id'=>1);
		$where_db = array('reservation_key'=>$post['reservation_key'], 'room_type_id'=>2);

		if (!$this->Crud->update('reservation',$resSbUpdateData,$where_sb)) {
			echo json_encode("Error updating single bedroom"); 
		} 
		if (!$this->Crud->update('reservation',$resDbUpdateData,$where_db)) {
			echo json_encode("Error updating double bedroom");
		} 
		if ($mattress = $this->Crud->fetch_like('billing','billing_name','Misc. Mattress', array('reservation_key' => $post['reservation_key'] ))) {
			if (!$this->Crud->update('billing',$billingMattressUpdateData, array('billing_id' => $mattress[0]->billing_id ))) {
				echo "<pre>";
				var_dump($billingMattressUpdateData);
				echo json_encode("Error updating mattress billing");
			}
		}else{
			if (!$this->Crud->insert('billing',$billingMattressData)) {
				echo json_encode("Error inserting mattress to billing");
			}
		}

		if ($post['changesTotal'] > 0) {
			if ($this->Crud->insert('billing',$billingData)) {
				echo json_encode(true);
			}else{
				echo json_encode("Error inserting to billing");
			}
		}else{
			echo json_encode(true);
		} 

		// if ($this->Crud->update('reservation',$resSbUpdateData,$where_sb)) {
		// 	if ($this->Crud->update('reservation',$resDbUpdateData,$where_db)) {
		// 		if ($this->Crud->insert('billing',$billingMattressData)) {
		// 			if ($post['changesTotal'] > 0) {
		// 				if ($this->Crud->insert('billing',$billingData)) {
		// 					echo json_encode(true);
		// 				}else{
		// 					echo json_encode("Error inserting to billing");
		// 				}
		// 			}else{
		// 				echo json_encode(true);
		// 			}
		// 		}else{
		// 			echo json_encode("Error inserting to billing");
		// 		}
		// 	}else{ 
		// 		echo json_encode("Error updating double bedroom");
		// 	}
		// }else{
		// 	echo json_encode("Error updating single bedroom");
		// }





	}
	

	public function downloadPDF(){
		$input = $this->input->post(NULL, TRUE);
		echo json_encode($input);
	}

}

/* End of file Payments.php */
/* Location: ./application/controllers/Payments.php */
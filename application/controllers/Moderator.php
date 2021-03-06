<?php
date_default_timezone_set('Asia/Manila');
defined('BASEPATH') OR exit('No direct script access allowed');

class Moderator extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Crud');
		$this->load->library('form_validation');
		$this->load->helper(['form', 'download']);
	}

	public function index() {
		$moderData = $this->session->userdata('moderdata');
		if (!$moderData) {
			$title = "Moderator - Login";
			$this->load->view('includes/materialize/header', compact('title'));
			$this->load->view('moderator/login');
			$this->load->view('includes/materialize/footer');
		} else {
			$title = "Moderator - Home";
			$this->load->view('includes/materialize/header', compact('title', 'moderData'));
			$this->load->view('moderator/moderator');
			$this->load->view('includes/materialize/footer');
		}

	}

	public function loadDescription() {
		$id = $this->input->post('roomID');
		$rooms = $this->Crud->fetch('room_type', array('room_type_id' => $id));

		echo json_encode($rooms[0]);
	}

	public function updateDesc() {
		$id = $this->input->post('roomID');
		$value = $this->input->post('roomDescription');

		if ($this->Crud->update('room_type', array('room_type_description' => $value), array("room_type_id" => $id))) {
			echo json_encode(true);
		}
	}

	public function updateDate() {
		$checkout = strtotime($this->input->post('checkOut'));
		$res_key = $this->input->post('res_key');
		$data = array(
			"reservation_out" => $checkout,
		);
		// fetch reservation details
		$res_details = $this->Crud->fetch('reservation',array('reservation_key'=>$res_key));
		$res_details = $res_details[0];
		$check_out_def=  $res_details->reservation_out;

	 	// fetch room details
		$room_type = $this->Crud->fetch('room_type',array('room_type_id'=>$res_details->room_type_id));
		$room_type = $room_type[0];
		$room_price = (int) $room_type->room_type_price;

		// compute additional billing
		$date1=date_create(date('M d, Y',$check_out_def));
		$date2=date_create(date('M d, Y',$checkout)); 
		$date_differrence =  date_diff($date1,$date2)->format("%a");
		$add_fee = $room_price * $date_differrence;

		if ($this->Crud->insert('billing',array('billing_price'=>$add_fee,"billing_name"=>"Misc. Extended Stay","billing_quantity"=>1,"reservation_key"=>$res_key))) {
			if ($this->Crud->update("reservation", $data, array("reservation_key" => $res_key))) {
				echo json_encode(true);
			} else {
				echo json_encode("An error updating the database occured");
			}
		}
		
	}

	public function updateDateIn()
	{
		$checkin = strtotime($this->input->post('checkIn'));
		$res_key = $this->input->post('res_key');
		$data = array(
			"reservation_in" => $checkin,
		);

		// fetch reservation details
		$res_details = $this->Crud->fetch('reservation',array('reservation_key'=>$res_key));
		$res_details = $res_details[0];
		$check_in_def=  $res_details->reservation_in;

		// fetch room details
		$room_type = $this->Crud->fetch('room_type',array('room_type_id'=>$res_details->room_type_id));
		$room_type = $room_type[0];
		$room_price = (int) $room_type->room_type_price;

		// compute additional billing
		$date1=date_create(date('M d, Y',$check_in_def));
		$date2=date_create(date('M d, Y',$checkin)); 
		$date_differrence =  date_diff($date1,$date2)->format("%a");
		$add_fee = ($room_price * $date_differrence) * -1;

		if ($this->Crud->insert('billing',array('billing_price'=>$add_fee,"billing_name"=>"Misc. Adjusted Check In Date","billing_quantity"=>1,"reservation_key"=>$res_key))) {
			if ($this->Crud->update("reservation", $data, array("reservation_key" => $res_key))) {
				echo json_encode(true);
			} else {
				echo json_encode("An error updating the database occured");
			}
		}


	}

	public function modifyRooms() {
		$moderData = $this->session->userdata('moderdata');
		if (!$moderData) {
			$title = "Moderator - Login";
			$this->load->view('includes/materialize/header', compact('title'));
			$this->load->view('moderator/login');
			$this->load->view('includes/materialize/footer');
		} else {
			$title = "Moderator - Modify Rooms";
			$this->load->view('includes/materialize/header', compact('title', 'moderData'));
			$this->load->view('moderator/modifyRooms');
			$this->load->view('includes/materialize/footer');
		}
	}

	public function addRooms() {
		$singleRoomCount = $this->input->post('singleRoomCount');
		$doubleRoomCount = $this->input->post('doubleRoomCount');
		// Last single bedroom
		$lastSR = $this->Crud->fetch_last('room', 'room_id', array('room_type_id' => 1));
		$lastDR = $this->Crud->fetch_last('room', 'room_id', array('room_type_id' => 2));

		for ($i = 1; $i <= $singleRoomCount; $i++) {
			$dataSR = array(
				'room_name' => ++$lastSR->room_name,
				'room_status' => 3,
				'room_type_id' => 1,
				'reservation_key' => "",
			);
			$this->Crud->insert('room', $dataSR);
		}
		for ($i = 1; $i <= $doubleRoomCount; $i++) {
			$dataSR = array(
				'room_name' => ++$lastDR->room_name,
				'room_status' => 3,
				'room_type_id' => 2,
				'reservation_key' => "",
			);
			$this->Crud->insert('room', $dataSR);
		}

		echo json_encode(true);

	}

	public function updateRoomStatus() {
		$id = $this->input->post("id");
		$val = $this->input->post("value");
		if ($this->Crud->update("room", array("room_status" => $val), array("room_id" => $id))) {
			echo json_encode("true");
		}
	}

	public function checkout() {
		$rKey = $this->input->post('rKey');

		if ($this->Crud->update('reservation', array('reservation_status' => 5), array('reservation_key' => $rKey))) {
			if ($this->Crud->update('room', array('room_status' => 3, 'reservation_key' => ""), array('reservation_key' => $rKey))) {
				echo json_encode(true);
			}
		} else {
			echo json_encode("Failed to update reservation");
		}
	}

	public function fetchReservations() {
		$start = $this->input->post('start');
		$end = $this->input->post('end');
		$data = $this->Crud->fetchDateBetween('reservation', $start, $end);
		$allData = array();

		$previousValue = null;

		foreach ($data as $key => $value) {
			if ($value->reservation_key != $previousValue) {
				array_push($allData, $value);
			}
			$previousValue = $value->reservation_key;
		}
		if ($allData) {
			echo json_encode($allData);
		}
	}

	public function addReservation() {
		$i = $this->input->post(array('add_checkin', 'add_checkout', 'add_adultCount', 'add_childCount', 'Room_0', 'Room_1', 'add_stayType', 'add_request', 'add_firstname', 'add_lastname', 'add_gender', 'add_phone', 'add_address', 'add_email', "totalCosts"));
		$i['Room_0'] = (int) $i['Room_0'];
		$i['Room_1'] = (int) $i['Room_1'];

		$data_guest = array(
			'guest_firstname' => $i['add_firstname'],
			'guest_lastname' => $i['add_lastname'],
			'guest_gender' => $i['add_gender'],
			'guest_phone' => $i['add_phone'],
			'guest_address' => $i['add_address'],
			'guest_email' => $i['add_email'],
		);
		if ($this->Crud->insert('guest', $data_guest)) {
			$last_id = $this->db->insert_id();
			$transaction_key = strtoupper(uniqid());
			$time_in = $i['add_stayType'] == 1 ? " 8:00 AM" : " 6:00 PM";
			$time_out = $i['add_stayType'] == 1 ? " 5:00 PM" : " 5:00 AM";

			for ($j = 0; $j < 2; $j++) {
				$data_reservation = array(
					'reservation_in' => strtotime($i['add_checkin'] . $time_in),
					'reservation_reserved_at' => strtotime('now'),
					'reservation_updated_at' => strtotime('now'),
					'reservation_out' => strtotime($i['add_checkout'] . $time_out),
					'reservation_adult' => $i['add_adultCount'],
					'reservation_child' => $i['add_childCount'],
					'reservation_roomCount' => $j == 0 ? $i['Room_0'] : $i['Room_1'],
					'reservation_day_type' => $i['add_stayType'],
					'reservation_status' => 1,
					'reservation_requests' => $i['add_request'],
					'reservation_payment_status' => 1,
					'reservation_key' => $transaction_key,
					'guest_id' => $last_id,
					'room_type_id' => ($j + 1),
				);
				$this->Crud->insert('reservation', $data_reservation);
			}

			// ADD BILLING
			$data_billing = array(
				'billing_price' => $i['totalCosts'],
				'billing_name' => 'Reservation Fee',
				'billing_quantity' => 1,
				'reservation_key' => $transaction_key,

			);
			$this->Crud->insert('billing', $data_billing);

			//  ADD TO ROOM
			// AVAILABLE ROOMS
			$room_1 = $this->Crud->countResult('room', array('room_type_id' => 1, 'room_status' => 3));
			$room_2 = $this->Crud->countResult('room', array('room_type_id' => 2, 'room_status' => 3));

			if ($room_1 < $i['Room_0']) {
				echo json_encode(["PROBLEM - INSUFFICIENT ROOMS", "There are no enough rooms for Single Bedroom"]);
			} else if ($room_2 < $i['Room_1']) {
				echo json_encode(["PROBLEM - INSUFFICIENT ROOMS", "There are no enough rooms for Double Bedroom"]);
			} else {

				if ($i['Room_0'] > 0) {
					$count = 1;
					$room_1_available = $this->Crud->fetch('room', array('room_type_id' => 1, 'room_status' => 3));
					foreach ($room_1_available as $key => $value) {
						$this->Crud->update('room', array('room_status' => 1, 'reservation_key' => $transaction_key), array('room_id' => $value->room_id));
						if ($count == $i['Room_0']) {
							break;
						}
						$count++;
					}
				}

				if ($i['Room_1'] > 0) {
					$count = 1;
					$room_2_available = $this->Crud->fetch('room', array('room_type_id' => 2, 'room_status' => 3));
					foreach ($room_2_available as $key => $value) {
						$this->Crud->update('room', array('room_status' => 1, 'reservation_key' => $transaction_key), array('room_id' => $value->room_id));
						if ($count == $i['Room_1']) {
							break;
						}
						$count++;
					}
				}
			}

			// END NA
			echo json_encode(array(true, $transaction_key));
		} else {
			echo json_encode("Failed to add reservation");
		}

	}

	public function guestValidation() {
		$this->form_validation->set_rules('add_firstname', 'First Name', 'required|alpha');
		$this->form_validation->set_rules('add_lastname', 'Last Name', 'required|alpha');
		$this->form_validation->set_rules('add_gender', 'Gender', 'required');
		$this->form_validation->set_rules('add_phone', 'Phone Number', 'required|exact_length[11]|numeric');
		$this->form_validation->set_rules('add_email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('add_address', 'Address', 'required|alpha_numeric_spaces');

		if ($this->form_validation->run() == FALSE) {
			$errors = validation_errors();
			echo json_encode(['error' => $this->form_validation->error_array()]);
		} else {
			echo json_encode(true);
		}

	}

	public function fetchReservationDetails() {
		$rKey = $this->input->post('rKey');

		$reservation = $this->Crud->fetch('reservation', array('reservation_key' => $rKey));
		$reservation_1 = $reservation[1];
		$reservation = $reservation[0];

		// STAY TYPE
		$stay_type = $reservation->reservation_day_type == 1 ? "Day Stay" : "Night Stay";
		$reservation->stay_type = $stay_type;

		// LENGTH OF STAY
		$datetime1 = new DateTime(date('Y-m-d', $reservation->reservation_in));
		$datetime2 = new DateTime(date('Y-m-d', $reservation->reservation_out));
		$difference = $datetime1->diff($datetime2);
		$reservation->stay_length = $difference->d + 1;

		// ROOM TYPE
		$room_types = $this->Crud->fetch('room_type');
		$reservation->room_1_type = $room_types[0]->room_type_name;
		$reservation->room_2_type = $room_types[1]->room_type_name;

		// Room 2 Count
		$reservation->room_2 = $reservation_1->reservation_roomCount;

		// Miscellaneous
		$miscs = $this->Crud->fetch_like('billing', 'billing_name', 'Misc.', array('reservation_key' => $rKey));
		$reservation->miscs = $miscs;

		// Total Billing
		$billing = $this->Crud->getSum('billing', 'billing_price', array('reservation_key' => $rKey));
		$reservation->billing = $billing;
		echo json_encode($reservation);

	}

	public function addBilling() {
		$miscName = $this->input->post('miscName');
		$miscPrice = $this->input->post('miscPrice');
		$miscQty = $this->input->post('miscQty');
		$rKey = $this->input->post('rKey');
		$boolean = true;
		foreach ($miscName as $key => $value) {
			$data = array(
				"billing_price" => $miscPrice[$key],
				"billing_name" => "Misc. " . ucwords($value),
				"billing_quantity" => $miscQty[$key],
				"reservation_key" => $rKey,
			);
			if (!$this->Crud->insert('billing', $data)) {
				$boolean = false;
			}
		}

		echo json_encode($boolean == true ? true : "Failed to add billing");
	}

	public function checkCredentials() {
		$username = $this->input->post('username');
		$password = sha1($this->input->post('password'));
		$data = array(
			'moderator_username' => $username,
			'moderator_password' => $password,
		);

		if ($data = $this->Crud->fetch('moderator', $data)) {
			$sessionData = array(
				'logged_in' => true,
				'data' => $data,
			);
			$this->session->set_userdata('moderdata', $sessionData);
			redirect('Moderator');
		} else {
			$this->session->set_flashdata('error', 'Account Credentials are incorrect.');
			redirect('Moderator');
		}
	}

	public function approveReservation() {
		$r_key = $this->input->post('rKey');
		$reservations = $this->Crud->fetch('reservation', array('reservation_key' => $r_key));

		// USER ROOM COUNT
		$roomCount = array();
		foreach ($reservations as $key => $value) {
			array_push($roomCount, $value->reservation_roomCount);
		}

		// AVAILABLE ROOMS
		$room_1 = $this->Crud->countResult('room', array('room_type_id' => 1, 'room_status' => 3));
		$room_2 = $this->Crud->countResult('room', array('room_type_id' => 2, 'room_status' => 3));

		if ($room_1 < $roomCount[0]) {
			echo json_encode(["PROBLEM - INSUFFICIENT ROOMS", "There are no enough rooms for Single Bedroom"]);
		} else if ($room_2 < $roomCount[1]) {
			echo json_encode(["PROBLEM - INSUFFICIENT ROOMS", "There are no enough rooms for Double Bedroom"]);
		} else {

			if ($roomCount[0] > 0) {
				$i = 1;
				$room_1_available = $this->Crud->fetch('room', array('room_type_id' => 1, 'room_status' => 3));
				foreach ($room_1_available as $key => $value) {
					$this->Crud->update('room', array('room_status' => 1, 'reservation_key' => $r_key), array('room_id' => $value->room_id));
					if ($i == $roomCount[0]) {
						break;
					}
					$i++;
				}
			}

			if ($roomCount[1] > 0) {
				$i = 1;
				$room_2_available = $this->Crud->fetch('room', array('room_type_id' => 2, 'room_status' => 3));
				foreach ($room_2_available as $key => $value) {
					$this->Crud->update('room', array('room_status' => 1, 'reservation_key' => $r_key), array('room_id' => $value->room_id));
					if ($i == $roomCount[1]) {
						break;
					}
					$i++;
				}
			}

			if ($this->Crud->update('reservation', array('reservation_status' => 1), array('reservation_key' => $r_key))) {
				echo json_encode(true);
			} else {
				echo json_encode("Failed to update reservation");
			}
		}

	}

	public function denyReservation() {
		$key = $this->input->post('rKey');

		if ($this->Crud->update('reservation', array('reservation_status' => 3), array('reservation_key' => $key))) {
			echo json_encode(true);
		} else {
			echo json_encode("Failed to update reservation");
		}
	}

	public function logout() {
		$this->session->unset_userdata('moderdata');
		redirect('Welcome');
	}

	public function downloadPDF() {
		if (!empty($id = $this->uri->segment(3))) {
			$moderData = $this->session->userdata('moderdata');
			$employee = strtoupper($moderData['data'][0]->moderator_firstname . " " . $moderData['data'][0]->moderator_lastname);
			require_once './application/vendor/autoload.php';
			$reservation = $this->Crud->fetch('reservation', array('reservation_key' => $id));
			if ($reservation) {
				$guest = $this->Crud->fetch('guest', array('guest_id' => $reservation[0]->guest_id));
				$guest = $guest[0];
				$fullname = $guest->guest_firstname . " " . $guest->guest_lastname;

				$stay_type = $reservation[0]->reservation_day_type == 1 ? "Day Stay" : "Night Stay";

				// Compute length of stay
				$datetime1 = new DateTime(date('Y-m-d', $reservation[0]->reservation_in));
				$datetime2 = new DateTime(date('Y-m-d', $reservation[0]->reservation_out));
				$difference = $datetime1->diff($datetime2);

				$billing = $this->Crud->fetch('billing', array('reservation_key' => $reservation[0]->reservation_key));
				$billing_total = 0;
				$billing_total_negative = 0;
				foreach ($billing as $key => $value) {
					$billing_total += ($value->billing_price * $value->billing_quantity);
					if ($value->billing_price * $value->billing_quantity > 0) {
					} else {
						$billing_total_negative += ($value->billing_price * $value->billing_quantity) * -1;
					}
				}
				$billing_total = $billing_total + $billing_total_negative;
				$tax = $this->Crud->fetch('settings', array('settings_id' => 1))[0]->settings_tax;
				$totalTax = ($billing_total / $tax);
				$totalTax = round($totalTax, 2);
			}
			$data = array(
				"title" => "PDF",
				"id" => $id,
				"reservation" => $reservation,
				"datetime1" => $datetime1,
				"datetime2" => $datetime2,
				"difference" => $difference,
				"stay_type" => $stay_type,
				"billing_total" => $billing_total,
				"totalTax" => $totalTax,
				"tax" => $tax,
				"fullname" => $fullname,
				"email" => $guest->guest_email,
				"employee" => $employee,
			);

			$mpdf = new \Mpdf\Mpdf();

			// Buffer the following html with PHP so we can store it to a variable later
			ob_start();

			// This is where your script would normally output the HTML using echo or print
			$this->load->view('pdf/pdf_file', $data); //last-mark

			// Now collect the output buffer into a variable
			$html = ob_get_contents();
			ob_end_clean();

			// send the captured HTML from the output buffer to the mPDF class for processing
			$mpdf->WriteHTML($html);
			$filepath = 'assets/uploads/pdfs/pdf_' . $id . '.pdf';
			if (file_exists($filepath)) {
				unlink($filepath);
			}
			$mpdf->Output($filepath);
			force_download($filepath, NULL);
		} else {
			redirect();
		}

	}
}

/* End of file Moderator.php */
/* Location: ./application/controllers/Moderator.php */
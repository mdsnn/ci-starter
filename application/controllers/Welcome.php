<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('crud_model'); 
	}

	// 25/9/24 MS: Default method that loads the welcome view
	public function index()
	{
		$this->load->view('welcome_message');
	}

	// 25/9/24 MS: Validate form entry through AJAX request
	public function validate_entry()
	{

		if ($this->input->is_ajax_request()) {
			$name = $this->input->post('name'); 
			$email = $this->input->post('email'); 


			$response = array('name_exists' => false, 'email_exists' => false);

			// 25/9/24 MS: Check for name uniqueness in the database
			if (!empty($name)) {
				$this->db->where('name', $name);
				$response['name_exists'] = $this->db->count_all_results('crud') > 0;
			}

			// 25/9/24 MS: Check for email uniqueness in the database
			if (!empty($email)) {
				$this->db->where('email', $email);
				$response['email_exists'] = $this->db->count_all_results('crud') > 0;
			}


			echo json_encode($response);
		} else {

			show_error('No direct script access allowed', 403);
		}
	}

	// 25/9/24 MS: Insert new entry into the database
	public function insert()
	{

		if ($this->input->is_ajax_request()) {

			// 26/9/24 MS: Set validation rules for name and email

			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

			// 26/9/24 MS: Validate form data

			if ($this->form_validation->run() == FALSE) {

				// 26/9/24 MS: Respond with validation errors

				$data = array(
					'response' => 'error',
					'message' => validation_errors()
				);
			} else {

				$ajax_data = $this->input->post();

				if ($this->crud_model->insert_entry($ajax_data) === false) {

					$data = array(
						'response' => 'error',
						'message' => 'Name or Email already exists'
					);
				} else {

					$data = array(
						'response' => 'success',
						'message' => 'Record added successfully'
					);
				}
			}
			echo json_encode($data);
		} else {

			show_error('No direct script access allowed', 403);
		}
	}

	// 25/9/24 MS: Fetch entries from the database
	
	public function fetch()
	{

		if ($this->input->is_ajax_request()) {
			$posts = $this->crud_model->get_entries();
			$data = array('response' => 'success', 'posts' => $posts);
			echo json_encode($data);
		} else {

			echo "No direct script access allowed";
		}
	}

	// 25/9/24 MS: Delete an entry from the database

	public function delete()
	{

		if ($this->input->is_ajax_request()) {
			$del_id = $this->input->post('del_id'); 


			if ($this->crud_model->delete_entry($del_id)) {
				$data = array('response' => 'success');
			} else {
				$data = array('response' => 'error');
			}


			echo json_encode($data);
		} else {

			echo "No direct script access allowed";
		}
	}

	// 25/9/24 MS: Edit an existing entry
	public function edit()
	{

		if ($this->input->is_ajax_request()) {
			$edit_id = $this->input->post('edit_id');


			if ($post = $this->crud_model->edit_entry($edit_id)) {
				$data = array('response' => 'success', 'post' => $post);
			} else {
				$data = array('response' => 'error', 'message' => 'failed to fetch record');
			}

			echo json_encode($data);
		} else {

			echo "No direct script access allowed";
		}
	}

	// 25/9/24 MS: Update an existing entry
	public function update()
	{

		if ($this->input->is_ajax_request()) {

			$this->form_validation->set_rules('edit_name', 'Name', 'required');
			$this->form_validation->set_rules('edit_email', 'Email', 'required|valid_email');


			if ($this->form_validation->run() == FALSE) {

				$data = array('response' => 'error', 'message' => validation_errors());

			} else {

				$data['id'] = $this->input->post('edit_record_id');
				$data['name'] = $this->input->post('edit_name');
				$data['email'] = $this->input->post('edit_email');


				if ($this->crud_model->update_entry($data)) {
					$data = array('response' => 'success', 'message' => 'Record updated successfully');
				} else {
					$data = array('response' => 'error', 'message' => 'Failed to update record');
				}
			}


			echo json_encode($data);
		} else {

			echo "No direct script access allowed";
		}
	}
}

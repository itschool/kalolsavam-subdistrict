<?php
class Special_Order_Entry extends Controller {
	function Special_Order_Entry()
	{
		parent::Controller();
		$this->Session_Model->is_user_logged(true);
		$this->template->write_view('menu', 'menu', '');
		$this->load->model('schools/Registration_Model');
		//$this->template->write_view('left_panel', 'menu_left', '');
		$this->Content['is_edit']	=	'yes';
	}
	
	function index($message = array())
	{
		if($this->Session_Model->check_user_permission(20)==false){
			$this->template->write('error', permission_warning());
			$this->template->load();
			return;
		}
		if (count(@$message['error_array']) > 0)
		{
			foreach(@$message['error_array'] as $error)
			{
				$this->template->write('error',$error.'<br>');
			}
		}
		$schoolCode	=	'';
		if($this->input->post('hidSchoolId')){
			$schoolCode	=	$this->input->post('hidSchoolId');
		}
	
		if($schoolCode){
			$this->Content['school_show']			=	'show';
			$this->Content['school_details']		= 	$this->Registration_Model->get_school_details($schoolCode);
			$this->Content['participant_details']	= 	$this->Registration_Model->get_special_order_participant_details($schoolCode);
			$this->Content['participants']			= 	$this->General_Model->get_participant_details_combo($schoolCode);
			$this->Content['orders']				= 	$this->General_Model->prepare_select_box_data('special_order_master','spo_id, spo_title','','Select Order','spo_id');
			
		} else {
			$this->Content = array();
		}
		$this->template->write_view('content', 'schools/special_order_entry', $this->Content);
		$this->template->load();
	}

	function get_school_details(){
	
		$school_details							=	 $this->Registration_Model->get_school_details($this->input->post('code'));
		$this->Content['participant_details']	=	 $this->Registration_Model->get_special_order_participant_details($this->input->post('code'));
		
		$this->Content['participants']			= 	$this->General_Model->get_participant_details_combo($this->input->post('code'));
		$this->Content['orders']				= 	$this->General_Model->prepare_select_box_data('special_order_master','spo_id, spo_title','','Select Order','spo_id');
		$this->Content['school_details']		=	$school_details;
		if ((int)@$school_details[0]['sd_id'] > 0)
		{
			$this->Content['school_show']	=	'show';
		}
		else
		{
			$this->Content['school_show']	=	'';
		}
		$this->load->view('schools/special_order_entry', $this->Content);
	}
	
	function save_participant () {
		if($this->Session_Model->check_user_permission(20)==false){
			$this->template->write('error', permission_warning());
			$this->template->load();
			return;
		}

		$save_participant		=	$this->Registration_Model->save_special_order_participant_details();
		$this->index($save_participant);
	}
	
	function edit_participant_detials() {
		if($this->Session_Model->check_user_permission(20)==false){
			$this->template->write('error', permission_warning());
			$this->template->load();
			return;
		}
		$this->Content['selected_participant']	=	 $this->Registration_Model->get_special_order_participant_details($this->input->post('hidSchoolId'), $this->input->post('hidPiId'));
		$this->index();
	}
	
	function update_participant_detials() {
		if($this->Session_Model->check_user_permission(20)==false){
			$this->template->write('error', permission_warning());
			$this->template->load();
			return;
		}
		$update_participant		=	$this->Registration_Model->update_participant_details();
		$this->index($update_participant);
	}
	
	function delete_participant_detials () {
		if($this->Session_Model->check_user_permission(20)==false){
			$this->template->write('error', permission_warning());
			$this->template->load();
			return;
		}
		$delete_participant		=	$this->Registration_Model->delete_special_order_participant_details();
		$this->index($delete_participant);
	}
	
}
?>
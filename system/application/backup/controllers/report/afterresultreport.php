<?php
class   Afterresultreport extends Controller {

		function Afterresultreport()
		{
		parent::Controller();
		$this->template->add_js('js/report/staticreport.js');	
		$this->template->add_js('js/report/reportjs.js');	
		$this->load->library('HTML2PDF');
		$this->load->model('report/prereport_model');
		$this->template->write_view('menu', 'menu', '');
		}
		

		function timefest_result()
		 
	{ 	 
	  $this->template->add_js('js/popcalendar.js');
	  $this->template->add_css('style/calendar.css');
	 	  
	  $fest							=	$this->General_Model->prepare_select_box_data('festival_master','fest_id,fest_name','','Select Festival');
	  $this->Contents[]				=	array();
	 
	// $last=array('ALL'=>'All Festival');
	// $fest=array_merge($fest,$last);
	 
	  $this->Contents['fest']		=	$fest;
	  $this->template->write_view('content','result/afterresult_report/status_of_kalolsavam',$this->Contents);
	  $this->template->load();	  
	}
	function school_wise_result_interface()
		{
		  $school							=	$this->General_Model->prepare_select_box_data('school_master','school_code,school_code','','Select School');
		  $this->Contents				=	array();
		  $this->Contents['school']		=	$school;
		  $this->template->write_view('content','result/afterresult_report/school_wise_result_interface',$this->Contents);
		  $this->template->load();
		}
	
	
		function rankwise_participant_result()
		 
		{ 	 
	  $this->template->add_js('js/popcalendar.js');
	  $this->template->add_css('style/calendar.css');
	 	  
	  $fest							=	$this->General_Model->prepare_select_box_data('festival_master','fest_id,fest_name','','Select Festival');
	  $this->Contents[]				=	array();
	 
	// $last=array('ALL'=>'All Festival');
	// $fest=array_merge($fest,$last);
	 
	  $this->Contents['fest']		=	$fest;
	  $this->template->write_view('content','result/afterresultinterfaces/partcipant_rankwise',$this->Contents);
	  $this->template->load();	  
	}
	 
	function status_of_kalolsavam_interface()
	{
	 $fest							=	$this->General_Model->prepare_select_box_data('festival_master','fest_id,fest_name','','Select Festival','fest_id');
	  $this->Contents				=	array();
	  $fest['All']                         =   "All festivals";
	  $this->Contents['fest']		=	$fest;
	  $date_array					=	$this->General_Model->get_fest_date_array();
	  $date_array['All']			=	'All Dates';
	  $this->Contents['date_array']	=	$date_array;
	  $this->template->write_view('content','result/afterresult_report/status_of_kalolsavam_interface',$this->Contents);
	  $this->template->load();
	 
	}
	function higherlevel_result()
	{
	 	if($this->Session_Model->check_user_permission(8)==false){
			$this->template->write('error', permission_warning());
			$this->template->load();
			return;
		}
		$fest							=	$this->General_Model->prepare_select_box_data('festival_master','fest_id,fest_name','fest_id != 1 and fest_id != 7','Select Festival','fest_id');
	    $this->Contents					=	array();
		//$fest= Array_Remove($fest, 8, 2);

	    $fest['All']                    =   "All festivals";
	    $this->Contents['fest']			=	$fest;
	    $this->template->write_view('content','result/afterresult_report/higherlevel_result',$this->Contents);
	    $this->template->load();
	
	}
	
}
	
?>
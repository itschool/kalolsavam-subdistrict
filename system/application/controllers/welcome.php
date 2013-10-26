<?php
class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();
		$this->load->model('Session_Model');
		//$this->load->model('General_Model');
		$this->Session_Model->is_user_logged(true);
		$this->template->add_js('js/admin/admin.js');
		$this->template->write_view('menu', 'menu', '');
		$this->load->model('login/login_model');
		$this->Contents = array();
		//$this->template->write_view('left_panel', 'menu_left', '');
	}
	
	function index()
	{
		//echo $this->session->userdata('USER_TYPE');.
		if ($this->session->userdata('USER_TYPE')==0 || $this->session->userdata('USER_TYPE')==1)
		{
			redirect('welcome/district_details');
		}
		if($this->session->userdata('USER_TYPE')==5){
			redirect('welcome/cluster_schools');
		}
		if($this->session->userdata('USER_TYPE')==2){
			//redirect('welcome/sub_district_details');
		}
		if($this->session->userdata('USER_TYPE')==3){
			//redirect('welcome/cluster_details');
		}
		$welcome_label		=	'';
		if($this->session->userdata('USER_TYPE')==3){
			$sub_dist_code		=	$this->session->userdata('SUB_DISTRICT');
			$sub_dist_name		=	get_sub_dist_name($this->session->userdata('SUB_DISTRICT'));
			$welcome_label		=	$sub_dist_name.' Subdistrict Kalolsavam 2012 - 2013';
		}
		else if($this->session->userdata('USER_TYPE')==2){
			$dist_code		=	$this->session->userdata('DISTRICT');
			$dist_name		=	get_dist_name($this->session->userdata('DISTRICT'));
			$welcome_label	=	$dist_name.' District Kalolsavam 2012 - 2013';
		}
		else if($this->session->userdata('USER_TYPE') < 2){
			$welcome_label	=	'Kerala Kalolsavam 2012 - 2013';
		}

		
		$this->Content['welcome_label']		=	$welcome_label;
		$this->template->write_view('content', 'welcome',$this->Content);
		$this->template->load();
		
	}
	
	/* unction for list the user cluster details*/
	function cluster_details ($message = '') {
	
		if ($message)
		{
			if (is_array($message['error_array']))
			{
				foreach($message['error_array'] as $error)
				{
					$this->template->write('error', $error.'<br>');
				}
				
			}
		}
		if ('' != trim($this->input->post('sel_sub_district_id'))) $subdist	= $this->input->post('sel_sub_district_id');
		else $subdist	= $this->session->userdata('SUB_DISTRICT');
		
		$this->Contents=array();
		$this->template->write('title', '');
		$sub_admin						=	$this->login_model->get_sub_admin_details ($subdist);
		$clusters						=	$this->login_model->get_cluster_details ($subdist);
		$this->Contents['sub_admin']	=	$sub_admin;
		$this->Contents['clusters']		=	$clusters;

		$sub_school						=	$this->login_model->get_sub_school_details($subdist);
		$nonclust                       =   $this->login_model->get_unclustersch_details($subdist);
		
		$val                            =   $this->login_model->get_unclustersch_finentry($subdist);
		$this->Contents['sub_school']	=	$sub_school;
		$this->Contents['nonclust']	    =	$nonclust;
		$this->Contents['val']			=    $val;
		$this->Contents['subdst']		= 	$subdist;
		
		$this->template->write_view('content', 'login/clusters', $this->Contents);
		$this->template->load();
	}
	
	function cluster_schools()
	{
		  $this->Contents=array();
		  $this->template->write('title', '');
		  if($this->input->post('hidClusterId') ){
		  	$clusterId	=	$this->input->post('hidClusterId') ;
		  }else {
		  		$clusterId	=	'';
		  }
		  $cluster						=	$this->login_model->clusterdetails($clusterId);
		
		  $school						=	$this->login_model->schooldetails($clusterId);
		  $particip                     =   $this->login_model->schoolpartcip();
		  $this->Contents['cluster']	=	$cluster;
		  $this->Contents['school']		=	$school;
		  $this->Contents['part']		=	$particip;
		// print_r($school);
		
		  if(count($school)>0){
			  $this->template->write_view('content', 'login/clustschool', $this->Contents);
			  $this->template->load();
		  }
	}
	function pdf_report()
	{
		$this->load->library('HTML2PDF');
		$this->Content = array();
		
		$content=get_encr_password('password');
		$html2pdf = new CI_HTML2PDF('P','A4', 'en');
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->WriteHTML($content, '');
		$html2pdf->Output('project_urls.pdf', 'I');
	}
	
	function confirm_sub_dist_schools()
	{
		$this->load->model('user/User_Cluster_Model');
		$message	=	'';
		if($this->session->userdata('USER_TYPE')==3){
			$message	=	$this->User_Cluster_Model->confirm_sub_dist_schools();
		}
		$this->cluster_details($message);
		
	}
	function district_details ()
	{
		if ($this->session->userdata('USER_TYPE')==0 || $this->session->userdata('USER_TYPE')==1)
		{

			
			$this->Content['district_details']	= $this->General_Model->get_data('rev_district_master', '*', 
													array(), 'rev_district_code');
													
			//$this->Content['dist_school']		=	$this->login_model->get_district_school_details();
			//var_dump($this->Content['dist_school']);exit();
			$this->template->write_view('content', 'login/list_district_details', $this->Content);
		}
		else redirect ('welcome');
		$this->template->load();
	}
	function sub_district_details ()
	{
		if ($this->session->userdata('USER_TYPE')==0 || $this->session->userdata('USER_TYPE')==1 || $this->session->userdata('USER_TYPE')==2 )
		{
			if($this->input->post('sel_district_id') ) $district_id	= $this->input->post('sel_district_id');
			else if ($this->session->userdata('DISTRICT')) $district_id	= $this->session->userdata('DISTRICT');
			else  redirect ('welcome');
			
			$district_details = $this->General_Model->get_data('rev_district_master', '*', 
													array("rev_district_code" => $district_id), 'rev_district_code');
			$this->Content['district_name']	= $district_details[0]['rev_district_name'];
			$this->Content['sub_district_details']	= $this->General_Model->get_data('sub_district_master', '*', 
													array("rev_district_code" => $district_id), 'sub_district_name');
			$this->template->write_view('content', 'login/list_sub_district_details', $this->Content);
		}
		else redirect ('welcome');
		$this->template->load();
	}
	
	function nonclustdetails()
	{
		  $subdist=$this->input->post('hidClusterId');
		  $this->Contents=array();
		  $this->template->write('title', '');
		  $val                        =   $this->login_model->nonclustdetails_sname($subdist);
		  $nonschool                  =   $this->login_model->nonclustdetails_nosname($subdist);
		$this->Contents['val']	  		=	 $val;
		$this->Contents['nonschool']	= $nonschool ;
		$this->template->write_view('content', 'login/nonclustschool', $this->Contents);
		$this->template->load();
	}
	
	function changeKalolsavam(){
			$subdist		= $this->session->userdata('SUB_DISTRICT');
			$sub_dist_name		=	get_sub_dist_name($this->session->userdata('SUB_DISTRICT'));
			if($change			=	$this->login_model->changeKalolsavam($subdist,$sub_dist_name)){
			$this->template->write('message', $sub_dist_name.'Kalolsavam Name changed sucess fully<br>');
			
			}
			else
				$this->template->write('error', 'Cant Change kalolsavam Name : <br>');
				
			$this->template->load();
		}
		
		function export_photo(){
			$this->load->library('zip');
			$this->load->helper('file');
			ini_set('memory_limit','2048M');		
			$photo_path			=	"uploads/photos/";
			$this->zip->read_dir($photo_path); 
			$this->zip->download('photos.zip');
		}
	function itemMaster(){
		$query1	=	mysql_query('INSERT INTO `item_master` (`item_id`, `item_code`, `vibhagam_id`, `fest_id`, `item_name`, `gender`, `item_type`, `max_time`, `time_type`, `max_participants`, `max_pinnani`, `is_off_stage`) VALUES
(305, 998, 21, 4, "Keralanadanam (Girls)", "G", "S", 15, "M", 1, 0, "N")');
		$query2	=	mysql_query("INSERT INTO `item_master` (`item_id`, `item_code`, `vibhagam_id`, `fest_id`, `item_name`, `gender`, `item_type`, `max_time`, `time_type`, `max_participants`, `max_pinnani`, `is_off_stage`) VALUES
(304, 682, 10, 3, 'Kerala Nadanam (Girls)', 'G', 'S', 15, 'M', 1, 0, 'N')");
	}
}
	
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */

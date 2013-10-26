<?php
class Import extends Controller {

	function Import()
	{
		parent::Controller();
		$this->load->model('Session_Model');
		//$this->load->model('General_Model');
		//$this->Session_Model->is_user_logged(true);
		$this->template->add_js('js/admin/admin.js');
		//$this->template->write_view('menu', 'menu', '');
		$this->load->library('csvreader');
		$this->load->model('Import_Model');
		$this->Contents = array();
		//$this->template->write_view('left_panel', 'menu_left', '');
	}
	function index()
	{
		$this->import_data ();
	}
	
	function import_data ()
	{
		if ($this->session->userdata('USER_GROUP') == 'A' && ($this->session->userdata('USER_TYPE')==3 || $this->session->userdata('USER_TYPE')==2))
		{
			
			if (is_import_data_finish ($this->session->userdata('SUB_DISTRICT')))
			{
				$this->Contents['import_completed'] = 'YES';
				$this->template->write_view('menu', 'menu', '');
			}
			else
			{
				$this->Contents['import_completed'] = 'NO';
			}
			if (isset($_FILES['kalolsavamCSV']['name']) && !empty($_FILES['kalolsavamCSV']['name']))
			{
				$file_name	= $this->General_Model->upload_kalolsavam_csv_data ('kalolsavamCSV', 'kalolsavamCSV'.substr(fncUuid(), 10));
				if (!$file_name)
				{
					$this->template->write('error', $this->upload->display_errors());
					$this->template->write_view('content', 'import/import_csv_data', $this->Contents);
					$this->template->load();
					return;
				}
				if ($this->session->userdata('USER_TYPE')==3 )
				{
					$import_result	= $this->Import_Model->insert_import_data ($file_name);
					if (TRUE === $import_result)
					{
						$this->Contents['import_completed'] = 'YES';
						$this->template->write_view('menu', 'menu', '');
						$this->template->write('message', 'CSV Data saved successfully');
					}
					else if ('INVALID_DATA' == $import_result)
					{
						$this->template->write('error', 'Invalid CSV');
					}
					else if ('DATA_ALREADY_ENTERED' == $import_result)
					{
						$this->template->write('error', 'CSV data already imported');
					}
					else
					{
						$this->template->write('error', 'Failed to Save CSV Data');
					}
				}
				else if ($this->session->userdata('USER_TYPE')==2)
				{
					$import_result	= $this->Import_Model->insert_import_district_data ($file_name);
					if (TRUE === $import_result)
					{
						$this->template->write('message', 'CSV Data saved successfully');
					}
					else if ('INVALID_SUB_DISRICT_DATA' == $import_result)
					{
						$this->template->write('error', 'This Sub-District is not belongs to current District');
					}
					else if ('DATA_ALREADY_ENTERED' == $import_result)
					{
						$this->template->write('error', 'Sub-District data already imported');
					}
					else
					{
						$this->template->write('error', 'Failed to Save CSV Data');
					}
				}
				
				
			}
	
			$this->template->write_view('content', 'import/import_csv_data', $this->Contents);
			$this->template->load();
		}
		else redirect ('welcome');
		
	}
	
	function import_district_kalolsavam_data ()
	{
		if ($this->session->userdata('USER_GROUP') == 'A' && $this->session->userdata('USER_TYPE')==2)
		{
			if (isset($_FILES['kalolsavamCSV']['name']) && !empty($_FILES['kalolsavamCSV']['name']))
			{
				$file_name	= $this->General_Model->upload_kalolsavam_csv_data ('kalolsavamCSV', 'kalolsavamCSV'.substr(fncUuid(), 10));
				if (!$file_name)
				{
					$this->template->write('error', $this->upload->display_errors());
				}
				if ($this->Import_Model->insert_import_data ($file_name))
				{
					$this->template->write('message', 'CSV Data saved successfully');
				}
				else
				{
					$this->template->write('error', 'Failed to Save CSV Data');
				}
				
			}
	
			$this->template->write_view('content', 'import/import_csv_data', $this->Contents);
			$this->template->load();
		}
		else redirect ('welcome');
		
	}
	
}
?>
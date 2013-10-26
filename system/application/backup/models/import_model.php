<?php
class Import_Model extends Model
{
	function Import_Model(){
		parent::Model();
	}
	
	function insert_import_data ($file_name)
	{
		$file = $this->config->item('base_path').'uploads/csv/'.$file_name;;
		if (file_exists($file))
		{
			$csvData			= $this->csvreader->parse_file($file);
			if (is_array($csvData) && count($csvData) > 0)
			{
				if (!is_import_data_finish ($this->session->userdata('SUB_DISTRICT')))
				{
				
					// checking encripted time and the integer time is same
					if (trim(@$csvData[1][1]) == get_encr_password(trim(@$csvData[1][0])) && 
						(trim(@$csvData[0][0])) == trim($this->session->userdata('SUB_DISTRICT')) && 
						get_encr_password(trim($this->session->userdata('SUB_DISTRICT')).trim(@$csvData[0][1])) == trim(@$csvData[0][2]))
					{
						if ('SM_DETAILS' == trim($csvData[2][0]))
						{
							$table 		= trim($csvData[2][0]);
							$data_array	= array(); 
	
							// transation begins
							$this->db->trans_begin();
							$this->db->empty_table('school_master');
							$this->db->empty_table('school_details');
							$this->db->empty_table('participant_details');
							$this->db->empty_table('participant_item_details');
							
							for ($i = 3; $i < count($csvData); $i++)
							{
								if (is_array($csvData[$i]))
								{
									
									if ('SM_DETAILS' == $table)
									{
										if (count($csvData[$i]) == 1 or trim($csvData[$i][0]) == 'SD_DETAILS' )
										{
											$table 		= trim($csvData[$i][0]);
											continue;
										}
															
										if (trim($csvData[$i][8]) == get_encr_password(trim($csvData[$i][0]).
																					trim($csvData[$i][1]).
																					trim($csvData[$i][3]).
																					trim($csvData[$i][5]))
											)
										{
											$data_array							= array ();											
											$data_array['school_code'] 			= trim($csvData[$i][0]); 
											$data_array['sub_district_code']	= trim($csvData[$i][1]); 
											$data_array['edu_district_code'] 	= trim($csvData[$i][2]); 
											$data_array['rev_district_code'] 	= trim($csvData[$i][3]); 
											$data_array['school_name'] 			= trim($csvData[$i][4],'"'); 
											$data_array['school_type'] 			= trim($csvData[$i][5]); 
											//$data_array['master_confirm'] 		= trim($csvData[$i][6]); 
											$data_array['master_confirm'] 		= 'N'; 
											$data_array['school_status'] 		= trim($csvData[$i][7]); 
											if (!$this->db->insert('school_master', $data_array))
											{
												 $this->db->trans_rollback();
											}
										}
										else
										{
											$this->db->trans_rollback();
											return FALSE;
										}
										
									}
									else if ($table == 'SD_DETAILS' or trim($csvData[$i][0]) == 'PD_DETAILS')
									{
										if (count($csvData[$i]) == 1)
										{
											$table 		= trim($csvData[$i][0]);
											continue;
										}
										if (trim($csvData[$i][18]) == get_encr_password(trim($csvData[$i][0]).
																	trim($csvData[$i][1]).
																	trim($csvData[$i][2]).
																	trim($csvData[$i][15]))
											)
										{
											$data_array							= array ();												
											$data_array['school_code'] 			= trim($csvData[$i][0]); 
											$data_array['class_start']			= trim($csvData[$i][1]); 
											$data_array['class_end'] 			= trim($csvData[$i][2]); 
											$data_array['school_phone'] 		= trim($csvData[$i][3]); 
											$data_array['school_email'] 		= trim($csvData[$i][4]); 
											$data_array['hm_name'] 				= trim($csvData[$i][5],'"'); 
											$data_array['hm_phone'] 			= trim($csvData[$i][6]); 
											$data_array['principal_name'] 		= trim($csvData[$i][7],'"'); 
											$data_array['principal_phone'] 		= trim($csvData[$i][8]); 
											$data_array['teachers'] 			= trim($csvData[$i][9],'"'); 
											$data_array['strength_lp'] 			= trim($csvData[$i][10]); 
											$data_array['strength_up'] 			= trim($csvData[$i][11]); 
											$data_array['strength_hs'] 			= trim($csvData[$i][12]); 
											$data_array['strength_hss'] 		= trim($csvData[$i][13]); 
											$data_array['strength_vhss'] 		= trim($csvData[$i][14]); 
											$data_array['total_strength'] 		= trim($csvData[$i][15]);
											$data_array['is_create_report'] 	= trim($csvData[$i][16]); 
											$data_array['is_finalize'] 			= trim($csvData[$i][17]); 
											
											if (!$this->db->insert('school_details', $data_array))
											{
												 $this->db->trans_rollback();
											}
										}
										else
										{
											$this->db->trans_rollback();
											return FALSE;
										}
										
									}
									else if ($table == 'PD_DETAILS')
									{
										
										if (count($csvData[$i]) == 1  or trim($csvData[$i][0]) == 'PID_DETAILS')
										{
											$table 		= trim($csvData[$i][0]);
											continue;
										}
										if (trim($csvData[$i][7]) == get_encr_password(trim($csvData[$i][0]).
																trim($csvData[$i][1]).
																trim($csvData[$i][2]).
																trim($csvData[$i][3]).
																trim($csvData[$i][5]).
																trim($csvData[$i][6])))
										{	
											$data_array							= array ();											
											$data_array['participant_id'] 		= trim($csvData[$i][0]); 
											$data_array['school_code']			= trim($csvData[$i][1]); 
											$data_array['sub_district_code'] 	= trim($csvData[$i][2]); 
											$data_array['admn_no'] 				= trim($csvData[$i][3]); 
											$data_array['participant_name'] 	= trim($csvData[$i][4],'"'); 
											$data_array['class'] 				= trim($csvData[$i][5]); 
											$data_array['gender']	 			= trim($csvData[$i][6]); 
											
											if (!$this->db->insert('participant_details', $data_array))
											{
												 $this->db->trans_rollback();
												 return FALSE;
											}
										}
										else
										{
											$this->db->trans_rollback();
											return FALSE;
										}
									}
									else if ($table == 'PID_DETAILS')
									{
										if (count($csvData[$i]) == 1)
										{
											$table 		= trim($csvData[$i][0]);
											continue;
										}
	
										if (trim($csvData[$i][9]) == get_encr_password(trim($csvData[$i][0]).
																		trim($csvData[$i][1]).
																		trim($csvData[$i][2]).
																		trim($csvData[$i][3]).
																		trim($csvData[$i][4]).
																		trim($csvData[$i][6]).
																		trim($csvData[$i][8]))
											)
										{
											$data_array							= array ();											
											$data_array['participant_id'] 		= trim($csvData[$i][0]); 
											$data_array['school_code']			= trim($csvData[$i][1]); 
											$data_array['admn_no'] 				= trim($csvData[$i][2]); 
											$data_array['parent_admn_no'] 		= trim($csvData[$i][3]); 
											$data_array['item_code'] 			= trim($csvData[$i][4]); 
											$data_array['item_type'] 			= trim($csvData[$i][5]); 
											$data_array['spo_id'] 				= trim($csvData[$i][6]); 
											$data_array['spo_remarks'] 			= trim($csvData[$i][7]); 
											$data_array['is_captain'] 			= trim($csvData[$i][8]); 
											
											if (!$this->db->insert('participant_item_details', $data_array))
											{
												 $this->db->trans_rollback();
												 return FALSE;
											}
										}
										else
										{
											$this->db->trans_rollback();
											return FALSE;
										}
									}
								}
							}
							$this->db->trans_commit();
							return TRUE;
						}
						else
						{
							return FALSE;
						}
					}
					else
					{
						return 'INVALID_DATA';
					}
					
				}
				else
				{
					return 'DATA_ALREADY_ENTERED';
				}
			}
		}
	}
	
	function insert_import_district_data ($file_name)
	{
		$file = $this->config->item('base_path').'uploads/csv/'.$file_name;
		if (file_exists($file))
		{
			$csvData			= $this->csvreader->parse_file($file);
			if (is_array($csvData) && count($csvData) > 0)
			{
				$sub_dist_code	= trim($csvData[0][1]);
				$this->db->select('rev_district_code');
				$this->db->where('sub_district_code', trim($csvData[0][1]));
				$district_code	= $this->db->get('sub_district_master');
				$district_code	= $district_code->result_array();
				if(is_array($district_code) && count($district_code) > 0 && $district_code[0]['rev_district_code'] == trim($this->session->userdata('DISTRICT')))
				{
					$this->db->select('school_code');
					$this->db->where('sub_district_code', trim($csvData[0][1]));
					$school_code	= $this->db->get('temp_dist_participant_details');
					$school_code	= $school_code->result_array();
					if (is_array($school_code) && count($school_code) <= 0)
					{
						// checking encripted time and the integer time is same
						if (isset($csvData[1][0])  && 
							isset($csvData[1][1])  && 
							isset($csvData[0][1])  && 
							isset($csvData[0][2])  && 
							isset($csvData[0][3])  && 
							trim($csvData[1][1]) == get_encr_password(trim(@$csvData[1][0])) && 
							(trim($csvData[0][0])) == trim($this->session->userdata('DISTRICT')) && 
							get_encr_password(trim($this->session->userdata('DISTRICT')).trim($csvData[0][1]).trim($csvData[0][2])) == trim($csvData[0][3]))
						{
							if ('PD_DETAILS' == trim($csvData[2][0]))
							{
								$table 		= trim($csvData[2][0]);
								$data_array	= array(); 
		
								// transation begins
								$this->db->trans_begin();
								for ($i = 3; $i < count($csvData); $i++)
								{
								
									if (is_array($csvData[$i]))
									{
									
										if ('PD_DETAILS' == $table)
										{
											
											if (count($csvData[$i]) == 1)
											{
												$table 		= trim($csvData[$i][0]);
												continue;
											}
											if (trim($csvData[$i][6]) == get_encr_password(trim($csvData[$i][0]).
																						trim($csvData[$i][1]).
																						trim($csvData[$i][2]).
																						trim($csvData[$i][4]).																				
																						trim($csvData[$i][5]))
												)
											{
												$data_array							= array ();											
												$data_array['school_code']			= trim($csvData[$i][0]); 
												$data_array['sub_district_code'] 	= trim($csvData[$i][1]); 
												$data_array['admn_no'] 				= trim($csvData[$i][2]); 
												$data_array['participant_name'] 	= trim($csvData[$i][3],'"'); 
												$data_array['class'] 				= trim($csvData[$i][4]); 
												$data_array['gender'] 				= trim($csvData[$i][5]); 
												if (!$this->db->insert('temp_dist_participant_details', $data_array))
												{
													 $this->db->trans_rollback();
												}
											}
											else
											{
												$this->db->trans_rollback();
												return FALSE;
											}
										}
										else if ($table == 'PID_DETAILS')
										{
											if (count($csvData[$i]) == 1)
											{
												$table 		= trim($csvData[$i][0]);
												continue;
											}
											if (trim($csvData[$i][6]) == get_encr_password(trim($csvData[$i][0]).
																			trim($csvData[$i][1]).
																			trim($csvData[$i][2]).
																			trim($csvData[$i][3]).
																			trim($csvData[$i][4]).
																			trim($csvData[$i][5]))
												)
											{
												$data_array							= array ();											
												
												$data_array['school_code']			= trim($csvData[$i][0]); 
												$data_array['admn_no'] 				= trim($csvData[$i][1]); 
												$data_array['parent_admn_no'] 		= trim($csvData[$i][2]); 
												$data_array['item_code'] 			= trim($csvData[$i][3]); 
												$data_array['item_type'] 			= trim($csvData[$i][4]); 
												$data_array['is_captain'] 			= trim($csvData[$i][5]); 
												
												if (!$this->db->insert('temp_dist_participant_item_details', $data_array))
												{
													 $this->db->trans_rollback();
												}
											}
											else
											{
												$this->db->trans_rollback();
												return FALSE;
											}
										}
									}
								}
							
								$query = $this->db->query("UPDATE temp_dist_participant_item_details TPID SET TPID.participant_id = ( SELECT TPD.participant_id
														FROM temp_dist_participant_details TPD WHERE TPID.school_code = TPD.school_code AND TPD.admn_no=TPID.admn_no)
														WHERE  TPID.admn_no IN(SELECT TPD.admn_no FROM temp_dist_participant_details TPD 
															WHERE TPD.sub_district_code=".$sub_dist_code.")");
								if (!$query)
								{
									$this->db->trans_rollback();
									return FALSE;
								}
								$this->db->trans_commit();
								return TRUE;
							}
							else
							{
								$this->db->trans_rollback();
								return FALSE;
							}
						}
					}
					else
					{
						return 'DATA_ALREADY_ENTERED';
					}
				}
				else
				{
					return 'INVALID_SUB_DISRICT_DATA';
				}
			}
		}
	}
}
?>
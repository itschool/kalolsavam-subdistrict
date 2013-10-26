<?php
class Import_Model extends Model
{
	function Import_Model(){
		parent::Model();
	}

	function insert_import_data ($file_name)
	{
		$file = $this->config->item('base_path').'uploads/csv/'.$file_name;
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
											$data_array['hm_name'] 				= trim($csvData[$i][5]);
											$data_array['hm_phone'] 			= trim($csvData[$i][6]);
											$data_array['principal_name'] 		= trim($csvData[$i][7]);
											$data_array['principal_phone'] 		= trim($csvData[$i][8]);
											$data_array['teachers'] 			= trim($csvData[$i][9]);
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

function backup_tables($tables = '*')
{
	$return='';
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}

	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);

		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";

		for ($i = 0; $i < $num_fields; $i++)
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++)
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}

	//save file
	$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/kalolsavam_subdistrict_2013/dbBackup/kalolsavam_subdistrict_'.date('d-m-Y-h-i-s').'.sql','x');
	fwrite($handle,$return);
	fclose($handle);
	mysql_query('REPAIR TABLE `result_master`');
	return true;
}

function restore_Database ($fileName){
$sucess	=	0;
	/* query all tables */
	$sql = "SHOW TABLES FROM ".$this->db->database;
	if($result = mysql_query($sql)){
	  /* add table name to array */
	  while($row = mysql_fetch_row($result)){
		$found_tables[]=$row[0];
	  }
	}
	else{
	  die("Error, could not list tables. MySQL Error: " . mysql_error());
	}

	/* loop through and drop each table */
	foreach($found_tables as $table_name){
	  $sql = "DROP TABLE ".$this->db->database.".$table_name";
	  if($result = mysql_query($sql)){
		//$this->template->write('message', "<br>Success - table $table_name deleted.");
		$sucess++;
	  }
	  else{
		$this->template->write('message', "<br>Error deleting $table_name. MySQL Error: " . mysql_error() . "");
	  }
	}

	// Temporary variable, used to store current query
	$templine = '';
	// Read in entire file
	$lines = file('uploads/csv/'.$fileName);
	// Loop through each line
	foreach ($lines as $line)
	{
    // Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

    // Add this line to the current segment
    $templine .= $line;
    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';')
    {
        // Perform the query
        mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
        // Reset temp variable to empty
        $templine = '';
    }
   }

   #Delete temporary files without any warning
   @unlink("/uploads/csv/".$fileName);
   return true;
	}
	function initialize_tables(){

	$this->db->empty_table('participant_details');
	$this->db->empty_table('participant_item_details');
	$this->db->empty_table('certificate_master');
	$this->db->empty_table('cluster_master');
	$this->db->empty_table('cluster_participant_details');
	$this->db->empty_table('item_absentee_master');
	$this->db->empty_table('result_master');
	$this->db->empty_table('result_publish');
	$this->db->empty_table('school_details');
	$this->db->empty_table('school_master');
	$this->db->empty_table('school_point_details');
	$this->db->empty_table('stage_item_master');
	$this->db->empty_table('stage_master');
	$this->db->empty_table('user_rights');
	$this->db->empty_table('z_login_log');
	$this->db->empty_table('z_participant_details_log');
	$this->db->empty_table('z_participant_item_details_log');
	$this->db->empty_table('z_school_confirm_log');
	$this->db->empty_table('z_school_details_log');
	$this->db->empty_table('z_school_master_log');
	$this->db->empty_table('z_school_master_log');

	$query1 = $this->db->query("DELETE FROM `user_master` WHERE `user_type` <> 0 AND `user_type` <> 3");
	$query2 = $this->db->query("
UPDATE `kalolsavam_master` SET `logo_name` = NULL WHERE `kalolsavam_master`.`kalolsavam_id` =1 LIMIT 1");
	$query4 = $this->db->query("UPDATE `sub_district_master` set confirm_data_entry = 'N' WHERE 1");
	$query5 = $this->db->query("ALTER TABLE `participant_details`  AUTO_INCREMENT = 1001");
	$query7 = $this->db->query("UPDATE `sub_dist_kalolsavam_master` set `venue` = '', `start_date` = '', `end_date` = '', `logo_name` = '' WHERE 1");
	return true;
}
}
?>

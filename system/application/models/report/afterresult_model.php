<?php
class Afterresult_model extends Model{
	function Afterresult_model()
	{
		parent::Model();
	}
	function school_wise_result($school)
	{
	
     $query="SELECT SPD.item_code,IM.item_name,SPD.participant_id,RM.percentage,RM.total_mark,RM.grade,SPD.point,RM.rank,PD.participant_name,SPD.school_code,SM.school_name,SPD.is_withheld,FM.fest_name 
	 				from  result_master AS RM
	                JOIN   school_point_details AS SPD ON RM.participant_id=SPD.participant_id and  RM.item_code=SPD.item_code 
					JOIN participant_details AS PD ON PD.participant_id=RM.participant_id 
					JOIN item_master AS IM ON IM.item_code=SPD.item_code
					
					JOIN school_master AS SM ON SM.school_code=RM.school_code
					JOIN festival_master AS FM ON IM.fest_id=FM.fest_id
					where  SPD.school_code = $school and SPD.is_withheld='N' and RM.is_finish='Y' and RM.grade!=''
					
					order by FM.fest_id,IM.item_code,RM.total_mark desc ";

                   $school_wise_result		=$this->db->query($query);
                   return   $school_wise_result->result_array();
	}
	function all_school_wise_result()
	{
	
     $query="SELECT SPD.item_code,IM.item_name,SPD.participant_id,RM.percentage,RM.total_mark,RM.grade,SPD.point,RM.rank,PD.participant_name,SPD.school_code,SM.school_name,SPD.is_withheld,FM.fest_name,FM.fest_id
	 				from  result_master AS RM
	                JOIN   school_point_details AS SPD ON RM.participant_id=SPD.participant_id and  RM.item_code=SPD.item_code 
					JOIN participant_details AS PD ON PD.participant_id=RM.participant_id 
					JOIN item_master AS IM ON IM.item_code=SPD.item_code
					
					JOIN school_master AS SM ON SM.school_code=RM.school_code
					JOIN festival_master AS FM ON IM.fest_id=FM.fest_id
					where  SPD.is_withheld='N' and RM.is_finish='Y' and  RM.grade!=''
					order by FM.fest_id,IM.item_code,RM.total_mark desc ";

                   $school_wise_result		=$this->db->query($query);
                   return   $school_wise_result->result_array();
	}
	function school_wise_result_all()
	{
	
				 $query="SELECT SPD.school_code, SM.school_name, sum( SPD.point) AS cnt, FM.fest_id, FM.fest_name
			FROM school_point_details AS SPD
			JOIN school_master AS SM ON SM.school_code = SPD.school_code
			LEFT JOIN item_master AS IM ON IM.item_code = SPD.item_code
			LEFT JOIN festival_master AS FM ON IM.fest_id = FM.fest_id
			GROUP BY SPD.school_code, FM.fest_id
			ORDER BY FM.fest_id, sum(SPD.point ) DESC";

                   $school_wise_result		=$this->db->query($query);
                   return   $school_wise_result->result_array();
	}
	function school_wise_gradedetails()
	{
				$rt="SELECT count( r.grade ) as grd , r.school_code, r.grade, i.fest_id
			FROM result_master AS r
			JOIN item_master AS i ON i.item_code = r.item_code
			where r.is_finish='Y' and r.is_taken='Y' and r.grade!=''
			GROUP BY r.grade, r.school_code, i.fest_id
			ORDER BY i.fest_id, r.school_code";
			 $school_wise_result		=$this->db->query($rt);
                   return   $school_wise_result->result_array();
			
	
	
	}
	function count_of_items()
	{
	      $query="select count(distinct r.item_code) as cn,f.fest_id from result_master as r
		  join item_master AS i on r.item_code=i.item_code 
		  join festival_master AS f on i.fest_id=f.fest_id 
		  where r.is_finish = 'Y' 
		  group by f.fest_id ";
		   $count_of_items		=$this->db->query($query);
                   return   $count_of_items->result_array();
	}
	function count_of_items_total()
	{
	      $query="select count(distinct s.item_code) as c,f.fest_id from stage_item_master as s
		  join item_master AS i on s.item_code=i.item_code 
		  join festival_master AS f on i.fest_id=f.fest_id 
		  group by f.fest_id ";
		  $count_of_items_total		=$this->db->query($query);
                   return   $count_of_items_total->result_array();
	}
	
	
	function rankwise_participant_result($festival)
	{  
	$this->db->where('fm.fest_id',$festival);
		$this->db->select('rs.item_code,rs.code_no,rs.marks,rs.total_mark,rs.percentage,rs.grade,   rs.point,rs.rank,im.item_name,sm.school_name,pd.participant_name,rs.participant_id,si.start_time,fm.fest_id,fm.fest_name,si.no_of_participant');
		$this->db->from('result_master AS rs');
		$this->db->join('item_master AS im','im.item_code = rs.item_code');
		$this->db->join('school_master AS sm','sm.school_code = rs.school_code');
		$this->db->join('participant_details AS pd','pd.participant_id = rs.participant_id','LEFT');
		$this->db->join('stage_item_master AS si','si.item_code = rs.item_code');
		$this->db->join('festival_master AS fm','fm.fest_id = im.fest_id');
	    $this->db->order_by('rs.item_code');
		$this->db->order_by('rs.rank');
		$details	=	$this->db->get();
			
      return  $details->result_array();
	
	}
	
	
	                function prepare_select_box_data_special($festival){
					$this->db->where('im.fest_id',$festival);
		            $this->db->select('rs.rank');
		            $this->db->from('result_master AS rs');
		            $this->db->join('item_master AS im','im.item_code = rs.item_code');
		            $this->db->group_by('rs.rank');
					$details	=	$this->db->get();
			
                     return  $details->result_array();
	
	
	
	           }
         function status_of_kalolsavam($fest,$date)
	 {
	 if($date!='All')
	 {
	 if($fest!='All')
	 {
	 $query="SELECT RP.item_code,IM.item_name,FM.fest_name,FM.fest_id from result_master AS RP
	 JOIN stage_item_master AS sm ON sm.item_code = RP.item_code
AND date( start_time ) = '$date'
	  LEFT JOIN item_master AS IM ON IM.item_code=RP.item_code LEFT JOIN festival_master AS FM ON IM.fest_id=FM.fest_id where IM.fest_id=$fest and RP.is_finish='Y' group by RP.item_code order by FM.fest_id";

                   $school_wise_result		=$this->db->query($query);
                   return   $school_wise_result->result_array();
				   }
	else
	{
	$query="SELECT RP.item_code,IM.item_name,FM.fest_name,FM.fest_id from result_master AS RP
	 JOIN stage_item_master AS sm ON sm.item_code = RP.item_code
AND date( start_time ) = '$date'
	 LEFT JOIN item_master AS IM ON IM.item_code=RP.item_code LEFT JOIN festival_master AS FM ON IM.fest_id=FM.fest_id
	 where RP.is_finish='Y'
	 group by RP.item_code
	  order by FM.fest_id";

                   $school_wise_result		=$this->db->query($query);
                   return   $school_wise_result->result_array();
	
	}
	}
	else
	{
	 if($fest!='All')
	 {
	 $query="SELECT RP.item_code,IM.item_name,FM.fest_name,FM.fest_id from result_master AS RP
	 JOIN stage_item_master AS sm ON sm.item_code = RP.item_code

	  LEFT JOIN item_master AS IM ON IM.item_code=RP.item_code LEFT JOIN festival_master AS FM ON IM.fest_id=FM.fest_id where IM.fest_id=$fest and RP.is_finish='Y' group by RP.item_code order by FM.fest_id";

                   $school_wise_result		=$this->db->query($query);
                   return   $school_wise_result->result_array();
				   }
	else
	{
	$query="SELECT RP.item_code,IM.item_name,FM.fest_name,FM.fest_id from result_master AS RP
	 JOIN stage_item_master AS sm ON sm.item_code = RP.item_code

	 LEFT JOIN item_master AS IM ON IM.item_code=RP.item_code LEFT JOIN festival_master AS FM ON IM.fest_id=FM.fest_id
	 where RP.is_finish='Y'
	 group by RP.item_code
	  order by FM.fest_id";

                   $school_wise_result		=$this->db->query($query);
                   return   $school_wise_result->result_array();
	
	}
	}
	 
	 }
	 function status_of_kalolsavam1($fest,$festdate)
	 {
	 if($festdate!='All')
	 {  
	 if($fest!='All') 
	 {
				   $query1="SELECT IM.item_name, FM.fest_name, IM.item_code,FM.fest_id
							FROM item_master AS IM
							JOIN stage_item_master AS sm ON sm.item_code = IM.item_code
							AND date( start_time ) = '$festdate'
							LEFT JOIN festival_master AS FM ON IM.fest_id = FM.fest_id
							WHERE IM.fest_id ='$fest'
							AND IM.item_code NOT
							IN (
							SELECT distinct(RP.item_code)
							FROM result_master AS RP
							LEFT JOIN item_master AS IM ON IM.item_code = RP.item_code
							LEFT JOIN festival_master AS FM ON IM.fest_id = FM.fest_id
							WHERE IM.fest_id =$fest)order by FM.fest_id";
							
				   $school_wise_result		=$this->db->query($query1);
                   return   $school_wise_result->result_array();
				   }
		else
		{
		        $query1="SELECT IM.item_name, FM.fest_name, IM.item_code, FM.fest_id
								FROM item_master AS IM
									JOIN stage_item_master AS sm ON sm.item_code = IM.item_code
						AND date( start_time ) = '$festdate'
							JOIN festival_master AS FM ON IM.fest_id = FM.fest_id
												AND IM.item_code NOT
								IN (
								
								SELECT DISTINCT (
								rp.item_code
								)
								FROM result_master AS rp
								)
								ORDER BY FM.fest_id";
				   $school_wise_result		=$this->db->query($query1);
                   return   $school_wise_result->result_array();	
	 }
	 }
	 else
	 {
	 if($fest!='All') 
	 {
				   $query1="SELECT IM.item_name, FM.fest_name, IM.item_code,FM.fest_id
							FROM item_master AS IM
							JOIN stage_item_master AS sm ON sm.item_code = IM.item_code
							LEFT JOIN festival_master AS FM ON IM.fest_id = FM.fest_id
							WHERE IM.fest_id ='$fest'
							AND IM.item_code NOT
							IN (
							SELECT distinct(RP.item_code)
							FROM result_master AS RP
							LEFT JOIN item_master AS IM ON IM.item_code = RP.item_code
							LEFT JOIN festival_master AS FM ON IM.fest_id = FM.fest_id
							WHERE IM.fest_id =$fest)order by FM.fest_id";
							
				   $school_wise_result		=$this->db->query($query1);
                   return   $school_wise_result->result_array();
				   }
		else
		{
		        $query1="SELECT IM.item_name, FM.fest_name, IM.item_code, FM.fest_id
								FROM item_master AS IM
									JOIN stage_item_master AS sm ON sm.item_code = IM.item_code
							JOIN festival_master AS FM ON IM.fest_id = FM.fest_id
												AND IM.item_code NOT
								IN (
								
								SELECT DISTINCT (
								rp.item_code
								)
								FROM result_master AS rp
								)
								ORDER BY FM.fest_id";
				   $school_wise_result		=$this->db->query($query1);
                   return   $school_wise_result->result_array();	
	 }
	 }
	 }

	 function total_points()
	 {
	  	$query="SELECT SPD.item_code,IM.item_name,SPD.participant_id,RM.percentage,RM.grade,SPD.point,RM.rank,PD.participant_name,SPD.school_code,SM.school_name,SPD.is_withheld,FM.fest_name,FM.fest_id from  result_master AS RM
		                   JOIN school_point_details AS SPD ON RM.participant_id=SPD.participant_id and RM.item_code=SPD.item_code
						  JOIN participant_details AS PD ON PD.participant_id=SPD.participant_id and PD.school_code=SPD.school_code 
					      JOIN item_master AS IM ON IM.item_code=SPD.item_code
						
						   JOIN school_master AS SM ON SM.school_code=RM.school_code
						   JOIN festival_master AS FM ON IM.fest_id=FM.fest_id
						where SPD.is_withheld='N' AND RM.is_finish='Y' AND RM.grade!=''  
						group by SPD.participant_id,SPD.item_code  order by FM.fest_id asc,IM.item_code,RM.total_mark desc ";
	
					   $school_wise_result		=$this->db->query($query);
					   return   $school_wise_result->result_array();
	}
	function report_press()
	 {
	  	$query="SELECT SPD.item_code,IM.item_name,SPD.participant_id,RM.percentage,RM.grade,SPD.point,RM.rank,PD.participant_name,SPD.school_code,SM.school_name,SPD.is_withheld,FM.fest_name,FM.fest_id from  result_master AS RM
		                   JOIN school_point_details AS SPD ON RM.participant_id=SPD.participant_id and RM.item_code=SPD.item_code
						  JOIN participant_details AS PD ON PD.participant_id=SPD.participant_id and PD.school_code=SPD.school_code 
					      JOIN item_master AS IM ON IM.item_code=SPD.item_code
						
						   JOIN school_master AS SM ON SM.school_code=RM.school_code
						   JOIN festival_master AS FM ON IM.fest_id=FM.fest_id
						where SPD.is_withheld='N' AND RM.is_finish='Y' AND RM.grade!='' group by SPD.participant_id,SPD.item_code  order by FM.fest_id asc,IM.item_code,RM.total_mark desc ";
	
					   $school_wise_result		=$this->db->query($query);
					   return   $school_wise_result->result_array();
	}
	function higherlevel_result($festid,$item)
	{
		
					   
			$this->db->select('r.item_code, r.participant_id, r.school_code, r.grade, m.school_name, p.participant_name, p.class, f.fest_name,f.fest_id, i.item_name');
			$this->db->from('result_publish AS r');
			$this->db->join('school_master AS m','m.school_code = r.school_code');
			$this->db->join('participant_details AS p',"p.participant_id = r.participant_id AND class >4");
			$this->db->join('item_master AS i','i.item_code = r.item_code');
			$this->db->join('festival_master AS f','f.fest_id = i.fest_id');
			$this->db->join('result_master AS rm',"rm.item_code = r.item_code and rm.is_taken = 'Y' and rm.is_finish = 'Y'");
			$this->db->where("r.is_withheld = 'N'");
			if($festid!='All')
			$this->db->where("f.fest_id",$festid);
			if($item!='ALL')
			$this->db->where("r.item_code",$item);
			
		//	$this->db->where("  ");
			$this->db->group_by('r.item_code');
			$this->db->group_by('r.participant_id');
			$this->db->order_by('i.fest_id');
			$this->db->order_by('r.item_code');
			
				$details	=	$this->db->get();
				return  $details->result_array();
	}
	
	
	function schoolhigher_result($school)
	{
			$this->db->select('r.item_code, r.participant_id, r.school_code, r.grade, m.school_name, p.participant_name, p.class, f.fest_name,f.fest_id, i.item_name');
			$this->db->from('result_publish AS r');
			$this->db->join('school_master AS m','m.school_code = r.school_code');
			$this->db->join('participant_details AS p',"p.participant_id = r.participant_id AND class >4");
			$this->db->join('item_master AS i','i.item_code = r.item_code');
			$this->db->join('festival_master AS f','f.fest_id = i.fest_id');
			$this->db->where("r.is_withheld = 'N'");
			//$this->db->where("R.is_taken ='Y' and R.is_finish = 'Y'");
			$this->db->where("r.school_code",$school);
			$this->db->order_by('i.fest_id');
			$this->db->order_by('r.item_code');
			
				$details	=	$this->db->get();
				return  $details->result_array();
	}
	
	function schoolhigher_resultall()
	{
			$this->db->select('r.item_code, r.participant_id, r.school_code, r.grade, m.school_name, p.participant_name, p.class, f.fest_name,f.fest_id, i.item_name');
			$this->db->from('result_publish AS r');
			$this->db->join('school_master AS m','m.school_code = r.school_code');
			$this->db->join('participant_details AS p',"p.participant_id = r.participant_id AND class >4");
			$this->db->join('item_master AS i','i.item_code = r.item_code');
			$this->db->join('festival_master AS f','f.fest_id = i.fest_id');
			$this->db->where("r.is_withheld = 'N'");
			$this->db->order_by('i.fest_id');
			$this->db->order_by('r.item_code');
			
			$details	=	$this->db->get();
			return  $details->result_array();
		
	}
		function itemcomplete_schoolpoint()
		{
		$this->db->select('r.*, i.item_name, f.fest_id, f.fest_name');
		$this->db->from('result_publish AS r');
		$this->db->join('item_master AS i','i.item_code = r.item_code');
		$this->db->join('festival_master AS f','f.fest_id = i.fest_id');
		$this->db->group_by('r.item_code');
		$this->db->order_by('i.fest_id');
		$details	=	$this->db->get();
		return  $details->result_array();
		}
	
	}
	?>
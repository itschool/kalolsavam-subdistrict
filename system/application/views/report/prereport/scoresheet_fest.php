<br />
<?php echo form_open('report/prereportpdf/fest_scoresheet_details', array('id' => 'formPWD','target'=>'_blank'));
echo blue_box_top();

		
		
?>

<table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" class="heading_tab" style="margin-top:15px;">
  <tr>
    <th colspan="4" align="left"><strong>Score Sheet - Festival wise</strong></th>
  </tr>
 
  <tr>
    <td width="10%" class="">&nbsp;</td>
    <td width="27%" align="left" class="table_row_first"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Festival  :</td>
    <td width="55%" align="left" class="table_row_first"><?php echo form_dropdown("txtfestFrom", array($retdat[0]['fest_id']=>$retdat[0]['fest_name'],$retdat[1]['fest_id']=>$retdat[1]['fest_name'],
	$retdat[2]['fest_id']=>$retdat[2]['fest_name'],
	$retdat[3]['fest_id']=>$retdat[3]['fest_name'],
	$retdat[4]['fest_id']=>$retdat[4]['fest_name'],
	$retdat[5]['fest_id']=>$retdat[5]['fest_name'],
	$retdat[6]['fest_id']=>$retdat[6]['fest_name'],
	$retdat[7]['fest_id']=>$retdat[7]['fest_name'],
	$retdat[8]['fest_id']=>$retdat[8]['fest_name']
	),'id="txtfestFrom"');?></td>
    <td width="18%">&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td class="table_row_first">&nbsp;</td>
    <td align="left" class="table_row_first"><?php echo form_submit('Report', 'View Report');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
  	<td>&nbsp;</td>
  </tr>
</table>
<?php
echo blue_box_bottom();
echo form_close();
?>
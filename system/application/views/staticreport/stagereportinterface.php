<?php echo form_open('staticreport/Onstagereport1/rpt_stagereport', array('id' => 'formStageReport','target'=>'_blank'));
echo blue_box_top();
?>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" class="heading_tab" style="margin-top:15px;">
  <tr>
    <th colspan="4" align="left">Select Item</th>
  </tr>
  
  <tr>
    <td width="10%">&nbsp;</td>
    <td align="left" width="27%" class="table_row_first">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stage Name : </td>
  
    <td align="left" width="55%" class="table_row_first"><div id="cmbitem"><?php echo form_dropdown("cmbstage",$stage,'', 'class="input_box" id="cmbstage" '  );?></div></td>
    <td width="18%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" class="table_row_first">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date :</td>
    <td align="left" class="table_row_first"><input class="input_box date_field" type="text" onFocus="displayCalendar($('txtDate'),'yyyy-mm-dd',this)" name="txtDate" id="txtDate" value="<?php echo @$date; ?>">
          	<!--<input class="input_box date_field" type="text"   onFocus="javascript:vDateType='3'" onBlur="DateFormat(this,this.value,event,true,'3')" onKeyDown="DateFormat(this,this.value,event,false,'3')" onKeyUp="DateFormat(this,this.value,event,false,'3')" maxlength="10"  name="txtDate" id="txtDate" value="<?php //echo $start_date; ?>">-->
            <img src="<?php echo image_url();?>calender.gif" onClick="displayCalendar($('txtDate'),'yyyy-mm-dd',this)" width="16" height="16" style="cursor:pointer" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" width="27%" class="table_row_first">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td align="left" width="55%" class="table_row_first">
      <label>
        <input type="submit" name="btnSubmit" id="btnSubmit" value="View report">
        </label>       </td>
    <td width="18%">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="center" colspan="4">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="stagename" id="stagename" />
</form>
<?php
//itemwise_report_interface
echo blue_box_bottom();
echo form_close();
?>
<?php echo form_open('import/initialize_database_confirm', array('id' => 'formInitialize'));
echo blue_box_top();

?>
<style type="text/css">
<!--
.style1 {
	color: #990000;
	font-size: 24px;
}
.style2 {
	color: #660000;
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" class="heading_tab" style="margin-top:15px;">
	
	<tr>
		<th colspan="4" align="left"><div align="center" class="style1">Initialize Database</div>       </th>
  </tr>
    <tr>
    	<td colspan="4" align="left"><div align="center" class="style2">If you initialize the database all the data will be loss . Take a backup before you initialize the database.</div>       </td>
    </tr>
     <tr>
    <td colspan="4" align="left"><div align="center" class="style2"><a href="<?php echo base_url();?>import/backup_data"><a href="<?php echo base_url();?>import/backup_data">To take a backup of your database . Please click here</a></a><strong></strong></div>       </td>
    </tr>
    <tr>
		<td align="center" colspan="3">
			&nbsp;
		</td>
	</tr>
    <tr>
		<td align="center" colspan="3">
			&nbsp;
		</td>
	</tr>
    <tr>
		<td align="center" colspan="3">
			&nbsp;
		</td>
	</tr>
    <tr>
		<td align="center" colspan="3">
        <fieldset>
            <legend>Initialize Kalolsavam Database </legend>
            	<?php echo form_submit('Initialize Database', 'Initialize Database');?>
           
        </fieldset>
		</td>
	</tr>
    </table>
<?php
echo blue_box_bottom();
echo form_close();
?>

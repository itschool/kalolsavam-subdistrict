<div align="center" class="heading_gray">
	<h3>Export Result Data</h3>
</div>
<br/>
<?php
	echo form_open('', array('id' => 'export_dist_data'));
	echo blue_box_top();
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" class="heading_tab" style="margin-top:15px;">
		<tr><th colspan="2" align="left">
			Export Result Data for District Kalolsavam
        </th>
		<tr>
			<td width="12%" align="left" class="table_row_first">
				<input type="hidden" name="hidExport" id="hidExport" />
				<?php print(form_button('data_export','Export Data','onClick="javascript:fncExportDistrictData();return false;"'));?>			</td>
		    <td width="88%" align="left" class="table_row_first"><?php print(form_button('export_photo','Export Photo','onClick="javascript:fnc_export_photo();return false;"'));?></td>
		</tr>
	</table>
<?php
	echo blue_box_bottom();
	echo form_close();
?>
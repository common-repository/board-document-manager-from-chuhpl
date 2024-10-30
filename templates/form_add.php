<?PHP if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
<div id="icon-plugins" class="icon32"></div>
<h2><?PHP _e( 'Board Document Management', 'board-document-manager-from-chuhpl' ); ?></h2>
</div>
<h2><?PHP _e( 'Add a document', 'board-document-manager-from-chuhpl' ); ?></h2>
<script language="javaScript">
var type;

function show_hidden_type(selectObj){
	if(selectObj.options[selectObj.selectedIndex].value == 'agen') {
		document.getElementById("agendaShow").style.display='';
		document.getElementById("minutesShow").style.display='none';
	} else if(selectObj.options[selectObj.selectedIndex].value == 'mins') {
		document.getElementById("agendaShow").style.display='none';
		document.getElementById("minutesShow").style.display='';
	} else {
		document.getElementById("agendaShow").style.display='none';
		document.getElementById("minutesShow").style.display='none';
	}
}
</script>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<?php 
if( !empty( $this->error_msg ) ): ?>
  <table class="bdmNiceTable error">
    <tr>
      <td><?php _e( 'Error!', 'board-document-manager-from-chuhpl' ); ?></td>
    </tr>
    <tr>
      <td><?php echo $this->error_msg; ?></td>
    </tr>
  </table><br />
 <?php  endif ?>
  <table class="bdmNiceTable">
    <tr>
      <td colspan="2"><?php _e( 'Add a document', 'board-document-manager-from-chuhpl' ); ?> </td>
    </tr>
    <tr>
      <td><?php _e( 'Document Type', 'board-document-manager-from-chuhpl' ); ?></td>
      <td>
        <select name="formType" id="formType" onchange="show_hidden_type(this)">
          
<?php
	# type drop down, none selected
	$selected = ($this->externals['formType'] == NULL) ? ' selected="selected"' : NULL;
	echo "<option value=\"\"{$selected}>".__( 'Choose a Doc Type', 'board-document-manager-from-chuhpl' )."</option>";
	
	foreach( $this->typesArr as $key => $val ):
		$selected = ( $this->externals['formType'] == $key ) ? ' selected="selected"' : NULL;
		echo "<option value=\"{$key}\"{$selected}>{$val}</option>";
	endforeach;
?>
        </select>
      </td>
    </tr>
  </table>
  <br />
  <div id="minutesShow" style="display:none">
  <table class="bdmNiceTable">
    <tr>
      <td colspan="2"><?php _e( 'Minutes', 'board-document-manager-from-chuhpl' ); ?></td>
    </tr>
    <tr>
      <td><?php _e( 'Date', 'board-document-manager-from-chuhpl' ); ?></td>
      <td>
        <select name="formYear_mins" id="formYear_mins">
<?PHP
# minutes year drop down
	$selected = ($this->externals['formYear_mins'] == NULL) ? ' selected="selected"' : NULL;
	echo "<option value=\"\"{$selected}>".__( "Choose Year", "board-document-manager-from-chuhpl")."</option>";
	$yearStart	= date_i18n('Y') - 2; $yearEnd	= date_i18n('Y') + 1;
	for( $y= $yearStart; $y <= $yearEnd; $y++ ):
		$selected = ($this->externals['formYear_mins'] == $y) ? ' selected="selected"' : NULL;
		echo "<option value=\"{$y}\"{$selected}>{$y}</option>";
	endfor;
?>
        </select>
        <select name="formMonth_mins" id="formMonth_mins">
<?PHP
	# minutes month drop down
	$selected = ($this->externals['formMonth_mins'] == NULL) ? ' selected="selected"' : NULL;
	echo "<option value=\"\"{$selected}>".__( "Choose Month", "board-document-manager-from-chuhpl")."</option>";
	for($month = 1; $month <= 12; $month++):
		$selected = ($this->externals['formMonth_mins'] == $month) ? ' selected="selected"' : NULL;
		$monthName = date_i18n( 'F', mktime(0,0,0,$month, 1, 2000) );
		echo "<option value=\"{$month}\"{$selected}>".$monthName."</option>";
	endfor;
?>
        </select>&nbsp;&nbsp;&nbsp;<?PHP
		/* translators: This is on the add page after the Minutes year and month to specify the next column is the day */
		 _e( 'Day:', 'board-document-manager-from-chuhpl' ); ?>
        <input name="formDay_mins" type="text" id="formDay_mins" value="<?PHP echo $this->externals['formDay_mins']; ?> " size="2" />
    	&nbsp;
<?PHP
	$checked = ( !empty( $this->externals['formSpecial_mins'] ) ) ? ' checked="checked"' : NULL;
	_e( 'Special', 'board-document-manager-from-chuhpl' );
	echo "&nbsp;<input name=\"formSpecial_mins\" type=\"checkbox\" id=\"formSpecial_mins\" value=\"TRUE\"{$checked} />";
?>
        </td>
    </tr>
  </table><br />
</div>
<div id="agendaShow" style="display:">
  <table class="bdmNiceTable">
    <tr>
      <td colspan="2"><?PHP _e( 'Agenda', 'board-document-manager-from-chuhpl' ); ?></td>
    </tr>
    <tr>
      <td><?PHP _e( 'Date', 'board-document-manager-from-chuhpl' ); ?></td>
      <td>
      
        <select name="formYear_agen" id="formYear_agen">
<?PHP
# agenda year drop down
	$selected = ($this->externals['formYear_agen'] == NULL) ? ' selected="selected"' : NULL;
	echo "<option value=\"\"{$selected}>".__( "Choose Year", "board-document-manager-from-chuhpl")."</option>";
	$yearStart	= date_i18n('Y') - 2; $yearEnd	= date_i18n('Y') + 1;
	for( $y= $yearStart; $y <= $yearEnd; $y++ ):
		$selected = ($this->externals['formYear_agen'] == $y) ? ' selected="selected"' : NULL;
		echo "<option value=\"{$y}\"{$selected}>{$y}</option>";
	endfor;
?>
        </select>
        <select name="formMonth_agen" id="formMonth_agen">
<?PHP
	# agenda month drop down
	$selected = ($this->externals['formMonth_agen'] == NULL) ? ' selected="selected"' : NULL;
	echo "<option value=\"\"{$selected}>".__( "Choose Month", "board-document-manager-from-chuhpl")."</option>";
	for($month = 1; $month <= 12; $month++):
		$selected = ($this->externals['formMonth_agen'] == $month) ? ' selected="selected"' : NULL;
		$monthName = date_i18n( 'F', mktime(0,0,0,$month, 1, 2000) );
		echo "<option value=\"{$month}\"{$selected}>".$monthName."</option>";
	endfor;
?>
        </select>&nbsp;&nbsp;&nbsp;<?PHP
		/* translators: This is on the add page after the Agenda year and month to specify the next column is the day */
		 _e( 'Day:', 'board-document-manager-from-chuhpl' ); ?>
        <input name="formDay_agen" type="text" id="formDay_agen" value="<?PHP echo $this->externals['formDay_agen']; ?> " size="2" />
    	&nbsp;
<?PHP
	$checked = ( !empty( $this->externals['formSpecial_agen'] ) ) ? ' checked="checked"' : NULL;
	_e( 'Special', 'board-document-manager-from-chuhpl' );
	echo "&nbsp;<input name=\"formSpecial_agen\" type=\"checkbox\" id=\"formSpecial_agen\" value=\"TRUE\"{$checked} />";
?>
</td>
    </tr>
  </table><br />
</div>
  <table class="bdmNiceTable">
    <tr>
      <td><?PHP _e( 'Attach PDF', 'board-document-manager-from-chuhpl' ); ?></td>
    </tr>
    <tr>
      <td><input name="file" type="file" id="file" size="50" /></td>
    </tr>
    <tr>
      <td align="center"><input name="formCode" type="hidden" id="formCode" value="<?PHP echo $this->externals['formCode']; ?>" />
        <input name="action" type="hidden" id="action" value="add_it" />
      <input type="submit" name="Submit" value="Submit" /></td>
    </tr>
  </table>

</form>
<p><a href="?page=bdm_mainShow"><?PHP _e( 'Return to document list', 'board-document-manager-from-chuhpl' ); ?> >></a></p>
<script language="javascript">
	var type_val = document.getElementById("formType");
	show_hidden_type(type_val)
  </script>

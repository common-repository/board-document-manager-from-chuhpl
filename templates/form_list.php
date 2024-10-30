<?PHP if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
<div id="icon-plugins" class="icon32"></div>
<h2><?php _e( 'Board Document Management', 'board-document-manager-from-chuhpl' ); ?></h2>
</div>
<h2><?PHP _e( 'View Documents', 'board-document-manager-from-chuhpl' ); ?></h2>
<?PHP
$docTypeName = $this->returnType( $this->externals['type'] );

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
    <td colspan="3"><?PHP _e( 'Board Documents - View List', 'board-document-manager-from-chuhpl' ); ?> </td>
  </tr>
  <tr>
    <td width="25%" align="center"><a href="?page=bdm_mainShow&action=view&type=all"><?PHP _e( 'All', 'board-document-manager-from-chuhpl' ); ?></a></td>
    <td width="25%" align="center"><a href="?page=bdm_mainShow&action=view&type=mins"><?PHP _e( 'Minutes', 'board-document-manager-from-chuhpl' ); ?></a> </td>
    <td width="25%" align="center"><a href="?page=bdm_mainShow&action=view&type=agen"><?PHP _e( 'Agendas', 'board-document-manager-from-chuhpl' ); ?></a></td>
  </tr>
</table>
<br />
<table class="bdmNiceTable">
  <tr>
    <td colspan="4"><?PHP echo( sprintf( __( 'Board Documents - %1s', 'board-document-manager-from-chuhpl' ), $docTypeName ) ); ?></td>
  </tr>
  <tr>
    <td><?PHP _e( 'Type', 'board-document-manager-from-chuhpl' ); ?></td>
    <td><?PHP _e( 'Date', 'board-document-manager-from-chuhpl' ); ?></td>
    <td><?PHP _e( 'Staff', 'board-document-manager-from-chuhpl' ); ?></td>
    <td><?PHP _e( 'Actions', 'board-document-manager-from-chuhpl' ); ?></td>
  </tr>
<?PHP
if( 0 == count( $this->items ) ):
	?>
	  <tr>
		<td colspan="4"><?PHP _e( 'No documents to view.', 'board-document-manager-from-chuhpl' ); ?></td>
	  </tr>
	<?PHP
else:
	foreach( $this->items as $key => $val ):
	?>
	  <tr>
		<td><strong><a href="<?PHP echo "?page=bdm_mainShow&action=showMe&id={$val['id']}"; ?>" target="_blank">
		<?PHP 
		echo $this->returnType( $val['type'] );
		$special = ( !empty( $val['special'] ) ) ? __( ' (Special)', 'board-document-manager-from-chuhpl' ) : NULL;
		echo $special;
		?>
		</a></strong></td>
		<td><?PHP echo date_i18n( get_option( 'date_format' ), $val['date'] ); ?></td>
		<td><strong><?PHP echo $val['staff']; ?></strong></span></td>
		<td align="right" nowrap="nowrap"><span class="smaller"><a href="?page=bdm_mainShow&action=delete&id=<?PHP echo $val['id']; ?>">Delete</a></span> </td>
	</tr>
	<?PHP
	endforeach;
endif;
?>
</table>
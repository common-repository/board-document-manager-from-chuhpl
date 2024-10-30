<?PHP
/*
<?PHP if ( ! defined( 'ABSPATH' ) ) exit; ?>
<h3><?PHP _e( 'Upcoming Board Agenda', 'board-document-manager-from-chuhpl' ); ?></h3>

# view agenda
$query = "SELECT bc_id, bc_date, bc_special
			FROM  {$wpdb->prefix}boardDocumentManager 
			WHERE  bc_type =  'agen'
			ORDER BY  bc_date DESC 
			LIMIT 1";

$final = $wpdb->get_row( $query );

if( !$final ):
	# no agendas
	?>
	<p><?PHP _e( 'No agendas to view', 'board-document-manager-from-chuhpl' ); ?></p>
	<?PHP
	#some agendas
else:
	$archived = ( $final->bc_date <= time() ) ? $archived = '&nbsp;[Archived]' : NULL;
	?>
	<p><a href="<?PHP echo "?page=bdm_mainShow&action=showMe&id={$final->bc_id}"; ?>" target="_blank">
	<strong><?PHP _e( 'Agenda:', 'board-document-manager-from-chuhpl' ); ?>&nbsp;</strong>&nbsp;<?PHP echo date_i18n( get_option( 'date_format' ), $final->bc_date); ?><strong><?PHP echo $archived;?></strong></a></p>
	<?PHP
endif;
*/
?>
<h3><?PHP _e( 'Board Minutes', 'board-document-manager-from-chuhpl' ); ?></h3>
<?PHP
$query = "SELECT bc_id, bc_type, bc_date, bc_fileName, bc_special
			FROM  {$wpdb->prefix}boardDocumentManager 
			WHERE bc_type='mins'
			ORDER BY bc_date DESC";

$final = $wpdb->get_results( $query, ARRAY_A );
if( !$final ):
	?>
	<p><?PHP _e( 'No board minutes to view.', 'board-document-manager-from-chuhpl' ); ?></p>
	<?PHP
else:
	foreach( $final as $val ): 
		$special = ( !empty( $val['bc_special'] ) ) ? __( ' (Special)', 'board-document-manager-from-chuhpl' ) : NULL;
		
		?><a href="<?PHP echo "?page=bdm_mainShow&action=showMe&id={$val['bc_id']}"; ?>" target="_blank"><?PHP echo date_i18n( get_option( 'date_format' ), $val['bc_date'] ).$special; ?></a><br><?PHP
	endforeach;
endif;
?>
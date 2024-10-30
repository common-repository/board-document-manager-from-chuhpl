<?PHP if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
<div id="icon-plugins" class="icon32"></div>
<h2><?php _e( 'Board Document Management', 'board-document-manager-from-chuhpl' ); ?></h2>
</div>
<h2><?PHP _e( 'Latest Documents', 'board-document-manager-from-chuhpl' ); ?></h2>
<table class="bdmNiceTable">
  <tr>
    <td width="33%"><?PHP _e( 'Minutes', 'board-document-manager-from-chuhpl' ); ?></td>
    <td width="33%"><?PHP _e( 'Agenda', 'board-document-manager-from-chuhpl' ); ?></td>
   <!-- <td width="33%"><PHP _e( 'Meeting Notice', 'board-document-manager-from-chuhpl' ); ></td>-->
  </tr>
  <?PHP
global $wpdb;
$latestMinsArr = $wpdb->get_row( "SELECT bc_id, bc_special, bc_date FROM {$wpdb->prefix}boardDocumentManager WHERE bc_type ='mins' ORDER BY bc_date DESC LIMIT 1" );
$latestAgenArr = $wpdb->get_row( "SELECT bc_id, bc_special, bc_date FROM {$wpdb->prefix}boardDocumentManager WHERE bc_type ='agen' ORDER BY bc_date DESC LIMIT 1" );
#$latestMnotArr = $wpdb->get_row( "SELECT bc_id, bc_special, bc_date FROM {$wpdb->prefix}boardDocumentManager WHERE bc_type ='mnot' ORDER BY bc_date DESC LIMIT 1" );

# Minutes
if( !empty( $latestMinsArr->bc_date ) ):
	$special	= ( !empty( $latestMinsArr->bc_special ) )	? ' (Special)' : NULL;
	$latestMins = '<a href="?page=bdm_mainShow&action=showMe&id='.$latestMinsArr->bc_id.'">'.date_i18n( get_option( 'date_format' ), $latestMinsArr->bc_date ) . $special.'</a>';
else:
	$latestMins = __( 'None', 'board-document-manager-from-chuhpl' );
endif;	

# Agenda
if( !empty( $latestAgenArr->bc_date ) ):
	$special	= ( !empty( $latestAgenArr->bc_special ) )	? ' (Special)' : NULL;
	$latestAgen = '<a href="?page=bdm_mainShow&action=showMe&id='.$latestAgenArr->bc_id.'">'.date_i18n( get_option( 'date_format' ), $latestAgenArr->bc_date ) . $special.'</a>';
else:
	$latestAgen = __( 'None', 'board-document-manager-from-chuhpl' );
endif;

  ?>
  <tr>
    <td><?PHP echo $latestMins; ?></td>
    <td><?PHP echo $latestAgen; ?></td>
    <!--<td><PHP echo $latestMnot; ></td>-->
  </tr>
  <?PHP
  
  ?>
</table>
<h2><?PHP _e( 'Recently Activity', 'board-document-manager-from-chuhpl' ); ?></h2>
<table class="bdmNiceTable">
  <tr>
    <td><?PHP _e( 'Type', 'board-document-manager-from-chuhpl' ); ?></td>
    <td><?PHP _e( 'Date', 'board-document-manager-from-chuhpl' ); ?></td>
    <td><?PHP _e( 'Uploader', 'board-document-manager-from-chuhpl' ); ?></td>
  </tr>
<?PHP
$query = "	SELECT bc_id, bc_type, bc_date, bc_fileName, bc_special, 
					bc_changed, bc_staff
					FROM {$wpdb->prefix}boardDocumentManager 
					ORDER BY bc_changed DESC LIMIT 10";
$final = $wpdb->get_results( $query, ARRAY_A );

if( !$final ): 
?>
  <tr class="table_bg1">
    <td colspan="3"><?PHP _e( 'No recent documents', 'board-document-manager-from-chuhpl' ); ?></td>
  </tr>
<?PHP
else:
	foreach( $final as $val ):
		$special		= ( !empty( $val['bc_special'] ) ) ? ' (Special)' : NULL;
		$recentType		= $this->typesArr[$val['bc_type']] . $special;
		
		$recentDate		= date_i18n( get_option( 'date_format' ), $val['bc_date'] );
		$changedDate	= date_i18n( get_option( 'date_format' ) .' g:i a', strtotime( $val['bc_changed'] ) );

		
?>
  <tr class="#bg_class#">
    <td><strong><?PHP echo $recentType; ?><br><a href="<?PHP echo '/?page=bdm_mainShow&action=showMe&id='.$val['bc_id'];?>"><?PHP _e( 'Download', 'board-document-manager-from-chuhpl' ); ?></a></strong></td>
    <td><?PHP echo $recentDate; ?></td>
    <td><strong><?PHP echo $val['bc_staff']; ?></strong><br />
      <em><?PHP echo $changedDate; ?></em></td>
  </tr>
<?PHP
	endforeach;
endif;
?>
</table>

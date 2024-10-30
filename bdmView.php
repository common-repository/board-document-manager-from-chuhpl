<?PHP
class bdmchuhpl_View extends bdmMain
{
	var $externals = array();
	
	public function __contruct()
	{
		$this->externals = $this->getExternals();
		$this->typesArr = $this->setup_typesArr();
		$this->sourcesArr = $this->setup_sourcesArr();
	}
	
	public function add_menu()
	{
		add_submenu_page( 'bdm_main', 'Board Document Manager', 'View & Delete', 'publish_pages', 'bdm_mainShow',  array( $this, 'showDocs' ) );
		add_submenu_page( 'bdm_main', 'Board Document Manager', 'Recent Log', 'publish_pages', 'bdm_mainRecent',  array( $this, 'showRecent' ) );

	}	

	protected function getExternals()
	# Pull in POST and GET values
	{
		$final = array();

		# setup GET variables
		$getArr = array(	'action'					=> FILTER_UNSAFE_RAW, 
							'type'						=> FILTER_UNSAFE_RAW, 
							'id'						=> FILTER_UNSAFE_RAW, );

		# pull in and apply to final
		if( $getTemp = filter_input_array( INPUT_GET, $getArr ) )
			$final = array_merge( $final, $getTemp );

		# setup POST variables
		$postArr = array(	'action'					=> FILTER_UNSAFE_RAW, 
							'type'						=> FILTER_UNSAFE_RAW, );

		# pull in and apply to final
		if( $postTemp = filter_input_array( INPUT_POST, $postArr ) )
			$final = array_merge( $final, $postTemp );

		$arrayCheck = array_unique( array_merge( array_keys( $getArr ), array_keys( $postArr ) ) );

		foreach( $arrayCheck as $key ):
			if( empty( $final[$key] ) ):
				$final[$key] = NULL;
			elseif( is_array( $final[$key] ) ):
				$final[$key] = array_keys( $final[$key] );
			else:			
				$final[$key] = trim( $final[$key] );
			endif;
		endforeach;
		
		
		return $final;
	}	
	
	protected function deleteDocument( $id )
	{
		global $wpdb;
		$query = "SELECT bc_fileName FROM {$wpdb->prefix}boardDocumentManager WHERE bc_id = '{$id}'";
		$final = $wpdb->get_row( $query, ARRAY_A );
		unlink( BOARDDOCMAN_CHUHPL_FILE_PATH . '/' . $final['bc_fileName'] );
		
		$wpdb->delete( "{$wpdb->prefix}boardDocumentManager" , array( 'bc_id' => $id ) );
		
		return true;
	}
	
	public function getDocumentInfo( $id )
	{
		global $wpdb;
		$query = "SELECT bc_id as id, bc_type as type, bc_date as date, bc_fileName as filename, 
					bc_special as special, UNIX_TIMESTAMP(bc_changed) as changed, bc_staff as staff 
					FROM {$wpdb->prefix}boardDocumentManager 
					WHERE bc_id = '$id'";
		

		$final = $wpdb->get_row( $query, ARRAY_A );
	
		return $final;		
	}
	
	public function get_items( $type )
	{
		global $wpdb;
		
		if($type == NULL):
			$type = "all";
		endif;
		
		$final = array();
		
		$query = "SELECT bc_id as id, bc_type as type, bc_date as date, bc_fileName as filename, 
					bc_special as special, UNIX_TIMESTAMP(bc_changed) as changed, bc_staff as staff 
					FROM {$wpdb->prefix}boardDocumentManager";
		if($type !== 'all'):
			$query .= " WHERE bc_type = '{$type}'";
		endif;
			
		$query .= " ORDER BY bc_date DESC";
		
		$final = $wpdb->get_results( $query, ARRAY_A );
		
		return $final;

	}
	public function showDocs()
	{
		$this->items = $this->get_items( $this->externals['type'] );

		switch( sanitize_text_field( $_REQUEST['action'] ) ):
			case 'delete_it':
				# find if ID is valid and get info
				if( empty( $_GET['id'] ) ):
					$this->error_msg = __( 'A Document ID wasn\'t included in the link, so you won\'t be able to view a document.', 'board-document-manager-from-chuhpl' );
					include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_list.php' );				
				elseif( false == ( $info = $this->getDocumentInfo( $_GET['id'] ) ) ):
					$this->error_msg = __( 'A bad Document ID was included in the link, so you won\'t be able to view a document', 'board-document-manager-from-chuhpl' );
					include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_list.php' );
				else:
					$this->deleteDocument( $_GET['id'] );
					include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_delete_success.php' );
				endif;
				break;
				
			case 'delete':
				# find if ID is valid and get info
				
				if( empty( $_GET['id'] ) ):
					$this->error_msg = __( 'A Document ID wasn\'t included in the link, so you won\'t be able to view a document.', 'board-document-manager-from-chuhpl' );
					include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_list.php' );				
				elseif( false == ( $info = $this->getDocumentInfo( $_GET['id'] ) ) ):
					$this->error_msg = __( 'A bad Document ID was included in the link, so you won\'t be able to view a document', 'board-document-manager-from-chuhpl' );
					include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_list.php' );
				else:
					include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_delete.php' );
				endif;
				
				break;
				
			default:
				include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_list.php' );
				break;
		endswitch;
	}
	
	public function showRecent()
	{
		include( BOARDDOCMAN_CHUHPL_PATH . 'templates/recent_list.php' );
			
	}
	
	public function returnType( $type )
	{
		switch( $type ):
			case 'agen':
				$docTypeName = __( 'Agendas', 'board-document-manager-from-chuhpl' );
				break;
#			case 'mnot':
#				$docTypeName = __( 'Meeting Notices', 'board-document-manager-from-chuhpl' );
#				break;
			case 'mins':
				$docTypeName = __( 'Minutes', 'board-document-manager-from-chuhpl' );
				break;
			case 'all':
				$docTypeName = __( 'All Documents', 'board-document-manager-from-chuhpl' );
				break;
			default:
				$docTypeName = __( 'No type selected. Please choose an option above. ', 'board-document-manager-from-chuhpl' ).'</span>';
				break;
		endswitch;	
		
		return $docTypeName;	
	}
}
?>

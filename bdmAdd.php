<?PHP
class bdmchuhpl_Add extends bdmMain
{
	
	var $action;
	var $typesArr	= array();
	var $sourcesArr	= array();
	var $error_msg;

	var $formContent;	
	var $formDay;
	var $formMonth;	
	var $formAgenSpecial;
	var $formSpecial;
	var $formType;
	var $formYear;
	var $formCode;
	var $formSource;
	var $formFile;

	public function __construct()
	{
		$this->typesArr = $this->setup_typesArr();
		$this->sourcesArr = $this->setup_sourcesArr();
		$this->externals = $this->getExternals();
				
	}
	public function add_menu()
	{
		
		add_submenu_page( 'bdm_main', 'Board Document Manager', 'Add Doc', 'publish_pages', 'bdm_main',  array( $this, 'formAdd' ) );

	}
	
	public function formAdd()
	{
		$externals = $this->getExternals();

		switch( $this->externals['action'] )
		{		
			case 'add_it':
				if($this->check_add() == FALSE || $this->checkFormCode() == FALSE)
				{
					include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_add.php' );
					break;
				}
				
				$this->addContent();	
				update_user_meta( get_current_user_id(), 'bdmchuhpl_formCode', NULL );
				include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_add_success.php' );
				break;
								
			default:
				$this->externals['formCode'] = intval( rand( 10000000,99999999 ) );
				update_user_meta( get_current_user_id(),  'bdmchuhpl_formCode', $this->externals['formCode'] );
				$this->externals['formSource'] = 'file';
				include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_add.php' );
				break;
		}
	}
	
	function addContent()
	{
		global $ldap, $wpdb;
		
		$staff = wp_get_current_user();
		
		$staff = $staff->display_name;
		
		$special = ( $this->externals['formSpecial_agen'] == TRUE || $this->externals['formSpecial_mins'] == TRUE) ? '1' : '0';
		
		$filename = $_FILES['file']['tmp_name'];


		$specialTitle = ( true ==  $special ) ? '_special' : NULL;
		
		switch( $this->externals['formType'] ):
			case 'mins':
				$type = 'minutes';
				break;
			case 'agen':
				$type = 'agenda';
				break;
#			case 'mnot':
#				$type = 'meeting-notes';
			break;
		endswitch;
		
		$title = 'board-document_'.$type.$specialTitle.'_'.date( 'Y-m-d', $this->externals['finalDate'] ).'.pdf';


		move_uploaded_file ( $filename , BOARDDOCMAN_CHUHPL_FILE_PATH . '/' . $title ) or die( 'Error: couldn\'t move file' );
		#$handle = fopen($filename, 'r');
		#$contents = fread( $handle, filesize( $filename ) );
		#fclose($handle);
		
		$wpdb->insert( 
						"{$wpdb->prefix}boardDocumentManager", 
						array( 
							'bc_type' => $this->externals['formType'], 
							'bc_date' => $this->externals['finalDate'], 
							'bc_fileName'	=> $title,
							'bc_special' => $special, 
							'bc_staff' => $staff )
		);
        $wpdb->print_error();
		return TRUE;
		
	}
	
	protected function getExternals()
	# Pull in POST and GET values
	{
		$final = array();

		# setup GET variables
		$getArr = array(	'action'					=> FILTER_UNSAFE_RAW );

		# pull in and apply to final
		if( $getTemp = filter_input_array( INPUT_GET, $getArr ) )
			$final = array_merge( $final, $getTemp );

		# setup POST variables
		$postArr = array(	'action'					=> FILTER_UNSAFE_RAW,
							'formType'					=> FILTER_UNSAFE_RAW,
							'formYear_mins'				=> FILTER_UNSAFE_RAW, 
							'formMonth_mins'			=> FILTER_UNSAFE_RAW, 
							'formDay_mins'				=> FILTER_UNSAFE_RAW, 
							'formSpecial_mins' 			=> FILTER_UNSAFE_RAW, 
														
							'formYear_agen'				=> FILTER_UNSAFE_RAW, 
							'formMonth_agen'			=> FILTER_UNSAFE_RAW, 
							'formDay_agen'				=> FILTER_UNSAFE_RAW, 
							'formSpecial_agen' 			=> FILTER_UNSAFE_RAW, 
							
#							'formYear_mnot'				=> FILTER_UNSAFE_RAW, 
#							'formMonth_mnot'			=> FILTER_UNSAFE_RAW, 
							
							'formCode'	 				=> FILTER_UNSAFE_RAW, 
							'formSource'				=> FILTER_UNSAFE_RAW, 
							);
		$final = array();
	

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

	function check_add()
	{
		$temp = array();
		$final = TRUE;
		
		# type
		if( empty($this->externals['formType'] ) || !array_key_exists($this->externals['formType'], $this->typesArr))
		{
			$this->bg['formType'] = 'error';
			$temp[] = 'You must choose a type.';
		}
				
		# file
		if( ( $fileError = $this->checkFile() ) !== FALSE)
		{
			$this->bg['formContent'] = 'error';
			$temp[] = $fileError;
		}
		
		# dates
		switch( $this->externals['formType'] )
		{
			# minutes
			case 'mins':
				if( ( $error = $this->checkErrors('mins') ) !== NULL)
				{
					$this->bg['formDate'] = 'error';
					$temp[] = $error;
				}
				break;
			case 'agen':
				if( ( $error = $this->checkErrors('agen') ) !== NULL)
				{
					$this->bg['formDate'] = 'error';
					$temp[] = $error;
				}
				break;
				
			default:
				$temp[] =  __( 'There is an error! You have selected a Type that isn\'t being checked. Please contact the Webmaster!', 'board-document-manager-from-chuhpl' );
				break;
		}
		
		# duplicate
		if( TRUE == $this->checkIsDupe( $this->externals['formType'] ) )
		{
			$this->bg['formDate'] = 'error';
			$temp[] = __( 'There is already a document of that type on that date.', 'board-document-manager-from-chuhpl' );
		}
		
		# finalize				
		if( count( $temp ) !== 0)
		{
			$final = FALSE;
			$this->error_msg = implode('<br /><br />', $temp);
		}
		
		return $final;		
	}

	function checkErrors( $type )
	{
		$errors = array();
		$final = NULL;
		
		# check year
		if( empty( $this->externals['formYear_'.$type] ) )
			$errors[] =  __( 'You must select a year.', 'board-document-manager-from-chuhpl' );
		
		if( empty( $this->externals['formMonth_'.$type] ) )
			$errors[] =  __( 'You must select a month.', 'board-document-manager-from-chuhpl' );
		
#		if( empty( $this->externals['formDay_'.$type]) && $this->externals['formType'] !== 'mnot' )
#			$errors[] =  __( 'You must enter a day.', 'board-document-manager-from-chuhpl' );
			
		if( count( $errors ) !== 0 )
		{
			return implode('<br /><br />', $errors);

		}
		
		# bad date
		$year	= $this->externals['formYear_'.$type];
		$month	= $this->externals['formMonth_'.$type];
		$day	= $this->externals['formDay_'.$type];
		#$day	= ($this->externals['formType'] == 'mnot') ? 1 : $this->externals['formDay_'.$type];

		if(FALSE == ($this->externals['finalDate'] = @mktime(0,0,0, $month, $day, $year)))
		{
			$final = __( 'Your date is invalid. Check your values and try again.', 'board-document-manager-from-chuhpl' );
		}
		else
		# day out of range
		{
			$curMonth = mktime(0,0,0, $month, 1, $year);
			
			$daysInMonth = date_i18n('t', $curMonth);
			
			if($day < 1 || $day > $daysInMonth)
			{
				/* translators: This will end up looking like "There are 31 days in October, 2016. You entered 5656, which is invalid." */
				$final = sprintf( __( 'There are <em><strong>%1$s</strong></em> days in %2$s. You entered <em><strong>%3$s</strong></em>, which is invalid.' ), $daysInMonth, date_i18n('F, Y', $curMonth), $day ) ;
			}
		}
		
		return $final;
	}
	
	function checkFile()
	{
		# is there a file uploaded
		if(empty($_FILES['file']['name']))
			return __( 'You must upload a file to continue.', 'board-document-manager-from-chuhpl' );
		
		# is the file a pdf
		if(empty($_FILES['file']['type']) || $_FILES['file']['type'] !== 'application/pdf')
			return __( 'Your file isn\'t a PDF. Please upload a valid PDF to continue', 'board-document-manager-from-chuhpl' );
		
		return FALSE;
		
	}
	
	function checkFormCode()
	{
		$final = TRUE;
		
		$formCode = $this->externals['formCode'];
		$sessionCode = NULL;
		$myCode = get_user_meta( get_current_user_id(),  'bdmchuhpl_formCode', true );
		
		if( !empty( $myCode ) ) $sessionCode = $myCode;

		if( empty( $formCode ) || (int)$formCode !== (int)$sessionCode)
		{
			
			foreach($_POST as $key => $val)
			{
				$this->$key = NULL;
			}

			$this->externals['formCode'] = intval(rand(10000000,99999999));
			update_user_meta( get_current_user_id(), 'bdmchuhpl_formCode', $this->externals['formCode'] );

			$final = FALSE;
		}
		$myCode = get_user_meta( get_current_user_id(),  'bdmchuhpl_formCode', true );
		
		return $final;
	}
	
	function checkIsDupe( $type )
	{
		global $wpdb;
		
		if( empty( $this->externals['finalDate'] ) ):
			return FALSE;
		endif;
		
#		if( $type == 'mnot' ):
#			$special = false;
#		else:
#			$special = ( $this->externals['formSpecial_'.$type] == TRUE ) ? true : false;
#		endif;
		$special = ( $this->externals['formSpecial_'.$type] == TRUE ) ? true : false;
		
		$query = "SELECT COUNT( bc_id ) FROM {$wpdb->prefix}boardDocumentManager WHERE
					bc_date = '" . $this->externals['finalDate'] . "' && 
					bc_special = '" . $special . "' && 
					bc_type = '" . $this->externals['formType'] . "'";
		
		$num_rows = $wpdb->get_var( $query );
		
		if( $num_rows == 0 ):
			$final = FALSE;
		else:
			$final = TRUE;
		endif;
		
		return $final;
	}
	
	function show_form()
	{
		include( BOARDDOCMAN_CHUHPL_PATH . 'templates/form_add.php' );
		return TRUE;
	}

}
?>

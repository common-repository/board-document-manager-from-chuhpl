<?PHP if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
<div id="icon-plugins" class="icon32"></div>
<h2><?php _e( 'Board Document Management', 'board-document-manager-from-chuhpl' ); ?></h2>
</div>
<h2><?PHP _e( 'Add a document - Success', 'board-document-manager-from-chuhpl' ); ?></h2>
  <table class="bdmNiceTable">
  <tr>
    <td colspan="2"><?php _e( 'Add a document - Success', 'board-document-manager-from-chuhpl' ); ?></td>
  </tr>
  <tr>
    <td colspan="2"><p><?php _e( 'Your document has been added.', 'board-document-manager-from-chuhpl' ); ?></p>
      <p><?php 

		$special	= ( $this->externals['formSpecial_'.$this->externals['formType']] == TRUE ) ? " (Special)" : NULL;
		$date = date_i18n( 'l, F jS, Y', $this->externals['finalDate'] );
		/* translators: This reads like, "You added a new document: Minutes (Special) for January 12, 2016" */
		echo ( sprintf( __( 'You added a new document: %1$s%2$s for %3$s', 'board-document-manager-from-chuhpl' ), $this->typesArr[$this->externals['formType']], $special, $date ) ); ?></p>
</td>
  </tr>
</table>
<p><a href="?page=bdm_main"><?PHP _e( 'Add another document', 'board-document-manager-from-chuhpl' ); ?> >></a></p>
<p><a href="?page=bdm_mainShow"><?PHP _e( 'Return to document list', 'board-document-manager-from-chuhpl' ); ?> >></a></p>
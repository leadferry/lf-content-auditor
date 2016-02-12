<?php 

/**
 * Manages the reports page
 * 
 */
class Content_Auditor_Reports {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_reports_page' ) );
	}

	function add_reports_page() {
		add_submenu_page( 'content_auditor_settings', 'Reports', 'Reports', 'manage_options', 'content_auditor_reports', array( $this, 'content_auditor_reports_callback' ), '', 59 );
	}

	function content_auditor_reports_callback() { ?>
		<?php settings_errors(); ?>
		<div class="wrap">
			<h2>Content Audit Report Generator by LeadFerry</h2>
			<h2 class="nav-tab-wrapper">
		     	<a class="nav-tab" href="<?php echo admin_url() ?>admin.php?page=content_auditor_settings">Create New Report</a>
		      	<a class="nav-tab nav-tab-active" href="<?php echo admin_url() ?>admin.php?page=content_auditor_reports">Previously Generated Reports</a>
		    </h2>

		    <h2>Available Reports</h2>
		    <p>You can find the previously generated reports here.</p>
		<?php

		require( 'reports-list-table.php' );
		$this->reports_table = new Reports_List_Table();
		$this->reports_table->prepare_items();
		$this->reports_table->display();

	}
}
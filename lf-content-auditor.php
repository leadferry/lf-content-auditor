<?php 
/**
 * Plugin Name: Content Auditor by LeadFerry
 * Plugin URI: https://leadferry.com/tools/content-audit-plugin-wordpress/
 * Description: Generates report based on various metrics for your WordPress content.
 * Version: 0.1.0
 * Author: LeadFerry
 * Author URI: https://leadferry.com/
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */
/*
    Content Auditor by LeadFerry - WordPress plugin to generate WP content audit reports
    Copyright (C) 2016  LeadFerry (https://leadferry.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once( 'vendor/autoload.php' );



class Content_Auditor {

	function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activate') );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate') );

		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts') );
		add_action( 'wp_ajax_report_generator', array( $this, 'generate_report' ) );

		require_once( 'classes/content-auditor-settings.php' );
		require_once( 'classes/content-auditor-reports.php' );
		require_once( 'classes/report-generator.php' );

		$this->settings = new Content_Auditor_Settings();
		$this->reports = new Content_Auditor_Reports();
		$this->report_generator = new Report_Generator();
	}

	static function activate() {
		if( false === get_option( 'content_auditor_metrics' ) ) {
			$defaults = array(
				'url' => 'URL',
				'page_title' =>'Page Title',
				'author' => 'Author',
				'publish_date' => 'Publish Date',
				'last_updated' => 'Last Update',
				'comments' => 'Comments',
				'images' => 'Images',
				'word_count' => 'Word Count',
				'readability_score' => 'Flesch-Kincaid Readability Score',
			);
			update_option( 'content_auditor_metrics', $defaults );
		}

		if( false === get_option( 'content_auditor_social_metrics' ) ) {
			$defaults = array(
				'facebook' => 'Facebook',
				'linkedin' => 'Linkedin',
				'pinterest' => 'Pinterest',
			);
			update_option( 'content_auditor_social_metrics', $defaults );
		}

		if( false === get_option( 'content_auditor_post_type' ) ) {
			$defaults = array(
				'post' => 'true',
				'page' =>'true',
			);
			update_option( 'content_auditor_post_type', $defaults );
		}

		if( false === get_option( 'content_audit_criteria' ) ) {
			$defaults = array(
				'criteria' => 'full-site',
				'older_than_x' => '7',
				'created_in_x' => '7',
			);
			update_option( 'content_audit_criteria', $defaults );
		}
	}

	static function deactivate() {
		delete_option( 'content_auditor_metrics' );
		delete_option( 'content_auditor_post_type' );	
	}

	/**
	 * Generates Audit report
	 * 
	 */
	function generate_report() {

		$this->report_generator->generate_report();
	}

	function test_generate_report() {
		if( isset($_POST['generate_report'])){
			$this->report_generator->generate_report();
		}
	}

	function add_scripts() {
		wp_enqueue_script( 'content-auditor-js', plugin_dir_url(__FILE__)  . '/js/content-auditor.js', '', '', true );
		wp_localize_script( 'content-auditor-js', 'data', array( 'url'  => admin_url( 'admin-ajax.php' ) ) );
	}
}
$GLOBALS['lf_content_auditor'] = new Content_Auditor();

<?php 

/**
 * Manages the settings page
 * 
 */
class Content_Auditor_Settings {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );
	}

	function add_settings_page() {
		add_menu_page( 'Content Auditor', 'Content Auditor', 'manage_options', 'content_auditor_settings', array( $this, 'content_auditor_settings_callback' ), '', 59 );
	}

	function content_auditor_settings_callback() { ?>
		<?php settings_errors(); ?>
		<div class="wrap">
			<h2>Content Audit Report Generator by LeadFerry</h2>

			<h2 class="nav-tab-wrapper">
		     	<a class="nav-tab nav-tab-active" href="<?php echo admin_url() ?>admin.php?page=content_auditor_settings">Create New Report</a>
		      	<a class="nav-tab" href="<?php echo admin_url() ?>admin.php?page=content_auditor_reports">Previously Generated Reports</a>
		    </h2>

			<form action="options.php" method="post">
				<?php settings_fields('content_auditor_options'); ?>
				<?php do_settings_sections('content_auditor_settings'); ?>

				<input class="button button-primary" name="submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
			</form>
		</div> 
		<h3>Generate report</h3>
		<p> <?php echo __( 'Click the button below to generate your audit report in CSV(Comma Separated Values) format.'); ?> </p>
		<form id="report_generator" action="" method="post">
			<input class="button button-secondary" type="submit" name="submit" value="<?php esc_attr_e('Generate Report'); ?>">
			<input type="hidden" name="generate_report" value="1" />
		</form>
		<div>
			<p>
				<b>Want more insights about your content marketing performance? <a href='https://leadferry.com/?utm_source=<?php echo str_replace( array( 'https://', 'http://' ), '', site_url() ); ?>&utm_medium=plugin&utm_content=content-auditor-plugin&utm_campaign=tools'>Check out LeadFerry.</a></b><br/>
				LeadFerry is a marketing software designed to help you automate and analyze your content marketing efforts.
			</p>
		</div>
		<?php
	}

	function init_settings() {

		register_setting( 'content_auditor_options', 'content_auditor_metrics', array( $this, 'content_auditor_metrics' ) );
		register_setting( 'content_auditor_options', 'content_auditor_post_type' );
		add_settings_section( 'content_auditor_settings_section', __( 'Report Generator' ), array( $this, 'content_auditor_settings_section' ), 'content_auditor_settings' );
		add_settings_field( 'select-metrics', __( 'Select Metrics' ), array( $this, 'field_select_metrics' ), 'content_auditor_settings', 'content_auditor_settings_section' );
		add_settings_field( 'select-post-type', __( 'Select Post Type' ), array( $this, 'field_select_post_type' ), 'content_auditor_settings', 'content_auditor_settings_section' );
	}

	function content_auditor_settings_section() {

		echo '<p>' . __( 'Create your content inventory. The content auditor plugin will automatically add useful metrics to help you evaluate the content.' ) . '</p>';
	}

	function content_auditor_metrics( $input ) {

		if( null == $input ) {
			add_settings_error( 'select-metrics', 'no_select', __( 'Please select few metrics' ), 'error' );
			return get_option( 'content_auditor_metrics' );
		}
		else {
			return $input;
		}
	}

	function field_select_metrics() {

		$options = get_option( 'content_auditor_metrics' );
		
		$options['url'] = isset( $options['url'] ) ? $options['url'] : false;
		$options['page_title'] = isset( $options['page_title'] ) ? $options['page_title'] : false;
		$options['meta_title'] = isset( $options['meta_title'] ) ? $options['meta_title'] : false;
		$options['meta_description'] = isset( $options['meta_description'] ) ? $options['meta_description'] : false;
		$options['author'] = isset( $options['author'] ) ? $options['author'] : false;
		$options['publish_date'] = isset( $options['publish_date'] ) ? $options['publish_date'] : false;
		$options['last_updated'] = isset( $options['last_updated'] ) ? $options['last_updated'] : false;
		$options['comments'] = isset( $options['comments'] ) ? $options['comments'] : false;
		$options['images'] = isset( $options['images'] ) ? $options['images'] : false;
		$options['word_count'] = isset( $options['word_count'] ) ? $options['word_count'] : false;
		$options['readability_score'] = isset( $options['readability_score'] ) ? $options['readability_score'] : false;

		echo '<p><input type="checkbox" name="content_auditor_metrics[url]" value="URL"' . checked( $options['url'], "URL", false ) . ' />' . __( " URL" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[page_title]" value="Page Title"' . checked( $options['page_title'], "Page Title", false ) . ' />' . __( " Page Title" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[meta_title]" value="Meta Title"' . checked( $options['meta_title'], "Meta Title", false ) . ' onchange= "if(checked){ alert( &quot; Report generatation can take a while longer &quot; );}"/>' . __( " Meta Title" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[meta_description]" value="Meta Description"' . checked( $options['meta_description'], "Meta Description", false ) . ' onchange= "if(checked) {alert( &quot; Report generatation can take a while longer &quot; );}" />' . __( " Meta Description" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[author]" value="Author"' . checked( $options['author'], "Author", false ) . ' />' . __( " Author" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[publish_date]" value="Publish Date"' . checked( $options['publish_date'], "Publish Date", false ) . ' />' . __( " Publish Date" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[last_updated]" value="Last Update"' . checked( $options['last_updated'], "Last Update", false ) . ' />' . __( " Last Updated" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[comments]" value="Comments"' . checked( $options['comments'], "Comments", false ) . ' />' . __( " Total Comments" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[images]" value="Images"' . checked( $options['images'], "Images", false ) . ' />' . __( " Number of Images" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[word_count]" value="Word Count"' . checked( $options['word_count'], "Word Count", false ) . ' />' . __( " Number of Words" ); 
		echo '<p><input type="checkbox" name="content_auditor_metrics[readability_score]" value="Flesch-Kincaid Readability Score"' . checked( $options['readability_score'], "Flesch-Kincaid Readability Score", false ) . ' />' . __( " Flesch–Kincaid Readability Score" ) . ' <a target ="_blank" href="https://en.wikipedia.org/wiki/Flesch%E2%80%93Kincaid_readability_tests">(Know more about this score)</a>'; 
	}

	function field_select_post_type() {

		$options = get_option( 'content_auditor_post_type' );
		
		$options['post'] = isset( $options['post'] ) ? $options['post'] : false;
		$options['page'] = isset( $options['page'] ) ? $options['page'] : false;

		echo '<p><input type="checkbox" name="content_auditor_post_type[post]" value="true"' . checked( $options['post'], "true", false ) . ' />' . __( " Post" ); 
		echo '<p><input type="checkbox" name="content_auditor_post_type[page]" value="true"' . checked( $options['page'], "true", false ) . ' />' . __( " Page" ); 
	}
}
<?php 

class Report_Generator {

	function __construct() {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( 'metrics-calculator.php' );
		$this->metric_calculator = new Metrics_Calculator();
		add_action( 'init', array( $this, 'initiate_filesystem' ) );
	}

	function generate_report() {

		$this->initiate_filesystem();
		global $wp_filesystem;
		
		$upload_dir = wp_upload_dir();
		$this->reports_dir = $upload_dir['basedir'].'/reports';
		if ( ! file_exists( $this->reports_dir ) ) {
		    $wp_filesystem->mkdir( $this->reports_dir );
		}
		$rows = $this->fetch_data();


		$csv = array();
		foreach ( $rows as $row ) {
			$csv[] = $this->convert_to_csv( $row );
		}
		$csv = implode( "\n", $csv );

		$filename = get_bloginfo( "name" ) .'_audit_report_' . time() . '.csv';

		$file =  trailingslashit( $this->reports_dir ) . $filename;

		if (  $wp_filesystem->put_contents( $file, $csv, FS_CHMOD_FILE) ) {
		    $this->send_email( $file );
		}
	}

	function send_email( $file ) {

		$current_user = wp_get_current_user();
		$to = $current_user->user_email;
		$subject = 'Your content audit report is ready';
		$message = '<p>Hello,</p><p>You recently requested to generate a content audit report. The report generation is now complete.</p><p>You can download the report by going to the “Previously Generated Reports” tab inside content Auditor.</p><p>Have feedback to share?</p><p>Write to support@leadferry.com with your comments.</p>';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		
		add_filter( 'wp_mail_from', function() {
			$current_user = wp_get_current_user();
			return $current_user->user_email; 
		});
		add_filter( 'wp_mail_from_name', function() { return 'Content Auditor'; });
		wp_mail( $to, $subject, $message, $headers );
	}

	function initiate_filesystem() {
		$url = wp_nonce_url( admin_url( 'admin.php?page=content_auditor_settings' ),'content_auditor_options' );
		
		if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
			return;
		}
		if ( ! WP_Filesystem($creds) ) {
			request_filesystem_credentials( $url, '', true, false, null );
			return;
		}
	}

	function prepare_query_args() {
		$args = array();
		$post_type = get_option( 'content_auditor_post_type' );

		if( isset( $post_type['post'] ) && isset( $post_type['page'] ) ) {
			$args['post_type'] = 'any';
		}
		elseif( !isset( $post_type['post'] ) && isset( $post_type['page'] ) ) {
			$args['post_type'] = 'page';
		}
		else {
			$args['post_type'] = 'post';
		}

		$content_criteria = get_option( 'content_audit_criteria' );
		if( $content_criteria['criteria'] == 'older_than_x' ) {
			$days = date( 'd-M-Y', strtotime( '-' . $content_criteria['older_than_x'] . ' days') );
			$args['date_query'] = array( 
				array( 'before' => $days )
			);
		}

		if( $content_criteria['criteria'] == 'created_in_x' ) {
			$days = date( 'd-M-Y', strtotime( '-' . $content_criteria['created_in_x'] . ' days') );
			$args['date_query'] = array( 
				array( 'after' => $days )
			);
		}

		$args['posts_per_page'] = -1;
		return $args;
	}

	function fetch_data() {
		
		global $more;
		$real_more = $more;
		
		$metrics = array_merge( get_option( 'content_auditor_metrics' ), get_option( 'content_auditor_social_metrics' ) );
		$results = array();
		$results[] = $metrics;

		$args = $this->prepare_query_args();
		$post_query = new WP_Query( $args );
		if( $post_query->have_posts() ) {
		 	while( $post_query->have_posts() ) {
		 		$post_query->the_post();
		 		$more = 1;
		 		$row = array();
		 		foreach ( $metrics as $metric => $value ) {
		 			$row[] = $this->metric_calculator->$metric();
		 		}
		 		$results[] = $row;
			}
		}
		$more = $real_more;
		return $results;
	}

	function convert_to_csv( $values ) {

		$line = '';
		$values = array_map( function ( $value ) {
        	return '"' . str_replace( '"', '""', $value ) . '"';
   		}, $values );

		$line .= implode( ',', $values );
    	return $line;
	}

	function report_generation_error_notice() {
		$class = "error";
		$message = __( "There was an error while generating your report" );
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
	}

	function report_generation_success_notice() {
		$class = "updated notice";
		$message = __( "The report has been generated successfully" );
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
	}
}
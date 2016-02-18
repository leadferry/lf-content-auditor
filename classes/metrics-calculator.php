<?php 
use DaveChild\TextStatistics as TS;

class Metrics_Calculator {

	function __construct() {
		require_once( dirname( dirname( __FILE__ ) ) . '/simple_html_dom.php' );
	}

	function url() {
		return get_the_permalink();
	}

	function page_title() {
		return get_the_title();
	}

	function meta_title() {

		$html = str_get_html( $this->get_output_page() );
		$title = $html->find( 'title' );
		$output =  ( null != $title ) ? $title[0]->plaintext : "Not Available";
				
		$html->clear();
		unset( $html );

		return $output;
	}

	function meta_description() {

		$html = str_get_html( $this->get_output_page() );
		$meta = $html->find( 'meta[name=description]' );
		$output = ( null != $meta ) ? $meta[0]->content : "Not Available";

		$html->clear();
		unset( $html );

		return $output;
	}

	function author() {
		return get_the_author();
	}

	function publish_date() {
		return get_the_date();
	}

	function last_updated() {
		return get_the_modified_date();
	}

	function comments() {
		return get_comments_number();
	}

	function images() {

		$count = substr_count( get_the_content(), '<img' );
		$count = has_post_thumbnail() ? $count + 1 : $count;
		return $count;
	}

	function word_count() {
		$content = strip_shortcodes( strip_tags( get_the_content() ) );
		return str_word_count( $content );

	}

	function readability_score() {
		
		$textStatistics = new TS\TextStatistics;
		$content = strip_shortcodes( strip_tags( get_the_content() ) );

		if( $this->word_count() == 0 ) {
			return "NA";
		}
		return $textStatistics->fleschKincaidReadingEase( $content );
	}

	function get_output_page() {
		ob_start();
		$str =  wp_remote_get( get_the_permalink() ); 
		ob_end_clean(); 
		return $str['body'];
	}

	function facebook() {
		$request = wp_remote_request( 'http://graph.facebook.com/?id=' . get_the_permalink() );
		if ( ! is_wp_error( $request ) ) {
		  	$body = json_decode( $request['body'] );
		  	return isset( $body->shares )? $body->shares : 0;
		}
		else {
			return "Not Available";
		}
	}

	function linkedin() {
		$request = wp_remote_request( 'https://www.linkedin.com/countserv/count/share?url=' . get_the_permalink() . '&format=json' );
		if ( ! is_wp_error( $request ) ) {
		  	$body = json_decode( $request['body'] );
		  	return $body->count;
		}
		else {
			return "Not Available";
		}
	}

	function pinterest() {

		$request = wp_remote_request( 'http://api.pinterest.com/v1/urls/count.json?callback=count&url=' . get_the_permalink() );
		if ( ! is_wp_error( $request ) ) {
			$jsonp = $request['body'];
			$jsonp = substr( $jsonp, strpos( $jsonp, '(') );
		  	$body = json_decode( trim( $jsonp,'();' ) );
		  	return $body->count;
		}
		else {
			return "Not Available";
		}
	}
}
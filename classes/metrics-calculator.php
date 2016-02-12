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
		$output =  ( null != $title ) ? $title[0]->plaintext : "Not Avaialble";
				
		$html->clear();
		unset( $html );

		return $output;
	}

	function meta_description() {

		$html = str_get_html( $this->get_output_page() );
		$meta = $html->find( 'meta[name=description]' );
		$output = ( null != $meta ) ? $meta[0]->content : "Not Avaialble";

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
}
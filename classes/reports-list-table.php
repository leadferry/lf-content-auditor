<?php
if( !class_exists( 'Content_Auditor_List_Table' ) ) {
    require( dirname( dirname( __FILE__ ) )  . '/vendor/wordpress/wp-list-table/class-wp-list-table.php' );
}

class Reports_List_Table extends Content_Auditor_List_Table {
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        // Pagination
        $perPage = 10;
        $currentPage = $this->get_pagenum();
       
        $this->set_pagination_args( array(
            'total_items' => count( $data ),
            'per_page'    => $perPage
        ) );

        if ( $data ) {
            $data = array_slice( $data, ( ( $currentPage-1 ) * $perPage ), $perPage);
        }

        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items = $data;
    }

    public function get_columns() {
        $columns = array(
            'name' => 'Name',
            'date' => 'Created Date'
        );

        return $columns;
    }

    public function get_hidden_columns() {
        return array();
    }

    public function get_sortable_columns() {
    	return array();
    }

    private function table_data() {
        $upload_dir = wp_upload_dir();
        $reports_dir = $upload_dir['basedir'].'/reports';
        $reports_dir_url = $upload_dir['baseurl'].'/reports';

        global $wp_filesystem;
        $filelist = $wp_filesystem->dirlist( $reports_dir );
        
        $table_data = array();

        if ( $filelist ){
            foreach( $filelist as $file ) {
                if( strpos( $file['name'], 'audit_report' ) !== false ) {                    
                    $table_data[] = array( 
                        'name' => '<a href="' . $reports_dir_url . '/' . $file['name'] . '">' . $file['name'] . '</a><br/>',
                        'date' => date( 'd M, Y H:i:s', $file['lastmodunix'] ),
                        'timestamp' => $file['lastmodunix'],
                    );
                }
            }
            usort( $table_data, function( $item1, $item2 ){
                $ts1 = $item1['timestamp'];
                $ts2 = $item2['timestamp'];
                return $ts2 - $ts1;
            });
        }
        return $table_data;
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'name':
            case 'date':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }

    function pagination( $which ) {
        if ( empty( $this->_pagination_args ) ) {
            return;
        }

        
        extract( $this->_pagination_args, EXTR_SKIP );

        $output = '<span class="displaying-num">' . sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) ) . '</span>';

        $current = $this->get_pagenum();

        $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

        $current_url = remove_query_arg( array( 'hotkeys_highlight_last', 'hotkeys_highlight_first' ), $current_url );


        $page_links = array();

        $disable_first = $disable_last = '';
        if ( $current == 1 )
            $disable_first = ' disabled';
        if ( $current == $total_pages )
            $disable_last = ' disabled';

        $page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
            'first-page' . $disable_first,
            esc_attr__( 'Go to the first page' ),
            esc_url( remove_query_arg( 'paged', $current_url ) ),
            '&laquo;'
        );

        $page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
            'prev-page' . $disable_first,
            esc_attr__( 'Go to the previous page' ),
            esc_url( add_query_arg( 'paged', max( 1, $current-1 ), $current_url ) ),
            '&lsaquo;'
        );

        if ( 'bottom' == $which )
            $html_current_page = $current;
        else
            $html_current_page = sprintf( "<input class='current-page' title='%s' type='text' name='paged' value='%s' size='%d' />",
                esc_attr__( 'Current page' ),
                $current,
                strlen( $total_pages )
            );

        $html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
        $page_links[] = '<span class="paging-input">' . sprintf( _x( '%1$s of %2$s', 'paging' ), $html_current_page, $html_total_pages ) . '</span>';

        $page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
            'next-page' . $disable_last,
            esc_attr__( 'Go to the next page' ),
            esc_url( add_query_arg( 'paged', min( $total_pages, $current+1 ), $current_url ) ),
            '&rsaquo;'
        );

        $page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
            'last-page' . $disable_last,
            esc_attr__( 'Go to the last page' ),
            esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
            '&raquo;'
        );

        $pagination_links_class = 'pagination-links';
        if ( ! empty( $infinite_scroll ) )
            $pagination_links_class = ' hide-if-js';
        $output .= "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';

        if ( $total_pages )
            $page_class = $total_pages < 2 ? ' one-page' : '';
        else
            $page_class = ' no-pages';

        $this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

        echo $this->_pagination;
    }
}
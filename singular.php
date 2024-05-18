<?php

get_header();

if ( is_single() ) {
	do_action( 'jays_cake_single_content_area' );
} elseif ( is_page() ) {
	do_action( 'jays_cake_page_content_area' );
}

get_footer();

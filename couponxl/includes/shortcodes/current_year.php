<?php 
function currentYear( $atts ){
    return date('Y');
}
add_shortcode( 'year', 'currentYear' );
?>
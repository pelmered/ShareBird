<?php

if( !function_exists('ShareBird_Shortcode'))
{
    function ShareBird_Shortcode( $atts )
    {
        $options = shortcode_atts( array(
            'post_id' => get_the_ID(),
            'template' => 'sharebird-buttons.php'
        ), $atts, 'sharebird' );
        
        ob_start();
        
        ShareBird()->output_buttons($options['template'], array('post_id' => $options['post_id']));

        return ob_get_clean();
    }
    add_shortcode( 'sharebird', 'ShareBird_Shortcode' );
}

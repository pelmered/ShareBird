<?php
if( !function_exists('ShareBird_Shortcode'))
{
    function ShareBird_Shortcode( $atts )
    {
        $a = shortcode_atts( array(
            'enable' => array(),
            'disable' => array()
        ), $atts );

        ob_start();

        ShareBird()->output_buttons('template', array('post_id' => 2));

        return ob_get_clean();
    }
    add_shortcode( 'sharebird', 'ShareBird_Shortcode' );
}
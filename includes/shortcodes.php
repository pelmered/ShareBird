<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
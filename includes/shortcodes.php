<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if( !function_exists('WP_Simple_Share_Buttons_Shortcode'))
{
    function WP_Simple_Share_Buttons_Shortcode( $atts )
    {
        $a = shortcode_atts( array(
            'enable' => array(),
            'disable' => array()
        ), $atts );
        
        
	ob_start();
        
        WP_Simple_Share_Buttons()->get_template('default');
        
	return ob_get_clean();
    }
    add_shortcode( 'wpssb', 'WP_Simple_Share_Buttons_Shortcode' );
}
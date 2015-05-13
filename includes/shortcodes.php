<?php
if( !function_exists('ShareBird_Shortcode'))
{
    function ShareBird_Shortcode( $atts, $content = '' )
    {
        extract(shortcode_atts( array(
            'id' => '',
            'template' => 'sharebird-buttons.php'
        ), $atts ));

        $id = (int)$id;

        if($id === 0)
            $id = get_the_ID();

        ob_start();

        ShareBird()->output_buttons($template, array('post_id' => $id));

        return ob_get_clean();
    }
    add_shortcode( 'sharebird', 'ShareBird_Shortcode' );
}
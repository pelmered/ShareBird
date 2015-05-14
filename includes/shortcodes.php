<?php
if( !function_exists('ShareBird_Shortcode'))
{
    function ShareBird_Shortcode( $atts, $content = '' )
    {
        $data = (shortcode_atts( array(
            'id' => '',
            'template' => 'sharebird-buttons.php'
        ), $atts ));

        $id = (int) $data['id'];

        if($id === 0)
            $id = get_the_ID();

        $data['post_id'] = $id;
        unset( $data['id'] );

        ob_start();

        ShareBird()->output_buttons( $data );

        return ob_get_clean();
    }
    add_shortcode( 'sharebird', 'ShareBird_Shortcode' );
}
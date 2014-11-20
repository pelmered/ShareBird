<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//TODO
//$options = get_option();

$options = WP_Simple_Share_Buttons()->get_options();

?>
<div class="wpsimplesharebuttons simplesharebuttons" id="">
    <ul>
        <?php if( $options['facebook']['active'] == 1 ) : ?>
        <li><a class="sharebutton facebook" data-basecount="<?php echo WP_Simple_Share_Buttons()->get_basecount('facebook'); ?>" data-sharetype="facebook" data-text="<?php echo get_the_title(); ?>" title="Share this on Facebook" href="#"><i class="icon-facebook"></i> <span class="count"></span></a></li>
        <?php endif; if( $options['twitter']['active'] == 1 ) : ?>
        <li><a class="sharebutton twitter" data-basecount="<?php echo WP_Simple_Share_Buttons()->get_basecount('twitter'); ?>" data-sharetype="twitter" data-text="<?php echo get_the_title(); ?>" data-via="AndreasNorman" data-related="AndreasNorman" title="Share this on Twitter" href="#"><i class="icon-twitter"></i> <span class="count"></span></a></li>
        <?php endif; if( $options['linkedin']['active'] == 1 ) : ?>
        <li><a class="sharebutton linkedin" data-basecount="<?php echo WP_Simple_Share_Buttons()->get_basecount('linkedin'); ?>" data-sharetype="linkedin" data-text="<?php echo get_the_title(); ?>" data-referer="AndreasNorman" data-related="AndreasNorman" title="Share this on LinkedIn" href="#"><i class="icon-linkedin"></i> <span class="count"></span></a></li>
        <?php endif; if( $options['googleplus']['active'] == 1 ) : ?>
        <li><a class="sharebutton googleplus" data-basecount="<?php echo WP_Simple_Share_Buttons()->get_basecount('googleplus'); ?>" data-sharetype="googleplus" data-text="<?php echo get_the_title(); ?>" title="Share this on Google Plus" href="#"><i class="icon-googleplus"></i> <span class="count"></span></a></li>
        <?php endif; ?>
    </ul>
</div>




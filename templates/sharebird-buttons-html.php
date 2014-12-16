<?php
/**
 * This is the default template for displaying share buttons.
 *
 * Override this template by copying it to your them or use this as a base 
 * for createing several templates that can be loaded with:
 * ShareBird()->output_buttons('template-filename.php');
 *
 * @author 	ShareBird
 * @package 	ShareBird/Templates
 * @version     1.0.0
 */

?>
<?php if(sizeof($button_options) > 0): ?>
    <div class="sharebird simplesharebuttons">
        <ul>
            <?php if(isset($button_options['facebook'])): ?>
                <li>
                    <a class="sharebutton facebook" data-url="<?php echo get_permalink($post->ID); ?>" data-basecount="0" data-sharetype="facebook" data-text="<?php echo ShareBird()->get_share_text('facebook', $post->ID); ?>" title="<?php _e('Share this on Facebook', $plugin_slug); ?>" href="#">
                        <i class="icon-facebook"></i>
                        <span class="count"></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($button_options['twitter'])): ?>
                <li>
                    <a class="sharebutton twitter" data-url="<?php echo get_permalink($post->ID); ?>" data-basecount="0" data-sharetype="twitter" data-text="<?php echo ShareBird()->get_share_text('twitter', $post->ID); ?>" data-via="<?php echo ShareBird()->get_username('twitter', $post->ID); ?>" data-related="<?php echo ShareBird()->get_author('twitter', $post->ID); ?>" title="<?php _e('Share this on Twitter', $plugin_slug); ?>" href="#">
                        <i class="icon-twitter"></i>
                        <span class="count"></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($button_options['linkedin'])): ?>
                <li>
                    <a class="sharebutton linkedin" data-url="<?php echo get_permalink($post->ID); ?>" data-basecount="0" data-sharetype="linkedin" data-text="<?php echo ShareBird()->get_share_text('linkedin', $post->ID); ?>" data-referer="<?php echo ShareBird()->get_username('linkedin', $post->ID); ?>" data-related="<?php echo ShareBird()->get_author('linkedin', $post->ID); ?>" title="<?php _e('Share this on LinkedIn', $plugin_slug); ?>" href="#">
                        <i class="icon-linkedin"></i>
                        <span class="count"></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($button_options['googleplus'])): ?>
                <li>
                    <a class="sharebutton googleplus" data-url="<?php echo get_permalink($post->ID); ?>" data-basecount="0" data-sharetype="googleplus" data-text="<?php echo ShareBird()->get_share_text('googleplus', $post->ID); ?>" title="<?php _e('Share this on Google Plus', $plugin_slug); ?>" href="#">
                        <i class="icon-googleplus">
                        </i> <span class="count"></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>
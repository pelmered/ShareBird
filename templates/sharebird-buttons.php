<?php
$options = ShareBird()->get_options();
$counts = ShareBird()->get_counts($post->ID);
?>
<?php if(sizeof($options['buttons']) > 0): ?>
    <div class="sharebird simplesharebuttons">
        <ul>
            <?php if(isset($options['buttons']['facebook'])): ?>
                <li>
                    <a class="sharebutton facebook" data-url="<?php echo get_the_permalink($post->ID); ?>" data-basecount="0" data-sharetype="facebook" data-text="<?php echo ShareBird()->get_post_title('facebook', $post->ID); ?>" title="<?php _e('Share this on Facebook', $plugin_slug); ?>" href="#">
                        <i class="icon-facebook"></i>
                        <span class="count"></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($options['buttons']['twitter'])): ?>
                <li>
                    <a class="sharebutton twitter" data-url="<?php echo get_the_permalink($post->ID); ?>" data-basecount="0" data-sharetype="twitter" data-text="<?php echo ShareBird()->get_post_title('twitter', $post->ID); ?>" data-via="<?php echo ShareBird()->get_author('twitter', $post->ID); ?>" data-related="<?php echo ShareBird()->get_author('twitter', $post->ID); ?>" title="<?php _e('Share this on Twitter', $plugin_slug); ?>" href="#">
                        <i class="icon-twitter"></i>
                        <span class="count"></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($options['buttons']['linkedin'])): ?>
                <li>
                    <a class="sharebutton linkedin" data-url="<?php echo get_the_permalink($post->ID); ?>" data-basecount="0" data-sharetype="linkedin" data-text="<?php echo ShareBird()->get_post_title('linkedin', $post->ID); ?>" data-referer="<?php echo ShareBird()->get_author('linkedin', $post->ID); ?>" data-related="<?php echo ShareBird()->get_author('linkedin', $post->ID); ?>" title="<?php _e('Share this on LinkedIn', $plugin_slug); ?>" href="#">
                        <i class="icon-linkedin"></i>
                        <span class="count"></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($options['buttons']['googleplus'])): ?>
                <li>
                    <a class="sharebutton googleplus" data-url="<?php echo get_the_permalink($post->ID); ?>" data-basecount="0" data-sharetype="googleplus" data-text="<?php echo ShareBird()->get_post_title('googleplus', $post->ID); ?>" title="<?php _e('Share this on Google Plus', $plugin_slug); ?>" href="#">
                        <i class="icon-googleplus">
                        </i> <span class="count"></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>
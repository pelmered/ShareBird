<?php $options = WP_Simple_Share_Buttons()->get_options(); ?>
<?php if(sizeof($options['buttons']) > 0): ?>
    <div class="wpsimplesharebuttons simplesharebuttons">
        <ul>
            <?php if(isset($options['buttons']['facebook'])): ?>
                <li>
                    <a class="sharebutton facebook" data-basecount="0" data-sharetype="facebook" data-text="<?php echo WP_Simple_Share_Buttons()->get_post_title('facebook'); ?>" title="Share this on Facebook" href="#">
                        <i class="icon-facebook"></i>
                        <span class="count">0</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($options['buttons']['twitter'])): ?>
                <li>
                    <a class="sharebutton twitter" data-basecount="0" data-sharetype="twitter" data-text="<?php echo WP_Simple_Share_Buttons()->get_post_title('twitter'); ?>" data-via="<?php echo WP_Simple_Share_Buttons()->get_author('twitter'); ?>" data-related="<?php the_author(); ?>" title="Share this on Twitter" href="#">
                        <i class="icon-twitter"></i>
                        <span class="count">0</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($options['buttons']['linkedin'])): ?>
                <li>
                    <a class="sharebutton linkedin" data-basecount="0" data-sharetype="linkedin" data-text="<?php echo WP_Simple_Share_Buttons()->get_post_title('linkedin'); ?>" data-referer="<?php echo WP_Simple_Share_Buttons()->get_author('linkedin'); ?>" data-related="<?php echo WP_Simple_Share_Buttons()->get_author('linkedin'); ?>" title="Share this on LinkedIn" href="#">
                        <i class="icon-linkedin"></i>
                        <span class="count">0</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($options['buttons']['googleplus'])): ?>
                <li>
                    <a class="sharebutton googleplus" data-basecount="0" data-sharetype="googleplus" data-text="<?php echo WP_Simple_Share_Buttons()->get_post_title('googleplus'); ?>" title="Share this on Google Plus" href="#">
                        <i class="icon-googleplus">
                        </i> <span class="count">0</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>
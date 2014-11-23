<?php
$options = WP_Simple_Share_Buttons()->get_options();

?>
<div class="wpsimplesharebuttons simplesharebuttons" id="">
    <ul>
        <li>
            <a class="sharebutton facebook" data-basecount="<?php echo $options['buttons']['facebook']['basecount']; ?>" data-sharetype="facebook" data-text="<?php echo get_the_title(); ?>" title="Share this on Facebook" href="#">
                <i class="icon-facebook"></i> 
                <span class="count"><?php echo $options['buttons']['facebook']['basecount']; ?></span>
            </a>
        </li>
        <li>
            <a class="sharebutton twitter" data-basecount="<?php echo $options['buttons']['facebook']['basecount']; ?>" data-sharetype="twitter" data-text="<?php echo get_the_title(); ?>" data-via="<?php the_author(); ?>" data-related="<?php the_author(); ?>" title="Share this on Twitter" href="#">
                <i class="icon-twitter"></i> 
                <span class="count"><?php echo $options['buttons']['facebook']['basecount']; ?></span>
            </a>
        </li>
        <li>
            <a class="sharebutton linkedin" data-basecount="<?php echo $options['buttons']['facebook']['basecount']; ?>" data-sharetype="linkedin" data-text="<?php echo get_the_title(); ?>" data-referer="<?php the_author(); ?>" data-related="<?php the_author(); ?>" title="Share this on LinkedIn" href="#">
                <i class="icon-linkedin"></i> 
                <span class="count"><?php echo $options['buttons']['facebook']['basecount']; ?></span>
            </a>
        </li>
        <li>
            <a class="sharebutton googleplus" data-basecount="<?php echo $options['buttons']['googleplus']['basecount']; ?>" data-sharetype="googleplus" data-text="<?php echo get_the_title(); ?>" title="Share this on Google Plus" href="#">
                <i class="icon-googleplus">
                </i> <span class="count"><?php //echo WP_Simple_Share_Buttons()->get_button_count('googleplus'); ?></span>
            </a>
        </li>
    </ul>
</div>
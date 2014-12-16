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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php if(sizeof($button_options) > 0) : ?>
<div class="sharebird simplesharebuttons" data-post-id="<?php echo get_the_ID(); ?>" >
    <ul>
        <?php foreach( $button_options as $slug => $button ): ?>
        <li>
            <a href="#" class="sharebutton <?php echo $slug; ?>" 
                    <?php echo ShareBird()->get_data_attributes($slug, $post->ID, $button_options); ?>  
                    title="<?php _e(sprintf('Share this on %s', $button['name']), $plugin_slug); ?>" >
                <i class="icon-<?php echo $slug; ?>"></i>
                <span class="count"></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
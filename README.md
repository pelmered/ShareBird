WP Simple Share Buttons
====================

[SimpleShareButtons](https://github.com/SubZane/SimpleShareButtons) for WordPress. 

The philosophy of this plugin is to be fast and slim. The first thing this means is that there is no admin interface, however it is very easy to customize the plugin using WordPress hooks.

## Usage

By default the plugin outputs all share buttons after the_content (priority 999).

####Change output locations.

```php
add_filter( 'wpssb_options', 'change_share_button_output');

function change_share_button_output( $options )
{
	//Override output locations with the hook 'the_footer'
	$options['output_locations'] = array(
		'the_footer'
	);

	return $options;
}
```

#####Manual output in your theme

First remove the standard output
```php
add_filter( 'wpssb_default_output', '__return_false');
```

And then add this code where ever you want in your template

```php
WP_Simple_Share_Buttons()->output_buttons();
```

####Change what buttons we should show

```php
add_filter( 'wpssb_options', ‘change_share_button_output’);

function change_share_button_output( $options )
{
	//Remove LinkedIn
	unset($options['buttons']['linkedin']);
	//Remove Google Plus
	unset($options['buttons']['googleplus']);

	return $options;
}
```

####Change HTML output (Templating)

Add the templates to the folder ` /wpssb/ ` in your themes root folder. Use the name of the files when you output the buttons like this:

```php
WP_Simple_Share_Buttons()->output_buttons('my_button_template');
```

This will use the template located in ` /wpssb/my_button_template.php `

To get a started with your template, copy the template from ` /tempates/default.php ` in the plugin folder, usually ` /wp-content/plugins/wp-simple-share-buttons `.

####Change CSS output

Comming soon

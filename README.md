WP Simple Share Buttons
====================

[SimpleShareButtons](https://github.com/SubZane/SimpleShareButtons) for WordPress. 

The philosophy of this plugin is to be fast and slim. The first thing this means is that there is no admin interface, however it is very easy to customize the plugin using WordPress hooks.

![Demo](https://raw.githubusercontent.com/pelmered/WPSimpleShareButtons/master/simplesharebuttons.png "This is what the default output looks like")

__Please note that this plugin is stil under early development and the API(hooks and filters) may change without any notice untill we release version 1.0.0__

#### Services

The plugin currently supports the following services:

* Facebook
* Twitter
* LinkedIn
* Google+

## Usage

By default the plugin outputs all available buttons on single posts and pages with (priority 999).

You can customize which buttons are shown and where using filters.

####Displayed buttons

You can remove services using the following filter:

```php
add_filter('wpssb_buttons', function($buttons)
{
	unset($buttons['linkedin']);
	return $buttons;
});
```

*Default value: array('facebook', 'twitter', 'linkedin', 'googleplus')*

####Button output locations

The plugin can display share buttons before the post content/excerpt, after, or both.

To display the buttons before and after the post content/excerpt, you can use this snippet:

```php
add_filter('wpssb_output_positions', function($positions)
{
	return array('before', 'after');
});
```

*Default value: array('after')*

####Output conditionals

You can use [WordPress Conditional Tags](http://codex.wordpress.org/Conditional_Tags) to select where the output should be displayed. 
Example:

```php
add_filter('wpssb_output_conditionals', function($conditionals)
{
	return array('is_single', 'is_page');
});
```

*Default value: array('is_front_page', 'is_home', 'is_single', 'is_page', 'is_post_type_archive', 'is_singular')*

####Output by post type

If you have selected is_post, is_page or is_singular in the output conditionals, you can use a filter to specify which post types the buttons
are displayed for:

```php
add_filter('wpssb_output_post_types', function($post_types)
{
	return array('post');
});
```

*Default value: array('post', 'page')*

#####Manual output in your theme

You can disable the output from the plugin altogether using:

```php
add_filter( 'wpssb_default_output', '__return_false');
```

*Default value: true (bool)*

Then, add this code wherever you want in your template:

```php
if(class_exists('WP_Simple_Share_Buttons')
{
	WP_Simple_Share_Buttons()->output_buttons();
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

Coming soon

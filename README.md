WP Simple Share Buttons
====================

[SimpleShareButtons](https://github.com/SubZane/SimpleShareButtons) for WordPress. 

The philosophy of this plugin is to be fast and slim. The first thing this means is that there is no admin interface, however it is very easy to customize the plugin using WordPress hooks.

![Demo](https://raw.githubusercontent.com/pelmered/WPSimpleShareButtons/master/simplesharebuttons.png "This is what the default output looks like")

__Please note that this plugin is stil under early development and the API(hooks and filters) may change without any notice untill we release version 1.0.0__

## Index
[Supported services](#supported-services) 
[Usage](#usage) 
* [Hooks - actions and filters]() 
  * [Displayed buttons](#displayed-buttons) 
  * [Button output locations](#button-output-locations) 
  * [Output conditionals](#output-conditionals) 
  * [Output by post type](#output-by-post-type) 
  * [Customizing values / metadata](#customizing-values--metadata) 
* [Change HTML output (Templating)](#change-html-output-templating) 
* [Change CSS output](#change-css-output) 


## Supported services

The plugin currently supports the following services:

* Facebook
* Twitter
* LinkedIn
* Google+

## Usage

By default the plugin outputs all available buttons on single posts and pages with (priority 999).

You can customize which buttons are shown and where using filters.

### Hooks - actions and filters

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

####Customizing values / metadata

To customize the metadata(title, author name etc.) that are passed to the share popup you can change this in the tamplate file, or by using filters like this.

#####Customizing title
Add tags at the end of the share text.

```php
add_filter('wpssb_get_post_title', function($post_title, $post_id)
{
	$tags = wp_get_post_tags($post_id);

	foreach($tags AS $tag)
	{
		$post_title .= '#'.$tag->name;
	}
	
	return $post_title;
}, 10, 2);
```
The same code, but only for Facebook
```php
add_filter('wpssb_facebook_get_post_title', function($post_title, $post_id)
{
	$tags = wp_get_post_tags($post_id);

	foreach($tags AS $tag)
	{
		$post_title .= '#'.$tag->name;
	}
	
	return $post_title;
}, 10, 2);
```
#####Customizing author

```php
add_filter('wpssb_get_author', function($post_author, $post_id)
{
	$post_author = 'myNickName'

	return $post_author;
}, 10, 2);
```
The same code but run only for Twitter
```php
add_filter('wpssb_twitter_get_author', function($post_author, $post_id)
{
	$post_author = 'myTwitterName';
	
	return $post_author;
}, 10, 2);
```

###Change HTML output (Templating)

Add the templates to the folder ` /wpssb/ ` in your themes root folder. Use the name of the files when you output the buttons like this:

```php
WP_Simple_Share_Buttons()->output_buttons('my_button_template');
```

This will use the template located in ` /wpssb/my_button_template.php `

To get a started with your template, copy the template from ` /tempates/default.php ` in the plugin folder, usually ` /wp-content/plugins/wp-simple-share-buttons `.

###Change CSS output

It is off course very simple to just extend the default styles to make them look the way you want. But if you want to include your own styles that can be made like this:

```php
add_action( 'wp_enqueue_scripts', function() 
{
	//derigster default script
	wp_dequeue_style('wp-simple-share-buttons-public-styles');

	//Add your own custom script from theme folder
	wp_enqueue_style( 'my-wp-simple-share-buttons-styles', get_template_directory_uri() . '/css/my-styles.css' );
});
```


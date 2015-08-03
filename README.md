ShareBird social buttons
====================
###Share the Word, with ShareBird!

The philosophy of this plugin is to be fast, slim and developer friendly. The most prominent thing you will notice is that there is no settings and admin interface, however it is very easy to customize the plugin using WordPress hooks. It's very easy and straight forward. Read the documentation below to get started.

Based on [SimpleShareButtons](https://github.com/SubZane/SimpleShareButtons) by [Andreas Norman (SubZane)](https://github.com/SubZane). 


__Please note that this plugin is stil under early development and the API(hooks and filters) may change without any notice untill we release version 1.0.0__


####Default styling
![Demo](https://raw.githubusercontent.com/pelmered/ShareBird/master/simplesharebuttons.png "This is what the default output looks like")

[Screenshot1 - TwentyThriteen, default styling](https://raw.githubusercontent.com/pelmered/ShareBird/master/assets/screenshot-1.png "Screenshot1 - TwentyThriteen, default styling]")


## Index
* [Supported services](#supported-services) 
* [Usage / Documentation](#usage--documentation) 
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

## Usage / Documentation

By default the plugin outputs all available buttons on single posts and pages with (priority 999).

You can customize which buttons are shown and where using filters.

### Hooks - actions and filters

####Displayed buttons

You can remove services using the following filter:

```php
add_filter( 'sharebird_buttons', function( $buttons )
{
	unset( $buttons['linkedin'] );
	return $buttons;
});
```

*Default value: array('facebook', 'twitter', 'linkedin', 'googleplus')*

####Button output locations

The plugin can display share buttons before the post content/excerpt, after, or both.

To display the buttons before and after the post content/excerpt, you can use this snippet:

```php
add_filter( 'sharebird_output_positions', function( $positions )
{
	return array('before', 'after');
});
```

*Default value: array('after')*

####Output conditionals

You can use [WordPress Conditional Tags](http://codex.wordpress.org/Conditional_Tags) to select where the output should be displayed. 
Example:

```php
add_filter( 'sharebird_output_conditionals', function( $conditionals )
{
	return array( 'is_single', 'is_page' );
});
```

*Default value: array('is_front_page', 'is_home', 'is_single', 'is_page', 'is_post_type_archive', 'is_singular')*

####Output by post type

If you have selected is_post, is_page or is_singular in the output conditionals, you can use a filter to specify which post types the buttons
are displayed for:

```php
add_filter( 'sharebird_output_post_types', function( $post_types )
{
	return array( 'post' );
});
```

*Default value: array('post', 'page')*

#####Manual output in your theme

You can disable the output from the plugin altogether using:

```php
add_filter( 'sharebird_default_output', '__return_false' );
```

*Default value: true (bool)*

Then, add this code wherever you want in your template:

```php
if( class_exists( 'ShareBird' ) )
{
	ShareBird()->output_buttons();
}
```

####Customizing values / metadata

To customize the metadata(title, author name etc.) that are passed to the share popup you can change this in the tamplate file, or by using filters like this.

#####Customizing title
Add tags at the end of the share text.

```php
add_filter( 'sharebird_get_post_title', function( $post_title, $post_id )
{
	$tags = wp_get_post_tags( $post_id );

	foreach( $tags AS $tag )
	{
		$post_title .= '#'.$tag->name;
	}
	
	return $post_title;
}, 10, 2);
```
The same code, but only for Facebook
```php
add_filter( 'sharebird_facebook_get_post_title', function( $post_title, $post_id )
{
	$tags = wp_get_post_tags( $post_id );

	foreach( $tags AS $tag )
	{
		$post_title .= '#'.$tag->name;
	}
	
	return $post_title;
}, 10, 2);
```
#####Customizing author

```php
add_filter( 'sharebird_get_author', function( $post_author, $post_id )
{
	$post_author = 'myNickName'

	return $post_author;
}, 10, 2);
```
The same code but run only for Twitter
```php
add_filter( 'sharebird_twitter_get_author', function( $post_author, $post_id )
{
	$post_author = 'myTwitterName';
	
	return $post_author;
}, 10, 2);
```

###Change HTML output (Templating)

Copy the default template, sharebird-buttons.php from the plugins templates/ folder to your themes root folder.

Example:

```
/wp-content/themes/your-theme/sharebird-buttons.php
```

You can define as many templates as you want. Just put the template files in you theme and use them like this:

```php
ShareBird()->output_buttons( 'folder/my_button_template.php' );
```

This will use the template located in ` /folder/my_button_template.php `

###Change CSS output

It is off course very simple to just extend the default styles to make them look the way you want. But if you want to include your own styles that can be made like this:

```php
add_action( 'wp_enqueue_scripts', function() 
{
	//derigster default script
	wp_dequeue_style( 'sharebird-public-styles' );

	//Add your own custom script from theme folder
	wp_enqueue_style( 'my-sharebird-styles', get_template_directory_uri() . '/css/my-styles.css' );
});

## Development

To participate in ShareBird development, you need to setup NPM, Bower and Gulp.

After you have NPM installed, just navigate to the ShareBird folder and run:

```
npm install
bower install
gulp
gulp watch
```

You are now ready to contribute to Sharebird!
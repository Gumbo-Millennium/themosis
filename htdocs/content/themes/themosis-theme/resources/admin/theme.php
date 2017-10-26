<?php

/**
 * Define your theme custom code.
 */

add_theme_support('post-thumbnails');

$activities = PostType::make('activities', 'Activities', 'Activity')->set([
	'public'	=> true,
	'supports'	=> ['thumbnail', 'title', 'editor']
]);

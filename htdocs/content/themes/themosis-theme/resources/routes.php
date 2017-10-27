<?php

/**
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */

// CSS
Asset::add('bootstrapcss','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css');
Asset::add('gumbo-theme-stylesheet','css/gumbo-millennium.css');

// JS
Asset::add('jquerybootstrap','https://code.jquery.com/jquery-3.2.1.slim.min.js', [], false, true);
Asset::add('popperjs','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js', ['jquerybootstrap'], false, true);
Asset::add('bootstrapjs','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js', ['jquerybootstrap', 'popperjs'], false, true);
Asset::add('gumbo-theme-javascript','js/gumbo-millennium.js');

Route::any('front', function($post, $query)
{
	return view('home', ['post' => $post]);
});
Route::any('page', function($post, $query)
{
	return view('page', ['post' => $post]);
});

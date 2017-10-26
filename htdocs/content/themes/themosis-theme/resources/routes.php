<?php

/**
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */

// JQuery
Asset::add('jquery','https://code.jquery.com/jquery-3.2.1.slim.min.js');

// PopperJS
Asset::add('popperjs','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js');

// Bootstrap
Asset::add('bootstrapcss','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css');
Asset::add('bootstrapjs','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js');

Route::get('home', function()
{
    return view('welcome');
});

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
Asset::add('gumbo-theme-stylesheet', 'css/gumbo-millennium.css');

// JS
Asset::add(
    'popper',
    'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js',
    ['jquery']
);
Asset::add(
    'bootstrap-js',
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js',
    ['jquery', 'popper']
);
Asset::add('gumbo-theme-javascript', 'js/gumbo-millennium.js', ['jquery', 'popper', 'bootstrap-js']);

Route::any('front', function ($post, $query) {
    return view('home', ['post' => $post]);
});

Route::any('page', function ($post, $query) {
    return view('page', ['post' => $post]);
});

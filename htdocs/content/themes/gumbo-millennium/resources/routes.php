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
Asset::add('gumbo-millennium-webpack', 'manifest.js', []);
Asset::add('gumbo-millennium-vendor', 'js/vendor.js', ['gumbo-millennium-webpack']);
Asset::add('gumbo-millennium-js', 'js/gumbo-millennium.js', ['gumbo-millennium-vendor']);

Route::any('front', function ($post, $query) {
    return view('home', ['post' => $post]);
});

Route::any('page', function ($post, $query) {
    return view('page', ['post' => $post]);
});

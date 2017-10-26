<?php

/*----------------------------------------------------*/
// Docker config, used for docker-compose
/*----------------------------------------------------*/
// Database
define('DB_NAME', 'gumbo_db');
define('DB_USER', 'gumbo_usr');
define('DB_PASSWORD', 'queedahraes7aifaeshadohs9aiGh1Uh');
define('DB_HOST', 'mysql');

// WordPress URLs
define('WP_HOME', 'http://localhost:8337');
define('WP_SITEURL', 'http://localhost:8337/cms');

// Jetpack
define('JETPACK_DEV_DEBUG', true);

// Encoding
define('THEMOSIS_CHARSET', 'UTF-8');

// Development
define('SAVEQUERIES', true);
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', true);

// Themosis framework
define('THEMOSIS_ERROR', true);
define('BS', true);

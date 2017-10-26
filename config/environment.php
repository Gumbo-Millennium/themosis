<?php

/**
 * Flexibly define the environment to use. Docker uses php.gumbo.example as
 * hostname, so that's one to check, otherwise look for a GUMBO_ENV environment
 * global.
 *
 * @return string
 */
return function () {
    // Get OS hostname
    $hostname = gethostname();

    if ($hostname === 'php.gumbo.example') {
        return 'docker';
    }

    if (in_array(getenv('GUMBO_ENV'), ['local', 'dev', 'development'])) {
        return 'local';
    }

    return 'production';
};

<?php
//////////////////////////////////////////////////////////////
//////////// SOFTACULOUS REAL ESTATE LARAVEL SCRIPT ///////////
//////////////////////////////////////////////////////////////

// Script information
$softname = 'Real Estate Laravel';
$softversion = '1.0';
$softdesc = 'A Laravel-based Real Estate Management System';
$softcategory = 'CMS';

// Script Author
$softauthor = 'Your Company Name';

// Website
$softurl = 'https://your-website.com';

// Logo URL
$softlogo = 'https://your-website.com/logo.png';

// Version of PHP required
$php_version = '8.3';

// Version of MySQL required
$mysql_version = '5.7';

// Installation function
function install(){
    global $softurl, $php_version, $mysql_version, $__settings, $error;

    // Check PHP version
    if(version_compare(PHP_VERSION, $php_version, '<')){
        $error[] = 'PHP version '.$php_version.' or higher is required.';
        return false;
    }

    // Check MySQL version
    $mysql_version_installed = mysqli_get_server_info($__settings['softdb']);
    if(version_compare($mysql_version_installed, $mysql_version, '<')){
        $error[] = 'MySQL version '.$mysql_version.' or higher is required.';
        return false;
    }

    // Clone the repository
    if(!sclone('https://github.com/liberu-real-estate/real-estate-laravel.git', $__settings['softpath'])){
        $error[] = 'Could not clone the repository.';
        return false;
    }

    // Copy .env.example to .env
    if(!copy($__settings['softpath'].'.env.example', $__settings['softpath'].'.env')){
        $error[] = 'Could not copy .env.example to .env';
        return false;
    }

    // Update .env file with database credentials
    $env_file = $__settings['softpath'].'.env';
    $env_content = file_get_contents($env_file);
    $env_content = str_replace('DB_DATABASE=laravel', 'DB_DATABASE='.$__settings['softdb'], $env_content);
    $env_content = str_replace('DB_USERNAME=root', 'DB_USERNAME='.$__settings['softdbuser'], $env_content);
    $env_content = str_replace('DB_PASSWORD=', 'DB_PASSWORD='.$__settings['softdbpass'], $env_content);
    file_put_contents($env_file, $env_content);

    // Install Composer dependencies
    if(!scomposer('install --no-dev', $__settings['softpath'])){
        $error[] = 'Could not install Composer dependencies.';
        return false;
    }

    // Install npm dependencies
    if(!snpm('install', $__settings['softpath'])){
        $error[] = 'Could not install npm dependencies.';
        return false;
    }

    // Generate application key
    if(!sphp('artisan key:generate', $__settings['softpath'])){
        $error[] = 'Could not generate application key.';
        return false;
    }

    // Run database migrations
    if(!sphp('artisan migrate --force', $__settings['softpath'])){
        $error[] = 'Could not run database migrations.';
        return false;
    }

    // Run database seeders
    if(!sphp('artisan db:seed --force', $__settings['softpath'])){
        $error[] = 'Could not run database seeders.';
        return false;
    }

    // Optimize the application
    if(!sphp('artisan optimize', $__settings['softpath'])){
        $error[] = 'Could not optimize the application.';
        return false;
    }

    return true;
}

// Post-installation tasks
function post_install(){
    global $__settings;

    // Set up scheduled tasks
    $cron_file = $__settings['softpath'].'cron.txt';
    $cron_content = "* * * * * cd ".$__settings['softpath']." && php artisan schedule:run >> /dev/null 2>&1\n";
    file_put_contents($cron_file, $cron_content);

    echo "Installation completed successfully. Please set up the following cron job on your server:\n\n";
    echo $cron_content;
    echo "\nThis will ensure that scheduled tasks run properly.\n";
}

?>
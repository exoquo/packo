<?php

namespace Deployer;

// Include the Laravel & rsync recipes
require 'recipe/laravel.php';
require 'recipe/rsync.php';

set('application', 'dep-demo');
set('ssh_multiplexing', true); // Speed up deployment

set(
    'rsync_src', function () {
        return __DIR__; // If your project isn't in the root, you'll need to change this.
    }
);

// Configuring the rsync exclusions.
// You'll want to exclude anything that you don't want on the production server.
add(
    'rsync', [
        'exclude' => [
            '.git',
            '/.env',
            '/storage/',
            '/vendor/',
            '/node_modules/',
            '.github',
            'deploy.php',
        ],
    ]
);

// Set up a deployer task to copy secrets to the server.
// Grabs the dotenv file from the github secret
task(
    'deploy:secrets',
    function () {
        file_put_contents(__DIR__ . '/.env', getenv('DOT_ENV'));
        upload('.env', get('deploy_path') . '/shared');
    }
);


host('lager.exoquo.com')
    ->hostname('185.26.156.158')
    ->stage('production') // production / staging
    ->user('exopinv')
    ->set('deploy_path', '/home/exopinv/html');

after('deploy:failed', 'deploy:unlock'); // Unlock after failed deploy

desc('Deploy the application');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'rsync', // Deploy code & built assets
    'deploy:secrets', // Deploy secrets
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link', // |
    'artisan:view:cache',   // |
    'artisan:config:cache', // | Laravel specific steps
    'artisan:optimize',     // |
    'artisan:migrate',      // |
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

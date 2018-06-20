<?php
namespace Deployer;

require 'recipe/cachetool.php';
require 'recipe/laravel.php';

// Project name
set('application', 'isf-saisen');

// Project repository
set('repository', 'git@github.com:binotaliu/isf-saisen');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('cachetool', '/tmp/php-cgi.sock');

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('donate.socialism.org.tw')
    ->set('deploy_path', '/home/wwwroot/{{application}}')
    ->user('deployer');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
    run('cd {{release_path}} && yarn prod');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('deploy:symlink', 'cachetool:clear:opcache');

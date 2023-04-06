<?php

namespace Deployer;

set('instance_local_name', 'development');
set('instance_live_name', 'production');
set('composer_channel', 2);
set('branch_detect_to_deploy', false);
set('allow_anonymous_stats', false);
set('web_path', 'web/');
set('default_timeout', 900);

set('local/bin/wp', function () {
    return './vendor/bin/wp';
});

set('shared_dirs', [
        'web/app/uploads',
    ]
);

set('shared_files', [
    '.env.local',
    'web/.htaccess',
    '.env'
]);

set('writable_dirs', [
        'web/app/uploads',
    ]
);

set('clear_paths', [
    '.ddev',
    '.editorconfig',
    '.env.example',
    '.git',
    '.gitattributes',
    '.gitignore',
    'CHANGELOG.md',
    'CODE_OF_CONDUCT.md',
    'LICENSE.md',
    'CONTRIBUTING.md',
    'README.md',
    'composer.json',
    'composer.lock',
    'composer.phar',
    'dependencies.yml',
    'phpcs.xml',
    'ruleset.xml',
    'travis.yml'
]);

set('local_host', function () {
    return (new \SourceBroker\DeployerExtendedWordpressComposer\Drivers\EnvDriver())
        ->getInstanceName(getcwd() . '/.env');
});

// Look on https://github.com/sourcebroker/deployer-extended#buffer-start for docs
set('buffer_config', [
        'index.php' => [
            'entrypoint_filename' => 'web/index.php',
        ],
    ]
);

// Look https://github.com/sourcebroker/deployer-extended-media for docs
set('media_allow_push_live', true);
set('media_allow_pull_live', true);
set('media_allow_copy_live', true);
set('media_allow_link_live', true);
set('media',
    [
        'filter' => [
            '+ web/',
            '+ web/.htaccess',
            '+ web/app/',
            '+ web/app/uploads/',
            '+ web/app/uploads/**',
            '- *'
        ]
    ]);

// Look https://github.com/sourcebroker/deployer-extended-database for docs
set('db_allow_push_live', true);
set('db_allow_pull_live', true);
set('db_allow_copy_live', true);
set('db_databases',
    [
        'database_default' => [
            [
                'ignore_tables_out' => [],
                'post_sql_in' => '',
                'post_command' => ['export $(cat .env | grep PATH | xargs) && export $(cat .env.local | grep PATH | xargs) && {{local/bin/deployer}} db:import:post_command:wp_domains ' . get('local_host')]
            ],
            function () {
                return (new \SourceBroker\DeployerExtendedWordpressComposer\Drivers\EnvDriver())
                    ->getDatabaseConfig(getcwd() . '/.env');
            }
        ]
    ]
);

// Look https://github.com/sourcebroker/deployer-extended-database#db-dumpclean for docs
set('db_dumpclean_keep', [
    '*' => 5,
    'live' => 10,
]);
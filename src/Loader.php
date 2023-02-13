<?php

namespace SourceBroker\DeployerExtendedWordpressComposer;

use SourceBroker\DeployerLoader\Load;

class Loader
{
    public function __construct()
    {
        /** @noinspection PhpIncludeInspection */
        require_once 'recipe/common.php';
        new Load([
                ['path' => 'vendor/digitalerase/deployer-instance/deployer'],
                ['path' => 'vendor/digitalerase/deployer-extended/deployer'],
                ['path' => 'vendor/digitalerase/deployer-extended-database/deployer'],
                ['path' => 'vendor/digitalerase/deployer-extended-media/deployer'],
                ['path' => 'vendor/digitalerase/deployer-extended-wordpress-composer/deployer']
            ]
        );
    }
}

deployer-extended-wordpress-composer
====================================

.. contents:: :local:

What does it do?
----------------

This package provides deploy task for deploying WordPress with deployer (deployer.org) and additionally a tasks
to synchronize database and media files.

The deployment is expected to be compatible with composer based WordPress projects based on https://roots.io/bedrock/

Dependencies
------------

This package depends on following packages:

- | `digitalerase/deployer-extended`_
  | Package which provides some deployer tasks that can be used for any framework or CMS.

- | `digitalerase/deployer-extended-database`_
  | Package which provides some php framework independent deployer tasks to synchronize database.

- | `digitalerase/deployer-extended-media`_
  | Package which provides some php framework independent deployer tasks to synchronize media.

- | `wp-cli/search-replace-command`_
  | Package to change domains after database synchronization. Part of wp-cli/wp-cli utility.


Installation
------------

1) Install package with composer:
   ::

      composer require sourcebroker/deployer-extended-wordpress-composer

2) If you are using deployer as phar then put following lines in your deploy.php:
   ::

      require __DIR__ . '/vendor/autoload.php';
      new \SourceBroker\DeployerExtendedWordpressComposer\Loader();

3) Remove task "deploy" from your deploy.php. Otherwise you will overwrite deploy task defined in
   deployer/deploy/task/deploy.php

4) Example deploy.php file:
   ::

    <?php

    namespace Deployer;

    require_once(__DIR__ . '/vendor/sourcebroker/deployer-loader/autoload.php');
    new \SourceBroker\DeployerExtendedWordpressComposer\Loader();

    set('repository', 'git@my-git:my-project.git');

    host('live')
        ->setHostname('111.111.111.111')->port(22)
        ->setRemoteUser('www-data')
        ->set('branch', 'master')
        ->set('public_urls', ['https://www.example.com/'])
        ->set('deploy_path', '/var/www/example.com.live');

    host('beta')
        ->setHostname('111.111.111.111')->port(22)
        ->setRemoteUser('www-data')
        ->set('branch', 'beta')
        ->set('public_urls', ['https://beta.example.com/'])
        ->set('deploy_path', '/var/www/example.com.beta');

    host('dev')
        ->set('public_urls', ['https://example-com.local/'])
        ->set('deploy_path', getcwd());


Mind the declaration of host('dev'); Its needed for database tasks to declare domain replacements,
and path to store database dumps.


Synchronizing database
----------------------

Database synchronization is done with `digitalerase/deployer-extended-database`.
Example of command for synchronizing database from live to local instance:
::

   dep db:pull live

You can also copy database from live to beta instance like:
::

   dep db:copy live --options=target:beta



Domain replacement
++++++++++++++++++

The "post_command" task "db:import:post_command:wp_domains" will change domains declared in "public_urls". Domain
replacement is done with cli command "search-replace" from `wp-cli/wp-cli`_.

Please mind to have the same amount of "public_urls" for each of instances because replacement on domains is done for
every pair of corresponding urls.

Look at following example to give you idea:
::

    host('live')
        ->setHostname('111.111.111.111')
        ->setRemoteUser('www-data')
        ->set('public_urls', ['https://www.example.com', 'https://sub.example.com'])
        ->set('deploy_path', '/var/www/example.com.live');

    host('beta')
        ->setHostname('111.111.111.111')
        ->setRemoteUser('www-data')
        ->set('public_urls', ['https://beta.example.com', 'https://beta-sub.example.com'])
        ->set('deploy_path', '/var/www/example.com.beta');

    host('dev')
        ->set('public_urls', ['https://example-com.dev', 'https://sub-example-com.dev'])
        ->set('deploy_path', getcwd());


The if you will do:
::

    dep db:pull live

the following commands will be done automatically after database import:
::

    wp search-replace https://www.example.com https://example-com.dev
    wp search-replace https://sub.example.com https://sub-example-com.dev


Should I use "deployer-extended-wordpress" or "deployer-extended-wordpress-composer"?
-------------------------------------------------------------------------------------

In `digitalerase/deployer-extended-wordpress`_ the WordPress and third party plugins are installed manually. What you have in git is
basically only your theme. The good thing is that in such case you can update WordPress and plugins automatically.
This can be considered as preferable for low budget WordPress websites.

In `digitalerase/deployer-extended-wordpress-composer`_ the WordPress and third party plugins are installed using composer.
This way you gain more control over what is installed but at the same time to install new WordPress or new plugin
version you need first to modify composer.json or do composer update (depending how big upgrade you do). Then you need
to commit composer.json / composer.lock and do deploy which will install new version of WordPress and plugins.
This is additional work that can not be easily automated. One of additional advantages of this solution is that you can
easily cleanup infected WordPress/plugins files as with each deployment all php files are fresh (part from your git
and part from composer repositories).


.. _digitalerase/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _digitalerase/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _digitalerase/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
.. _digitalerase/deployer-extended-wordpress-composer: https://github.com/sourcebroker/deployer-extended-wordpress-composer
.. _wp-cli/search-replace-command: https://github.com/wp-cli/search-replace-command
.. _wp-cli/wp-cli: https://github.com/wp-cli/wp-cli

## Shopware Mage Tasks

This package includes common tasks to deploy a shopware project using [MagePHP](http://magephp.com/).

## .mage.yml example

```yaml
magephp:
    php_executable: /usr/bin/php # Leave this empty if you want to use the globally installed php executable.
    custom_tasks:
        - BestIt\Mage\Tasks\Deploy\DeployTask
        - BestIt\Mage\Tasks\Env\CreateEnvFileTask        
        - BestIt\Mage\Tasks\Env\SetEnvParametersTask
        - BestIt\Mage\Tasks\Misc\CopyTask
        - BestIt\Mage\Tasks\Misc\DenyRobotsTxtTask        
        - BestIt\Mage\Tasks\Release\PrepareTask
        - BestIt\Mage\Tasks\Release\SwPrepareTask
        - BestIt\Mage\Tasks\Shopware\ApplyMigrationsTask
        - BestIt\Mage\Tasks\Shopware\CommandTask
        - BestIt\Mage\Tasks\Shopware\UpdateLegacyPluginsTask
        - BestIt\Mage\Tasks\Shopware\UpdatePluginsTask
    environments:
        prod:
            user: apache
            host_path: /var/www/html
            releases: 4
            # Add php_executable in environment options to overwrite the global configuration above
            php_executable: /usr/bin/env/path/to/php
            # Add console_script_path in environment options to overwrite the default ./bin/console
            console_script_path: ./bin/console
            hosts:
                - production_server1
            pre-deploy:
                - env/create-env-file: 
                    file: '.env'
                    whitelist: 
                        - foo
                        - bar
                # Prefix                                          
                - env/set-env-parameters: { file: 'configs/config_prod.php', prefix: 'ENV_' }
                - misc/deny-robots-txt
            on-deploy:
                # Skips default prepare task which is not needed.
                - deploy/release/prepare

                # Creates new release directory and copies all content of current into the created directory.
                - prepare/sw-structure: { timeout: 500 }

                 # Creates a new symlink.
                - fs/link: { from: '../../media', to: 'media' }
                - fs/link: { from: '../../files', to: 'files' }

                # Pushes file(s) to server(s).
                # If strict is true, it will actually do a sync (i.e. delete files that do not exist locally anymore).
                # Otherwise it would just upload&overwrite without touching files that do not exist locally anymore.
                - deploy: { from: 'configs/config_prod.php', to: './config.php' }
                - deploy: { from: 'scripts/remote/', to: './scripts/', strict: true, timeout: 500 }
                - deploy: { from: 'libraries/', to: './engine/Library/', strict: false }
                - deploy: { from: 'legacy_plugins/Community/', to: 'engine/Shopware/Plugins/Community/', strict: false }
                - deploy: { from: 'legacy_plugins/Local/', to: 'engine/Shopware/Plugins/Local/', strict: true }
                - deploy: { from: 'plugins/', to: 'custom/plugins/', strict: true }
                - deploy: { from: 'licenses/', to: 'licenses/', strict: true }

                # Execute "raw" command on remote host.
                - exec: { cmd: './var/cache/clear_cache.sh', desc: 'Clear shopware cache.' }

                # Updates all (>=5.2 system) plugins on server(s).
                # Single remote command for executing updates is activated, default false.
                # Plugin refresh before update command is activated, default true.
                - shopware/update-plugins: { single_remote_command: true, plugin_refresh: true }

                # Updates all (legacy) plugins on server(s). "Sources" are the Community/Local folders.
                - shopware/update-legacy-plugins: { sync_sources_folders: true, single_remote_command: false }

                # Executes all SQL migrations on server(s). Both parameters are optional.
                - shopware/migrate: { table_suffix: 'bestit', migration_dir: 'sql' }

                # Executes remote the shopware commands
                
                # If ignoreReturnValue is true all return values of the command will be ignored. 
                # The usage of this option should be considered carefully because with this options no differentiation 
                # between an successful command call and an error command call is possible.
                # This option is necessary because specific shopware commands like plugin install will indicate an 
                # if the plugin is already installed
                
                # Installs an activate an plugin
                - shopware/command: { cmd: 'sw:plugin:install --activate Cron', ignoreReturnValue: true }

                # Warms up the shopware theme cache on server(s).
                - shopware/command: { cmd: 'sw:theme:cache:generate' }
```

## Installation

### Step 1: Composer

Run:

```
composer require bestit/shopware-mage-tasks --dev
```

### Step 2: .mage.yml

Create a .mage.yml file in your project root directory and define your desired tasks as per the example above.
For more information about what you can do check out the [documentation](http://magephp.com/).

### Step 3: Initial folder structure on the deploy target

Before the first deployment with mage, you need to set up the folder structure manually, because the original mage deploy/release/prepare-task is skipped and replaced by the prepare/sw-structure-task which performs an ordinary file copy from CURRENT to the newly created REALEASE:

```
- host_path on SERVER
    - current -> release/initial    (Symlink)
    - release
        - initial
            - bin
            - configs
            - custom
            - engine
            - files -> ../../files  (Symlink)
            - media -> ../../media  (Symlink)
            - recovery
            - scripts
            - themes
            - var
            - vendor
            - web
            ...
    - media
    - files
```

### Step 4: That's it!

You just need to run the deployment script:

```
vendor/bin/mage deploy <environment>
```

## License

This software is open-sourced under the MIT license.
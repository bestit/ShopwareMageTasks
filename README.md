## Shopware Mage Tasks

This package includes common tasks to deploy a shopware project using [MagePHP](http://magephp.com/).

## .mage.yml example

```yaml
magephp:
    php_executable: /usr/bin/php # Leave this empty if you want to use the globally installed php executable.
    custom_tasks:
        - BestIt\Mage\Tasks\Deploy\DeployTask
        - BestIt\Mage\Tasks\Misc\CopyTask
        - BestIt\Mage\Tasks\Misc\SetEnvParametersTask
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
            hosts:
                - production_server1
            pre-deploy:
                - misc/set-env-parameters: { file: 'configs/config_prod.php' }
            on-deploy:
                - deploy/release/prepare # Skips default prepare task which is not needed.
                - prepare/sw-structure: { timeout: 500 } # Creates new release directory and copies all content of current into the created directory.
                - fs/link: { from: '../../media', to: 'media' } # Creates a new symlink.
                - fs/link: { from: '../../files', to: 'files' } # Creates a new symlink.
                - deploy: { from: 'configs/config_prod.php', to: './config.php' } # Pushes config file to server(s).
                - deploy: { from: 'scripts/remote/', to: './scripts/', strict: true, timeout: 500 } # Pushes scripts files to server(s).
                - deploy: { from: 'libraries/', to: './engine/Library/', strict: false } # Pushes library (engine/Library) to server(s).
                - deploy: { from: 'legacy_plugins/Community/', to: 'engine/Shopware/Plugins/Community/', strict: false }
                - deploy: { from: 'legacy_plugins/Local/', to: 'engine/Shopware/Plugins/Local/', strict: true }
                - deploy: { from: 'plugins/', to: 'custom/plugins/', strict: true }
                - deploy: { from: 'licenses/', to: 'licenses/', strict: true } # sync files in licenses folder from local to server
                - exec: { cmd: './var/cache/clear_cache.sh', desc: 'Clear shopware cache.' }
                - shopware/update-plugins # Updates all (>=5.2 system) plugins on server(s).
                - shopware/update-legacy-plugins: { sync_sources_folders: true } # Updates all (legacy) plugins on server(s). "Sources" are the Community/Local folders.
                - shopware/migrate: { table_suffix: 'bestit', migration_dir: 'sql' } # Executes all SQL migrations on server(s). Both parameters are optional.
                - shopware/command: { cmd: 'sw:swaglicense:import ./licenses/licenses_prod.ini' } # Import licenses of license ini file into database, SwagLicense is needed for command
                - shopware/command: { cmd: 'sw:theme:cache:generate' } # Warms up the shopware theme cache on server(s).
```

## Installation

### Step 1: Composer

Currently the package is not registered on Packagist, so you will have to tell composer about it manually.
You can do so by putting the following content in your composer.json file:

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/bestit/ShopwareMageTasks"
        }
    ]
```

Then from the command line, run:

```
composer require bestit/shopware-mage-tasks
```

### Step 2: .mage.yml

Create a .mage.yml file in your project root directory and define your desired tasks as per the example above.
For more information about what you can do check out the [documentation](http://magephp.com/).

### Step 3: That's it!

You just need to run the deployment script:

```
vendor/bin/mage deploy <environment>
```

## License

This software is open-sourced under the MIT license.
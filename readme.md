# PHPRC
Run PHP scripts as shell commands

## Install

``$ git clone https://github.com/kyrrr/phprc.git``


## Configure
Optionally load example scripts:
```$ mv example_scripts scripts```

 - Included here is a simple hello world and a news ticker.

Or create your own:

```$ vim scripts/phprc_hello_world.php```

```php
<?php
// phprc_hello_world.php
echo "Hello, World!";
``` 

Create autoload of PHPRC source:
```$ composer dump-autoload```

Run the generator: ```$ php public/index.php```

Register the generated functions: ```$ source output/.phprc```

If you get newline errors then you can thank Bill Gates. 

Run your command: ```$ phprc_hello```

#### Automate
To automate this process, add something like (example.load.sh) to a file in your home directory and source it in your ```.bashrc``` or similar:

```BASH
PHP_RC_FILE=~/phprc/output/.phprc
PHP_RC_LOADER=~/phprc/public/index.php
if test -f "$PHP_RC_FILE"; then
     source "$PHP_RC_FILE"
elif test -f "$PHP_RC_LOADER"; then
     php "$PHP_RC_LOADER" && source "$PHP_RC_FILE"
fi
```

This will either source the generated functions file if it exists, or trigger file generation.

Place your scripts in ```scripts/``` or specify your own source in ```phprc_config.json```.
The loader script will recursively look for ```.php``` files and create callable functions.

#TODO
- Middleware script for parameter analysis/injection
- Framework for flags and stuff (common functionality like --help --version)

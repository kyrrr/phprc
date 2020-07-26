# PHPRC
Run PHP scripts as shell commands

## Install

``git clone`` this repo

Add something like (example.load.sh) to a file in your home directory and source it in your ```.bashrc``` or similar:

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

#TODO:
- Middleware script for parameter analysis/injection
 
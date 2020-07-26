PHP_RC_FILE=~/.phprc
PHP_RC_LOADER=~/.phprc.d/load.php
if test -f "$PHP_RC_FILE"; then
     source ~/.phprc
elif test -f "$PHP_RC_LOADER"; then
     php "$PHP_RC_LOADER"
fi

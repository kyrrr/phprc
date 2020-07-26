PHP_RC_FILE=~/phprc/output/.phprc
PHP_RC_LOADER=~/phprc/public/index.php
if test -f "$PHP_RC_FILE"; then
     source "$PHP_RC_FILE"
elif test -f "$PHP_RC_LOADER"; then
     php "$PHP_RC_LOADER"
fi

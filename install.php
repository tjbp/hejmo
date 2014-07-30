#!/usr/bin/php
<?php

// Download Composer and install dependencies
chdir(__DIR__);

echo "Setting permissions..." . PHP_EOL;
chmod('app/config/hejmo', 0757);
chmod('app/storage/cache', 0757);
chmod('app/storage/logs', 0757);
chmod('app/storage/meta', 0757);
chmod('app/storage/sessions', 0757);
chmod('app/storage/views', 0757);

echo "Downloading Composer..." . PHP_EOL;
shell_exec('/usr/bin/php -r "readfile(\'https://getcomposer.org/installer\');" | /usr/bin/php &> /dev/null');

echo "Installing dependencies..." . PHP_EOL;
shell_exec('/usr/bin/php composer.phar install');

echo "Clearing up..." . PHP_EOL;
unlink('composer.phar');

echo "Finished!" . PHP_EOL;

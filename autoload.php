<?php

spl_autoload_register(function (string $className): void {
    include  BASE_DIR . DIRECTORY_SEPARATOR . str_replace('App', 'src', str_replace('\\', DIRECTORY_SEPARATOR, $className)) . '.php';
});
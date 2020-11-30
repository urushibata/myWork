<?php

const Version = '8.x';

function getLangContent($str)
{
    $makeInvalidPreCode = preg_replace('/<pre><code>/', '*NOT-BLOCK*', $str, 1);
    $makeInvalidCodePre = preg_replace('/<\/code><\/pre>/', "**NOT-BLOCK**", $makeInvalidPreCode, 1);

    $deletedTail = preg_replace('/<\/code><\/pre>[\s\S]+/', "\n", $makeInvalidCodePre);
    $deletedHead = preg_replace('/[\s\S]+<pre><code>/', '', $deletedTail);

    $decoded = html_entity_decode($deletedHead, ENT_QUOTES);

    return $decoded;
}

if (false === is_dir(getcwd().'/resources/lang/ja')) {
    if (false === mkdir(getcwd().'/resources/lang/ja', 0777, true)) {
        file_put_contents('php://stderr', getcwd().'/resources/lang/ja'.PHP_EOL);

        exit(1);
    }
}

$authContents = getLangContent(file_get_contents('https://readouble.com/laravel/'.Version.'/ja/auth-php.html'));

if (false === file_put_contents(getcwd() . '/resources/lang/ja/auth.php', $authContents)) {
    file_put_contents('php://stderr', getcwd() . '/resources/lang/ja/auth.php' . PHP_EOL);

    exit(1);
}


$paginationContents = getLangContent(file_get_contents('https://readouble.com/laravel/'.Version.'/ja/pagination-php.html'));

if (false === file_put_contents(getcwd().'/resources/lang/ja/pagination.php', $paginationContents)) {
    file_put_contents('php://stderr', getcwd().'/resources/lang/ja/pagination.php'.PHP_EOL);

    exit(1);
}

$passwordsContents = getLangContent(file_get_contents('https://readouble.com/laravel/'.Version.'/ja/passwords-php.html'));

if (false === file_put_contents(getcwd() . '/resources/lang/ja/passwords.php', $passwordsContents)) {
    file_put_contents('php://stderr', getcwd() . '/resources/lang/ja/passwords.php' . PHP_EOL);

    exit(1);
}


$validationContents = getLangContent(file_get_contents('https://readouble.com/laravel/'.Version.'/ja/validation-php.html'));

if (false === file_put_contents(getcwd() . '/resources/lang/ja/validation.php', $validationContents)) {
    file_put_contents('php://stderr', getcwd() . '/resources/lang/ja/validation.php' . PHP_EOL);

    exit(1);
}

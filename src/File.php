<?php

/**
 *  Website: https://mudew.com/
 *  Author: Lkeme
 *  License: The MIT License
 *  Updated: 2018
 */

namespace roceys\BiliHelper;

use roceys\BiliHelper\Curl;
use roceys\BiliHelper\Sign;
use roceys\BiliHelper\Log;
use roceys\BiliHelper\Index;

class File
{
    // RUN
    public static function run()
    {
    }

    // PUT CONF
    public static function writeNewEnvironmentFileWith($key, $value)
    {
        file_put_contents(__DIR__ . '/../conf/' . Index::$conf_file, preg_replace(
            '/^' . $key . '=' . getenv($key) . '/m',
            $key . '=' . $value,
            file_get_contents(__DIR__ . '/../conf/' . Index::$conf_file)
        ));
        // 写入系统变量
        putenv($key . '=' . $value);
    }
}
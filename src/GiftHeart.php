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

class GiftHeart
{
    public static $lock = 0;

    // RUN
    public static function run()
    {
        if (self::$lock > time()) {
            return;
        }
        if (self::giftheart()) {
            self::$lock = time() + 60 * 60;
            return;
        }
        self::$lock = time() + 5 * 60;
    }

    // GIFT HEART
    protected static function giftheart(): bool
    {
        $payload = [
            'roomid' => getenv('ROOM_ID'),
        ];
        $raw = Curl::get('https://api.live.bilibili.com/gift/v2/live/heart_gift_receive', Sign::api($payload));
        $de_raw = json_decode($raw, true);

        if ($de_raw['code'] == -403) {
            Log::info($de_raw['msg']);
            $payload = [
                'ruid' => 17561885,
            ];
            Curl::get('https://api.live.bilibili.com/eventRoom/index', Sign::api($payload));
            return true;
        }

        if ($de_raw['code'] != 0) {
            Log::warning($de_raw['msg']);
            return false;
        }

        if ($de_raw['data']['heart_status'] == 0) {
            Log::info('没有礼物可以领了呢!');
            return true;
        }

        if (isset($de_raw['data']['gift_list'])) {
            foreach ($de_raw['data']['gift_list'] as $vo) {
                Log::info("{$de_raw['msg']}，礼物 {$vo['gift_name']} ({$vo['day_num']}/{$vo['day_limit']})");
            }
            return false;
        }
    }

}
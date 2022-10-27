<?php if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}

function cron_wmzz_ban()
{
    global $i, $m;
    $s = unserialize(option::get('plugin_wmzz_ban'));
    $now = strtotime(date('Y-m-d'));
    $date = date('Y-m-d H:i:s');
    $day_list = [
        '1',
        '3',
        '10',
    ];
    $log = "";

    $y = $m->query("SELECT * FROM `" . DB_PREFIX . "wmzz_ban` WHERE `nextdo` <= '{$now}' LIMIT {$s['limit']}");
    while ($x = $m->fetch_array($y)) {

        $is_open = option::uget('wmzz_ban_enable', $x['uid']);
        if (!$is_open) {
            option::uset('wmzz_ban_enable', 'on', $x['uid']);
            $is_open = true;
        } else {
            if ($is_open == "on") {
                $is_open = true;
            } else if ($is_open == "off") {
                $is_open = false;
            }
        }

        $diff = $x['date'] - $now;
        $diff_day = ceil($diff / 86400);
        $r = '';
        if ($x['date'] == '0') {
            $r = $x['day'];
        } else if ($diff < 0) {
            $r = '-1';
        } else {
            if ($diff_day >= $x['day']) {
                $r = $x['day'];
            } else if ($diff_day == 0) {
                $r = '1';
            } else {
                foreach (array_reverse($day_list) as $day_i) {
                    if ($diff_day >= $day_i) {
                        $r = $day_i;
                    }
                }
            }
        }

        if ($is_open) {
            if ($r >= '0') {
                if (empty($log)) {
                    $log .= $date . " 封禁结果: " . PHP_EOL;
                }
                $log .= "云签到平台uid为\"" . $x["uid"] . "\"的用户"
                    . "添加的百度账户\"" . $i['user']['baidu'][$x['pid']] . "\""
                    . "对\"" . $x['tieba'] . "\"吧portrait为\""
                    . $x['portrait'] . "\"的目标百度账户"
                    . "执行了原因为\"" . $x['msg'] . "\","
                    . "封禁天数为\"" . $r . "\"天的封禁,封禁结果: ";
                $bduss = misc::getCookie($x['pid']);
                $c = new wcurl('http://tieba.baidu.com/pmc/blockid');
                $c->addcookie('BDUSS=' . $bduss);
                $option = array(
                    'day' => $r,
                    'fid' => misc::getFid($x['tieba']),
                    'tbs' => misc::getTbs($x['uid'], $bduss),
//                'ie' => 'utf-8',
                    'ie' => 'gbk',
                    'user_name[]' => '',
                    'nick_name[]' => '',
                    'portrait[]' => $x['portrait'],
                    'pid[]' => '',
                    'reason' => $x['msg']
                );
                $res = $c->post($option);
                $res = json_decode($res, TRUE);
                if ($res['errno'] == 0) {
                    $next = $now + ($r * 86400);
                    $m->query("UPDATE `" . DB_PREFIX . "wmzz_ban` SET `nextdo` = '{$next}' WHERE `id` = '{$x['id']}'");
                    $log .= "封禁成功" . PHP_EOL;
                } else {
                    $next = $now + ($r * 86400);
                    $m->query("UPDATE `" . DB_PREFIX . "wmzz_ban` SET `nextdo` = '{$next}' WHERE `id` = '{$x['id']}'");
                    $log .= "封禁失败, 原因为\"" . $res['errno'] . ", " . $res['errmsg'] . "\"" . PHP_EOL;
                }
//            else if ($res['errno'] == 74) {    //用户名不存在   224011 需要验证码
//                $m->query("DELETE FROM `" . DB_PREFIX . "wmzz_ban` WHERE `id` = '{$x['id']}'");
//            }
                usleep(250000);
            } else {
                $m->query("DELETE FROM `" . DB_PREFIX . "wmzz_ban` WHERE `id` = '{$x['id']}'");
            }
        } else {
            $next = $now + ($r * 86400);
            $m->query("UPDATE `" . DB_PREFIX . "wmzz_ban` SET `nextdo` = '{$next}' WHERE `id` = '{$x['id']}'");
        }
    }
    $log = trim($log);
    if (empty($log)) {

    } else {
        return $log;
    }
}

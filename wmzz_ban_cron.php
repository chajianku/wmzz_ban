<?php if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}

/**
 * 获取封禁类型
 * @param $date 封禁截止日期
 */
function wmzz_ban_getTime($date)
{
    $result = '';
    if ($date == '0') {
        $result = '0';
    } else {
        $result = $date - strtotime(date('Y-m-d'));
    }
    return $result;
}

function cron_wmzz_ban()
{
    global $m;
    $s = unserialize(option::get('plugin_wmzz_ban'));
    $now = strtotime(date('Y-m-d'));
    $y = $m->query("SELECT * FROM `" . DB_PREFIX . "wmzz_ban` WHERE `nextdo` <= '{$now}' LIMIT {$s['limit']}");
    while ($x = $m->fetch_array($y)) {
        $r = wmzz_ban_getTime($x['date']);
        if ($r >= '0') {
            $day = '1';
            $bduss = misc::getCookie($x['pid']);
            $c = new wcurl('http://tieba.baidu.com/pmc/blockid');
            $c->addcookie('BDUSS=' . $bduss);
            $option = array(
                'day' => $day,
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
                $next = $now + ($day * 86400);
                $m->query("UPDATE `" . DB_PREFIX . "wmzz_ban` SET `nextdo` = '{$next}' WHERE `id` = '{$x['id']}'");
            }
//            else if ($res['errno'] == 74) {    //用户名不存在   224011 需要验证码
//                $m->query("DELETE FROM `" . DB_PREFIX . "wmzz_ban` WHERE `id` = '{$x['id']}'");
//            }
        } else {
            $m->query("DELETE FROM `" . DB_PREFIX . "wmzz_ban` WHERE `id` = '{$x['id']}'");
        }
    }
}

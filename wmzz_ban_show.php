<?php if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}
global $i, $m;

if (SYSTEM_PAGE == 'store') {
    $pid = addslashes(strip_tags($_POST['pid']));
    $msg = addslashes(strip_tags($_POST['msg']));
    $tieba = addslashes(strip_tags($_POST['tieba']));
    $day = addslashes(strip_tags($_POST['day']));
    $anchor = addslashes(strip_tags($_POST['anchor']));
    option::uset("wmzz_ban_pid", $pid, UID);
    option::uset("wmzz_ban_msg", $msg, UID);
    option::uset("wmzz_ban_tieba", $tieba, UID);
    option::uset("wmzz_ban_day", $day, UID);
    ReDirect(SYSTEM_URL . 'index.php?plugin=wmzz_ban&ok' . '#' . $anchor);
} else if (SYSTEM_PAGE == 'save') {
    ob_end_clean();
    $data = json_decode($_POST['info'], true);
    $cache_wmzz_ban_enable = "";
    if (!empty($data['wmzz_ban_enable'])) {
        $cache_wmzz_ban_enable = addslashes(strip_tags($data['wmzz_ban_enable']));
    }
    if (empty($cache_wmzz_ban_enable)
    ) {
        $return_arr = array('code' => 0, 'msg' => '无效请求!');
    } else {
        option::uset('wmzz_ban_enable', $cache_wmzz_ban_enable, UID);
        $return_arr = array('code' => 1, 'msg' => '保存成功!');
    }
    echo json_encode($return_arr);
    die;
} else if (SYSTEM_PAGE == 'add') {
    $pid = !empty($_POST['pid']) ? intval($_POST['pid']) : msg('请选择PID');
    if (!isset($i['user']['bduss'][$pid])) {
        msg('PID不存在');
    }
    $tieba = !empty($_POST['tieba']) ? addslashes(strip_tags($_POST['tieba'])) : msg('请输入贴吧');
    $msg = !empty($_POST['msg']) ? addslashes(strip_tags($_POST['msg'])) : msg('请输入封禁原因');
    $day = !empty($_POST['day']) ? addslashes(strip_tags($_POST['day'])) : msg('请输入每次封禁天数');
    $portrait = !empty($_POST['portrait']) ? addslashes(strip_tags($_POST['portrait'])) : msg('请输入被封禁人portrait');
    $portrait = explode("\n", trim($portrait));
    $anchor = addslashes(strip_tags($_POST['anchor']));
    if (isset($_POST['date'])) {
        if (empty($_POST['date'])) {
            $date = '0';
        } else {
            $date = strtotime($_POST['date']);
        }
    } else {
        msg('请输入截止日期');
    }
    $prefix = "id=tb.";
    $suffix1 = "&";
    $suffix2 = "?";
    foreach ($portrait as $portrait_i) {
        $portrait_i = trim($portrait_i);
        if (!empty($portrait_i)) {
            if (strpos($portrait_i, $prefix)) {
                $portrait_i = "tb." . explode($prefix, $portrait_i)[1];
            }
            if (strpos($portrait_i, $suffix1)) {
                $portrait_i = explode($suffix1, $portrait_i)[0];
            }
            if (strpos($portrait_i, $suffix2)) {
                $portrait_i = explode($suffix2, $portrait_i)[0];
            }
//            if(strlen($portrait_i)=="")
            $now = strtotime(date('Y-m-d'));
            $exists = $m->fetch_array($m->query(
                "SELECT * FROM `" . DB_PREFIX . "wmzz_ban`"
                . " WHERE `uid` = '" . UID . "' AND `pid` = '{$pid}' AND `tieba` = '{$tieba}'"
                . " AND `portrait` = '{$portrait_i}' AND `msg` = '{$msg}' AND `date` = '{$date}' AND `day` = '{$day}' LIMIT 1"
            ));
            if (!is_null($exists)) {
                $m->query("INSERT INTO `" . DB_PREFIX . "wmzz_ban` (`uid`, `pid`, `tieba`, `portrait`, `msg` , `date`, `day` , `nextdo`)"
                    . " VALUES ('" . UID . "', '{$pid}', '{$tieba}', '{$portrait_i}', '{$msg}', '{$date}', '{$day}', '{$now}')");
            }
        }
    }
    ReDirect(SYSTEM_URL . 'index.php?plugin=wmzz_ban&ok' . '#' . $anchor);
} elseif (SYSTEM_PAGE == 'update') {
    $id = !empty($_POST['id']) ? intval($_POST['id']) : msg('请选择ID');
    $pid = !empty($_POST['pid']) ? intval($_POST['pid']) : msg('请选择PID');
    if (!isset($i['user']['bduss'][$pid])) {
        msg('PID不存在');
    }
    $tieba = !empty($_POST['tieba']) ? addslashes(strip_tags($_POST['tieba'])) : msg('请输入贴吧');
    $msg = !empty($_POST['msg']) ? addslashes(strip_tags($_POST['msg'])) : msg('请输入封禁原因');
    $nextdo = !empty($_POST['nextdo']) ? addslashes(strip_tags($_POST['nextdo'])) : msg('请输入下次封禁日期');
    $nextdo = strtotime($nextdo);
    $msg = !empty($_POST['msg']) ? addslashes(strip_tags($_POST['msg'])) : msg('请输入封禁原因');
    $day = !empty($_POST['day']) ? addslashes(strip_tags($_POST['day'])) : msg('请输入每次封禁天数');
    $portrait = !empty($_POST['portrait']) ? addslashes(strip_tags($_POST['portrait'])) : msg('请输入被封禁人portrait');
    $portrait = explode("\n", trim($portrait));
    $anchor = addslashes(strip_tags($_POST['anchor']));
    if (isset($_POST['date'])) {
        if (empty($_POST['date'])) {
            $date = '0';
        } else {
            $date = strtotime($_POST['date']);
        }
    } else {
        msg('请输入截止日期');
    }
    $prefix = "id=tb.";
    $suffix1 = "&";
    $suffix2 = "?";
    foreach ($portrait as $portrait_i) {
        $portrait_i = trim($portrait_i);
        if (!empty($portrait_i)) {
            if (strpos($portrait_i, $prefix)) {
                $portrait_i = "tb." . explode($prefix, $portrait_i)[1];
            }
            if (strpos($portrait_i, $suffix1)) {
                $portrait_i = explode($suffix1, $portrait_i)[0];
            }
            if (strpos($portrait_i, $suffix2)) {
                $portrait_i = explode($suffix2, $portrait_i)[0];
            }
//            if(strlen($portrait_i)=="")
            $m->query("UPDATE `" . DB_PREFIX . "wmzz_ban` "
                . "SET `pid` = '{$pid}', `tieba` = '{$tieba}', `msg` = '{$msg}',"
                . " `portrait` = '{$portrait_i}', `date` = '{$date}', `nextdo` = '{$nextdo}',"
                . " `day` = '{$day}'"
                . " WHERE `id` = '{$id}'");
        }
    }
    ReDirect(SYSTEM_URL . 'index.php?plugin=wmzz_ban&ok' . '#' . $anchor);
} elseif (SYSTEM_PAGE == 'batchedit') {
    $anchor = addslashes(strip_tags($_POST['anchor']));
    $pid = !empty($_POST['pid']) ? intval($_POST['pid']) : "";
    $pid2 = !empty($_POST['pid2']) ? intval($_POST['pid2']) : "";
    if ((empty($pid) && !empty($pid2)) || (!empty($pid) && empty($pid2))) {
        msg('请正确选择PID');
    }
    $tieba = !empty($_POST['tieba']) ? addslashes(strip_tags($_POST['tieba'])) : "";
    $tieba2 = !empty($_POST['tieba2']) ? addslashes(strip_tags($_POST['tieba2'])) : "";
    if ((empty($tieba) && !empty($tieba2)) || (!empty($tieba) && empty($tieba2))) {
        msg('请正确输入贴吧');
    }
    $msg = !empty($_POST['msg']) ? addslashes(strip_tags($_POST['msg'])) : "";
    $msg2 = !empty($_POST['msg2']) ? addslashes(strip_tags($_POST['msg2'])) : "";
    if ((empty($msg) && !empty($msg2)) || (!empty($msg) && empty($msg2))) {
        msg('请正确输入封禁原因');
    }
    $nextdo = !empty($_POST['nextdo']) ? addslashes(strip_tags($_POST['nextdo'])) : "";
    $nextdo2 = !empty($_POST['nextdo2']) ? addslashes(strip_tags($_POST['nextdo2'])) : "";
    if ((empty($nextdo) && !empty($nextdo2)) || (!empty($nextdo) && empty($nextdo2))) {
        msg('请正确输入下次封禁日期');
    }
    $day = !empty($_POST['day']) ? addslashes(strip_tags($_POST['day'])) : "";
    $day2 = !empty($_POST['day2']) ? addslashes(strip_tags($_POST['day2'])) : "";
    if ((empty($day) && !empty($day2)) || (!empty($day) && empty($day2))) {
        msg('请正确输入每次封禁天数');
    }
    $portrait = !empty($_POST['portrait']) ? addslashes(strip_tags($_POST['portrait'])) : "";
    $portrait2 = !empty($_POST['portrait2']) ? addslashes(strip_tags($_POST['portrait2'])) : "";
    if ((empty($portrait) && !empty($portrait2)) || (!empty($portrait) && empty($portrait2))) {
        msg('请正确输入被封禁人portrait');
    }
    $portrait = explode("\n", trim($portrait));
    $portrait2 = explode("\n", trim($portrait2));
    $portrait_count = count($portrait);
    $portrait2_count = count($portrait2);
    if (($portrait_count == $portrait2_count)
        || ($portrait2_count == 1)) {

    } else {
        msg('请输入正确数量的被封禁人portrait');
    }
    if ($_POST['date'] == '0') {
        $date = '0';
    } else {
        if (empty($_POST['date'])) {
            $date = '';
        } else {
            $date = strtotime($_POST['date']);
        }
    }
    if ($_POST['date2'] == '0') {
        $date2 = '0';
    } else {
        if (empty($_POST['date2'])) {
            $date2 = '';
        } else {
            $date2 = strtotime($_POST['date2']);
        }
    }
    if (((empty($date) && $date != '0') && (!empty($date2) || $date2 = '0'))
        || ((!empty($date) || $date == '0') && (empty($date2) && $date2 != '0'))) {
        msg('请正确输入每次封禁天数');
    }

    $status = false;
    $sql1 = "UPDATE `" . DB_PREFIX . "wmzz_ban` SET ";
    $sql2 = " WHERE ";
    if (!empty($pid) && !empty($pid2)) {
        if (!isset($i['user']['bduss'][$pid2])) {
            msg('PID不存在');
        }
        if ($status) {
            $sql1 .= ", `pid` = '{$pid2}'";
            $sql2 .= " AND `pid` = '{$pid}'";
        } else {
            $sql1 .= "`pid` = '{$pid2}'";
            $sql2 .= "`pid` = '{$pid}'";
        }
        $status = true;
    }
    if (!empty($tieba) && !empty($tieba2)) {
        if ($status) {
            $sql1 .= ", `tieba` = '{$tieba2}'";
            $sql2 .= " AND `tieba` = '{$tieba}'";
        } else {
            $sql1 .= "`tieba` = '{$tieba2}'";
            $sql2 .= "`tieba` = '{$tieba}'";
        }
        $status = true;
    }
    if (!empty($msg) && !empty($msg2)) {
        if ($status) {
            $sql1 .= ", `msg` = '{$msg2}'";
            $sql2 .= " AND `msg` = '{$msg}'";
        } else {
            $sql1 .= "`msg` = '{$msg2}'";
            $sql2 .= "`msg` = '{$msg}'";
        }
        $status = true;
    }
    if (!empty($nextdo) && !empty($nextdo2)) {
        $nextdo = strtotime($nextdo);
        $nextdo2 = strtotime($nextdo2);
        if ($status) {
            $sql1 .= ", `nextdo` = '{$nextdo2}'";
            $sql2 .= " AND `nextdo` = '{$nextdo}'";
        } else {
            $sql1 .= "`nextdo` = '{$nextdo2}'";
            $sql2 .= "`nextdo` = '{$nextdo}'";
        }
        $status = true;
    }
    if ((!empty($date) || $date == '0')
        && (!empty($date2) || $date2 == '0')) {
        if ($status) {
            $sql1 .= ", `date` = '{$date2}'";
            $sql2 .= " AND `date` = '{$date}'";
        } else {
            $sql1 .= "`date` = '{$date2}'";
            $sql2 .= "`date` = '{$date}'";
        }
        $status = true;
    }
    if (!empty($day) && !empty($day2)) {
        if ($status) {
            $sql1 .= ", `day` = '{$day2}'";
            $sql2 .= " AND `day` = '{$day}'";
        } else {
            $sql1 .= "`day` = '{$day2}'";
            $sql2 .= "`day` = '{$day}'";
        }
        $status = true;
    }
    $prefix = "id=tb.";
    $suffix1 = "&";
    $suffix2 = "?";

    if ($portrait2_count == 1) {
        if (!empty($portrait2)) {
            $portrait_status = false;
            if ($status) {
                $sql1_cache = $sql1 . " AND `portrait` = '{$portrait2[0]}'";
            } else {
                $sql1_cache = $sql1 . "`portrait` = '{$portrait2[0]}'";
            }
            if ($status) {
                $sql2_cache = $sql2 . " AND (";
            } else {
                $sql2_cache = $sql2 . "(";
            }
            for ($ii = 0; $ii < count($portrait) - 1; $ii++) {
                $portrait_i = trim($portrait[$ii]);
                if (!empty($portrait_i)) {
                    if (strpos($portrait_i, $prefix)) {
                        $portrait_i = "tb." . explode($prefix, $portrait_i)[1];
                    }
                    if (strpos($portrait_i, $suffix1)) {
                        $portrait_i = explode($suffix1, $portrait_i)[0];
                    }
                    if (strpos($portrait_i, $suffix2)) {
                        $portrait_i = explode($suffix2, $portrait_i)[0];
                    }
                    if (!empty($portrait_i)) {
                        $portrait_status = true;
                        $status = true;
                        $sql2_cache .= "`portrait` = '{$portrait_i}' OR ";
                    }
                    //            if(strlen($portrait_i)=="")
                }
            }
            if (!empty($portrait[count($portrait) - 1])) {
                $portrait_status = true;
                $status = true;
                $sql2_cache .= "`portrait` = '{$portrait[count($portrait) - 1]}')";
            }
            if ($portrait_status) {
                $sql1 = $sql1_cache;
                $sql2 = $sql2_cache;
            }
        }
    } else {
        $i_count = 0;
        foreach ($portrait as $portrait_i) {
            $portrait_i = trim($portrait_i);
            if (!empty($portrait_i)) {
                if (strpos($portrait_i, $prefix)) {
                    $portrait_i = "tb." . explode($prefix, $portrait_i)[1];
                }
                if (strpos($portrait_i, $suffix1)) {
                    $portrait_i = explode($suffix1, $portrait_i)[0];
                }
                if (strpos($portrait_i, $suffix2)) {
                    $portrait_i = explode($suffix2, $portrait_i)[0];
                }
                if (!empty($portrait_i) && !empty($portrait2)) {
                    if ($status) {
                        $sql1_cache = $sql1 . ", `portrait` = '{$portrait2[$i_count]}'";
                        $sql2_cache = $sql2 . " AND `portrait` = '{$portrait_i}'";
                    } else {
                        $sql1_cache = $sql1 . "`portrait` = '{$portrait2[$i_count]}'";
                        $sql2_cache = $sql2 . "`portrait` = '{$portrait_i}'";
                    }
                    $sql = $sql1_cache . $sql2_cache;
                    $m->query($sql);
                }
            }
            $i_count += 1;
        }
        $status = false;
    }
    $sql = $sql1 . $sql2;
    if ($status) {
        $m->query($sql);
    }
    ReDirect(SYSTEM_URL . 'index.php?plugin=wmzz_ban&ok' . '#' . $anchor);
} elseif
(SYSTEM_PAGE == 'del') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : msg('缺少ID');
    $anchor = addslashes(strip_tags($_GET['anchor']));
    $m->query("DELETE FROM `" . DB_PREFIX . "wmzz_ban` WHERE `uid` = " . UID . " AND `id` = " . $id);
    ReDirect(SYSTEM_URL . 'index.php?plugin=wmzz_ban&ok' . '#' . $anchor);
} else {
    loadhead();
    require SYSTEM_ROOT . '/plugins/wmzz_ban/show.php';
    loadfoot();
}

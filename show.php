<?php if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}
global $m, $i;
$is_open = option::uget('wmzz_ban_enable', UID);
if (!$is_open) {
    option::uset('wmzz_ban_enable', 'on', UID);
    $is_open = true;
} else {
    if ($is_open == "on") {
        $is_open = true;
    } else if ($is_open == "off") {
        $is_open = false;
    }
}

if (isset($_GET['ok'])) {
    echo '<div class="alert alert-success">设置保存成功</div>';
}

$msg = option::uget("wmzz_ban_msg", UID) ? option::uget("wmzz_ban_msg", UID) : '';
$tieba = option::uget("wmzz_ban_tieba", UID) ? option::uget("wmzz_ban_tieba", UID) : '';
$day = option::uget("wmzz_ban_day", UID) ? option::uget("wmzz_ban_day", UID) : '';
$pid = option::uget("wmzz_ban_pid", UID) ? option::uget("wmzz_ban_pid", UID) : '';
$pid_checked = array_key_exists($pid, $i['user']['bduss']) ? $pid : '';

$day_list = [
    '1',
    '3',
    '10',
];

?>

<script type="text/javascript">
    $(function () {
        $('.banuser').modal("hide");
        $('.edituser').modal("hide");
        $('.batchedit').modal("hide");
    });

    const day_list = [
        <?php foreach ($day_list as $day_i) {
        echo '"' . $day_i . '",';
    } ?>
    ];

    function ban_values() {
        const prefix = "banuser_";

        const portrait_html_all = "被封禁人portrait<br/><br/>1.可添加多个<br/><br/>用回车分隔<br/><br/>2.支持直接粘贴<br/><br/>用户主页的url<br/><br/>可以自动提取portrait";
        document.getElementById(prefix + "pid_info").innerHTML = "选择封禁发起人百度账号";
        document.getElementById(prefix + "banlistspan").innerHTML = portrait_html_all;

        document.getElementById(prefix + "pid").innerHTML =
            "<?php
                if (empty($pid_checked)) {
                    echo '<option value=\"' . $pid_checked . '\" selected hidden>' . $pid_checked . '</option>';
                } else {
                    echo '<option value=\"' . $pid_checked . '\" selected hidden>' . $i['user']['baidu'][$pid_checked] . '</option>';

                }
                foreach ($i['user']['bduss'] as $keyyy => $valueee) {
                    echo '<option value=\"' . $keyyy . '\">' . $i['user']['baidu'][$keyyy] . '</option>';
                }
                ?>";
        document.getElementById(prefix + "tieba").setAttribute("value", "<?php echo $tieba;  ?>");
        document.getElementById(prefix + "msg").setAttribute("value", "<?php echo $msg;  ?>");
        document.getElementById(prefix + "portrait").innerHTML = "";
        document.getElementById(prefix + "date").setAttribute("value", "0");
        document.getElementById(prefix + "day").innerHTML =<?php
        echo '"<option value=\"' . $day . '\" selected hidden>' . $day . '</option>'; ?>
        <?php foreach ($day_list as $day_i) {
            echo '<option value=\"' . $day_i . '\">' . $day_i . '</option>';
        }
        echo "\"";
        ?>;
    }

    function edit_values(id) {
        const prefix = "edituser_";

        const args = document.getElementById(id).getElementsByTagName("td");
        const names = document.getElementById("bantable").getElementsByTagName("thead")[0]
            .getElementsByTagName("tr")[0].getElementsByTagName("th");
        const pid_array = {
            <?php
            foreach ($i['user']['bduss'] as $keyyy => $valueee) {
                echo '"' . $keyyy . '": "' . $i['user']['baidu'][$keyyy] . '",';
            }
            ?>
        };
        for (let i = 0; i < args.length; i++) {
            if (args[i].id === "use") {
                if (names[i].id.trim() === "date" && args[i].innerHTML.trim() === "永久") {
                    document.getElementById(prefix + names[i].id.trim()).setAttribute("value", "0");
                } else if (names[i].id.trim() === "portrait") {
                    document.getElementById(prefix + names[i].id.trim()).innerText = args[i].innerText.trim();
                } else if (names[i].id.trim() === "day") {
                    let day_html = "";
                    for (let ii = 0; ii < day_list.length; ii++) {
                        if (args[i].innerHTML.trim() === day_list[ii]) {
                            day_html += "<option selected value=\"" + day_list[ii]
                                + "\">" + day_list[ii] + "</option>";
                        } else {
                            day_html += "<option value=\"" + day_list[ii]
                                + "\">" + day_list[ii] + "</option>";
                        }
                    }
                    document.getElementById(prefix + names[i].id.trim()).innerHTML = day_html;
                } else if (names[i].id.trim() === "pid") {
                    let pid_html = "";
                    const pid_cache = args[i].getElementsByTagName("input")[0].getAttribute("value");
                    if (pid_array.hasOwnProperty(pid_cache)) {
                        document.getElementById(prefix + "pid_info").innerHTML = "选择封禁发起人百度账号";
                        pid_html = "<option value=\"" + pid_cache + "\" selected hidden>" + pid_array[pid_cache] + "</option>";
                    } else {
                        document.getElementById(prefix + "pid_info").innerHTML = "选择封禁发起人百度账号<br/>（原pid已失效，请重新选择）";
                        pid_html = "<option value=\"" + "\" selected hidden>" + "</option>";
                    }
                    <?php
                    foreach ($i['user']['bduss'] as $keyyy => $valueee) {
                        echo 'pid_html += "<option value=\"' . $keyyy . '\">' . $i['user']['baidu'][$keyyy] . '</option>";';
                    }
                    ?>
                    document.getElementById(prefix + names[i].id.trim()).innerHTML = pid_html;
                } else {
                    document.getElementById(prefix + names[i].id.trim()).setAttribute("value", args[i].innerHTML.trim());
                }
            }
        }
    }

    function batch_values() {
        const prefix = "batchedit_";

        let pid_html = "<option value=\"" + "\" selected hidden>" + "</option>";
        <?php
        foreach ($i['user']['bduss'] as $keyyy => $valueee) {
            echo 'pid_html += "<option value=\"' . $keyyy . '\">' . $i['user']['baidu'][$keyyy] . '</option>";';
        }
        ?>
        document.getElementById(prefix + "pid_list").innerHTML = pid_html;
        document.getElementById(prefix + "pid2").innerHTML = pid_html;

        let day_html = "<option value=\"" + "\" selected hidden>" + "</option>";
        <?php
        foreach ($day_list as $day_i) {
            echo 'day_html += "<option value=\"' . $day_i . '\">' . $day_i . '</option>";';
        }
        ?>
        document.getElementById(prefix + "day_list").innerHTML = day_html;
        document.getElementById(prefix + "day2").innerHTML = day_html;

    }

    function save_event(id, id2) {
        $('#' + id2).attr('disabled', true);
        $('#' + id2).text('正在保存');
        const args = document.getElementById(id).getElementsByTagName("tr");

        let data = {};
        for (let i = 0; i < args.length; i++) {
            if (args[i].id.trim() === "wmzz_ban_enable") {
                let is_open = true;
                const tds = args[i].getElementsByTagName("td")
                for (let ii = 0; ii < tds.length; ii++) {
                    if (tds[ii].id.trim() === "values") {
                        const radios = tds[ii].getElementsByTagName("input");
                        for (let iii = 0; iii < radios.length; iii++) {
                            if (radios[iii].name === "wmzz_ban_enable"
                                && radios[iii].checked
                            ) {
                                is_open = radios[iii].value;
                            }
                        }
                    }
                }

                data["wmzz_ban_enable"] = is_open;
            }
        }
        $.ajax({
            url: 'index.php?plugin=wmzz_ban&mod=save',
            type: 'POST',
            dataType: 'json',
            data: {
                info: JSON.stringify(data),
            },
            success: function (result) {
                switch (result.code) {
                    case 1:
                        document.getElementById("head").innerHTML =
                            '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + result.msg + '</div>';
                        break;
                    case 0:
                        document.getElementById("head").innerHTML =
                            '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + result.msg + '</div>';
                        break;
                    default:
                        alert('请求异常!请刷新页面后重试');
                        break;
                }
                $('#' + id2).attr('disabled', false);
                $('#' + id2).text('保存设定');
            },
            error: function () {
                alert('网络异常!请刷新页面后重试');
                $('#' + id2).attr('disabled', false);
                $('#' + id2).text('保存设定');
            }
        });
    }
</script>

<div id="head"></div>

<h2>贴吧循环封禁</h2>

<br/>

<table class="table table-striped" id="user_settings">
    <tr id="wmzz_ban_enable">
        <td>是否开启循环封禁</td>
        <td id="values">
            <input type="radio" name="wmzz_ban_enable"
                   value="on" <?php echo $is_open ? 'checked' : ''; ?> >开启<br/>
            <input type="radio" name="wmzz_ban_enable"
                   value="off" <?php echo $is_open ? '' : 'checked'; ?> >关闭

        </td>
    </tr>
    <tr>
        <td>
            <a id="save_button" style="float: right"
               onclick="save_event('user_settings','save_button')"
               href="javascript:void(0)" class="btn btn-primary">
                保存设定
            </a>
        </td>
    </tr>
</table>

<!-- NAVI -->
<ul class="nav nav-tabs" id="PageTab">
    <li class="active"><a href="#page_list" data-toggle="tab"
                          onclick="$('#page_list').css('display','');$('#page_config').css('display','none');">封禁列表</a>
    </li>
    <li><a href="#page_config" data-toggle="tab"
           onclick="$('#page_list').css('display','none');$('#page_config').css('display','');">插件配置</a>
    </li>
</ul>
<br>
<!-- END NAVI -->

<!-- PAGE1: page_list-->
<div class="tab-pane fade in active" id="page_list">
    <a name="#page_list"></a>

    <input type="button" data-toggle="modal" data-target="#banuser"
           onclick="ban_values()"
           class="btn btn-info btn-lg" value="+ 增加封禁"
           style="float:right;">
    <input type="button" data-toggle="modal" data-target="#batchedit"
           onclick="batch_values()"
           class="btn btn-info btn-lg" value="* 批量/搜索 修改"
           style="float:right;">

    以下为循环封禁列表，若要增加新的封禁，请点击右侧的 增加封禁 按钮
    <br/><br/>
    <table class="table table-striped" id="bantable">
        <thead>
        <tr>
            <th id="id">ID</th>
            <th id="pid">百度账号</th>
            <th id="tieba">贴吧</th>
            <th id="portrait">被封禁人portrait</th>
            <th id="msg">封禁原因</th>
            <th id="date">截止日期</th>
            <th id="nextdo">下次封禁</th>
            <th id="day">每次封禁天数</th>
            <th id="edituser_button">修改</th>
            <th id="deluser_button">删除</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $x = $m->query("SELECT * FROM `" . DB_PREFIX . "wmzz_ban` WHERE `uid` = " . UID);
        while ($v = $m->fetch_array($x)) {
            ?>
            <tr id="baneduser<?php echo $v['id'] ?>">
                <td id="use"><?php echo $v['id'] ?></td>
                <td id="use">
                    <input type="hidden" disabled value="<?php echo $v['pid'] ?>">
                    <?php echo $i['user']['baidu'][$v['pid']] ?>
                </td>
                <td id="use"><?php echo $v['tieba'] ?></td>
                <?php
                echo '<td id="use"><a href="http://tieba.baidu.com/home/main?id=' . $v['portrait'] . '&fr=pb' . '" target="_blank">' . $v['portrait'] . '</td>';
                ?>
                <td id="use"><?php echo $v['msg']; ?></td>
                <td id="use">
                    <?php if ($v['date'] == '0') {
                        echo '永久';
                    } else {
                        echo date('Y-m-d', $v['date']);
                    }
                    ?></td>
                <td id="use"><?php if (empty($v['nextdo'])) echo '即将';
                    else echo date('Y-m-d', $v['nextdo']); ?></td>
                <td id="use"><?php echo $v['day']; ?>
                </td>
                <td><a class="btn btn-default" data-toggle="modal" data-target="#edituser"
                       onclick="edit_values('baneduser<?php echo $v['id'] ?>')"
                       title="修改"><span class="glyphicon glyphicon-edit"></span> </a>
                    <br/><br/><a href="javascript:scroll(0,0)">返回顶部</a></td>
                <td><a class="btn btn-default"
                       href="index.php?plugin=wmzz_ban&mod=del&id=<?php echo $v['id'] ?>"
                       title="删除"><span class="glyphicon glyphicon-remove"></span> </a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>

<!-- END PAGE1 -->

<!-- PAGE2: page_config -->
<div class="tab-pane fade" id="page_config" style="display:none">
    <a name="#page_config"></a>
    <form action="index.php?plugin=wmzz_ban&mod=store" method="post">
        <table class="table table-striped">
            以下为循环封禁设置
            <br/>
            <br/>
            <b>先保存设定，再增加封禁</b>
            <br/><br/>
            <tr>
                <td>添加封禁时默认的封禁原因</td>
                <td><input type="text"
                           value="<?php echo $msg ?>"
                           name="msg" class="form-control">
                </td>
            </tr>
            <tr>
                <td>添加封禁时默认执行封禁的百度账号</td>
                <td><select name="pid" class="form-control" id="pid">
                        <option value=""></option><?php
                        foreach ($i['user']['bduss'] as $keyyy => $valueee) {
                            if ($keyyy == $pid) {
                                echo '<option selected value="' . $keyyy . '">' . $i['user']['baidu'][$keyyy] . '</option>';
                            } else {
                                echo '<option value="' . $keyyy . '">' . $i['user']['baidu'][$keyyy] . '</option>';
                            }
                        } ?></select></td>
            </tr>
            <tr>
                <td>添加封禁时默认选择的贴吧</td>
                <td><input type="text"
                           value="<?php echo $tieba ?>"
                           name="tieba" class="form-control"></td>
            </tr>
            <tr>
                <td>添加封禁时默认选择的每次封禁天数</td>
                <td>
                    <select name="day" class="form-control" id="day">
                        <?php
                        foreach ($day_list as $day_i) {
                            if ($day == $day_i) {
                                echo '<option value="' . $day_i . '" selected>' . $day_i . '</option>';
                            } else {
                                echo '<option value="' . $day_i . '">' . $day_i . '</option>';
                            }
                        }
                        ?>
                    </select></td>
            </tr>
        </table>
        <input type="hidden" name="anchor" id="anchor" value="page_config"/>
        <button type="submit" class="btn btn-success">保存设定</button>
    </form>
</div>

<!-- END PAGE2 -->

<div class="modal fade" id="banuser" tabindex="-1" role="dialog" aria-labelledby="banuser"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                </button>
                <h4 class="modal-title" id="banuser_title">添加循环封禁</h4>
            </div>
            <form id="banuser_form" action="index.php?plugin=wmzz_ban&mod=add" method="post">
                <div class="modal-body">
                    要操作的贴吧名称后面不要带 <b>吧</b><br/>封禁相关日期格式为 yyyy-mm-dd，<b>0</b>
                    表示永久封禁<br/>web端点开用户主页，url里的id参数就是portrait<br/>
                    <br/>
                    <div class="input-group">
                                        <span class="input-group-addon"
                                              id="banuser_pid_info">选择封禁发起人百度账号</span>
                        <select name="pid" class="form-control" id="banuser_pid">

                        </select>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">要操作的贴吧名称</span>
                        <input type="text" name="tieba" class="form-control" id="banuser_tieba"
                               value="">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">封禁原因</span>
                        <input type="text" name="msg" class="form-control" id="banuser_msg" value="">
                    </div>
                    <br/>
                    <div id="banuser_banlist">
                        <div class="input-group">
                                            <span class="input-group-addon"
                                                  id="banuser_banlistspan">被封禁人portrait<br/><br/>1.可添加多个<br/><br/>用回车分隔<br/><br/>2.支持直接粘贴<br/><br/>用户主页的url<br/><br/>可以自动提取portrait</span>
                            <textarea class="form-control" name="portrait" style="height:240px;"
                                      id="banuser_portrait"></textarea>
                            <!--                            <input type="text" name="portrait" class="form-control" id="banuser_portrait">-->
                        </div>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">封禁截止日期</span>
                        <input type="text" name="date" id="banuser_date" class="form-control" value="0">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">每次封禁天数</span>
                        <select name="day" id="banuser_day" class="form-control">

                        </select>
                    </div>
                </div>
                <input type="hidden" name="anchor" id="anchor" value="page_list"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary" id="runsql_button">提交更改</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="edituser" tabindex="-1" role="dialog" aria-labelledby="edituser"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                </button>
                <h4 class="modal-title" id="edituser_title">修改循环封禁</h4>
            </div>
            <form id="edituser_form" action="index.php?plugin=wmzz_ban&mod=update" method="post">
                <div class="modal-body">
                    要操作的贴吧名称后面不要带 <b>吧</b><br/>封禁相关日期格式为 yyyy-mm-dd，<b>0</b>
                    表示永久封禁<br/>web端点开用户主页，url里的id参数就是portrait<br/>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">ID</span>
                        <input type="text" name="id" class="form-control" id="edituser_id" value=""
                               readonly>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon" id="edituser_pid_info">选择封禁发起人百度账号</span>
                        <select name="pid" class="form-control" id="edituser_pid">

                        </select>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">要操作的贴吧名称</span>
                        <input type="text" name="tieba" class="form-control" id="edituser_tieba"
                               value="">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">封禁原因</span>
                        <input type="text" name="msg" class="form-control" id="edituser_msg" value="">
                    </div>
                    <br/>
                    <div id="edituser_banlist">
                        <div class="input-group">
                                            <span class="input-group-addon"
                                                  id="edituser_banlistspan">被封禁人portrait<br/><br/>1.支持直接粘贴<br/><br/>用户主页的url<br/><br/>可以自动提取portrait</span>
                            <textarea class="form-control" name="portrait" style="height:140px;"
                                      id="edituser_portrait"></textarea>
                        </div>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">封禁截止日期</span>
                        <input type="text" name="date" id="edituser_date" class="form-control"
                               value="0">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">下次封禁日期</span>
                        <input type="text" name="nextdo" id="edituser_nextdo" class="form-control"
                               value="">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">每次封禁天数</span>
                        <select name="day" id="edituser_day" class="form-control">

                        </select>
                    </div>
                </div>
                <input type="hidden" name="anchor" id="anchor" value="page_list"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary" id="runsql_button">提交更改</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="batchedit" tabindex="-1" role="dialog" aria-labelledby="batchedit"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                </button>
                <h4 class="modal-title" id="batchedit_title">批量修改循环封禁</h4>
            </div>
            <form id="batchedit_form" action="index.php?plugin=wmzz_ban&mod=batchedit" method="post">
                <div class="modal-body">
                    要操作的贴吧名称后面不要带 <b>吧</b><br/>封禁相关日期格式为 yyyy-mm-dd，<b>0</b>
                    表示永久封禁<br/>web端点开用户主页，url里的id参数就是portrait<br/>
                    <b>填了多个选项的情况下是判断条件都满足（条件和）</b>
                    <br/>
                    <b>修改被封禁人portrait支持一对一，多对一和多对多（相同数量）</b>
                    <br/>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon" id="batchedit_pid_info">要修改的封禁发起人百度账号<br/><br/>支持搜索，下拉框选择和手动输入的值</span>
                        <input name="pid" type="text" list="batchedit_pid_list" class="form-control"
                               id="batchedit_pid" value="">
                        <datalist id="batchedit_pid_list">

                        </datalist>
                        <select name="pid2" class="form-control" id="batchedit_pid2">

                        </select>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">要修改的贴吧名称</span>
                        <input type="text" name="tieba" class="form-control" id="batchedit_tieba"
                               value="">
                        <input type="text" name="tieba2" class="form-control" id="batchedit_tieba2"
                               value="">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">要修改的封禁原因</span>
                        <input type="text" name="msg" class="form-control" id="batchedit_msg" value="">
                        <input type="text" name="msg2" class="form-control" id="batchedit_msg2"
                               value="">
                    </div>
                    <br/>
                    <div id="batchedit_banlist">
                        <div class="input-group">
                            <span class="input-group-addon" id="banuser_banlistspan">要修改的被封禁人portrait<br/><br/>1.可添加多个，用回车分隔<br/><br/>2.支持直接粘贴用户主页的url<br/><br/>可以自动提取portrait</span>
                            <textarea class="form-control" name="portrait" style="height:60px;"
                                      id="batchedit_portrait"></textarea>
                            <textarea class="form-control" name="portrait2" style="height:60px;"
                                      id="batchedit_portrait2"></textarea>
                            <!--                            <input type="text" name="portrait" class="form-control" id="batchedit_portrait">-->
                        </div>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">封禁截止日期</span>
                        <input type="text" name="date" id="batchedit_date" class="form-control"
                               value="">
                        <input type="text" name="date2" id="batchedit_date2" class="form-control"
                               value="">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">下次封禁日期</span>
                        <input type="text" name="nextdo" id="batchedit_nextdo" class="form-control"
                               value="">
                        <input type="text" name="nextdo2" id="batchedit_nextdo2" class="form-control"
                               value="">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon">每次封禁天数</span>
                        <input name="day" type="text" list="batchedit_day_list" class="form-control"
                               id="batchedit_day" value="">
                        <datalist id="batchedit_day_list">

                        </datalist>
                        <select name="day2" class="form-control" id="batchedit_day2">

                        </select>
                    </div>
                </div>
                <input type="hidden" name="anchor" id="anchor" value="page_list"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary" id="runsql_button">提交更改</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<br/><br/>管理员设置的循环封禁理由：<?php echo $msg ?>
<br/><br/>管理员设置的默认封禁的贴吧：<?php echo $tieba ?>
<br/><br/>管理员设置的默认执行封禁的百度账号：<?php echo $i['user']['baidu'][$pid_checked] ?>
<br/><br/>作者：<a href="http://zhizhe8.net" target="_blank">无名智者</a>

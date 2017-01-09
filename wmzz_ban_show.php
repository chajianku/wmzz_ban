<?php if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); }  
global $i,$m;
$s = unserialize(option::get('plugin_wmzz_ban'));
if (SYSTEM_PAGE == 'add') {
	$pid   = !empty($_POST['pid']) ? intval($_POST['pid']) : msg('请选择PID');
	if (!isset($i['user']['bduss'][$pid])) {
		msg('PID不存在');
	}
	$tieba = !empty($_POST['tieba']) ? addslashes(strip_tags($_POST['tieba'])) : msg('请输入贴吧');
	if (isset($_POST['date'])) {
		if (empty($_POST['date'])) {
			$date = '0';
		} else {
			$date = strtotime($_POST['date']);
		}
	} else {
		msg('请输入截止日期');
	}
	$value = addslashes(strip_tags($_POST['user']));
	$tpid = $_POST['tpid'];
	$m->query("INSERT INTO `".DB_PREFIX."wmzz_ban` (`uid`, `pid`, `tieba`, `user`, `tpid` , `date`) VALUES ('".UID."', '{$pid}', '{$tieba}', '{$value}', '{$tpid}', '{$date}')");
	ReDirect(SYSTEM_URL . 'index.php?plugin=wmzz_ban&ok');
} elseif (SYSTEM_PAGE == 'del') {
	$id = isset($_GET['id']) ? intval($_GET['id']) : msg('缺少ID');
	$m->query("DELETE FROM `".DB_PREFIX."wmzz_ban` WHERE `uid` = ".UID." AND `id` = ".$id);
	ReDirect(SYSTEM_URL . 'index.php?plugin=wmzz_ban&ok');
} elseif(SYSTEM_PAGE == 'getpid'){
	$username = $_GET['user'];
	$tieba = $_GET['tieba'];
	$pid = $_GET['pid'];
	$option = array(
		'word' => mb_convert_encoding($tieba,'gbk','UTF-8'),
		'op_type' => '',
		'stype' => 'post_uname',
		'svalue' =>  mb_convert_encoding($username,'gbk','UTF-8'),
		'date_type' => 'on',
		'startTime' => '',
		'begin' => '',
		'endTime' => '',
		'end' => ''
	);
	$bduss = misc::getCookie($pid);
	$query = http_build_query($option);
	$c = new wcurl('http://tieba.baidu.com/bawu2/platform/listPostLog?'. $query);
	$c->addcookie('BDUSS='.$bduss);
	$html = $c->get();
	$html = mb_convert_encoding($html,'UTF-8','gbk');
	$preg ="/<article class=\"post_wrapper clearfix\"><div class=\"post_meta\"><div class=\"post_author\">.*?<h1><a target=\"_blank\" href=\".*?pid=(.*?)\".*?<\/article>/i";
	preg_match_all($preg, $html, $info);
	$pid = [];
	foreach ($info[1] as $value) {
		$pidarray = explode('#',$value);
		$pid[] = (float)$pidarray[1];
		$pid[] = (float)$pidarray[0];
	}
	$maxpid =  max($pid);
	if($maxpid > 100000000000){
		echo json_encode(array('status' => 'ok','pid'=>$maxpid));
		return;
	}
	echo json_encode(array('status' => 'error'));
}else {
	loadhead();
	require SYSTEM_ROOT.'/plugins/wmzz_ban/show.php';
	loadfoot(); 
} 

<?php
if (!defined('QA_VERSION')) { 
	require_once dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/../../qa-include/qa-base.php';
   require_once QA_INCLUDE_DIR.'app/emails.php';
}

// for local test START
/****************
qa_opt('q2a-get-roundnumber-celebrate-body', "本文です。N番目の投稿です。おめでとうございます。");
$obj = new q2a_get_roundnumber_celebrate_email_event();
$event = 'a_post';
$param['userid'] = 1589;
$obj->process_event($event, 1589, 'developer', 0, $param);
*************/
// for local test START
class q2a_get_roundnumber_celebrate_email_event
{
	function process_event ($event, $userid, $handle, $cookieid, $params)
	{
		$type = '';
		if ($event == 'q_post') {
			$type = 'Q';
		} elseif ($event == 'a_post') {
			$type = 'A';
		} else {
			return;
		}

		$LIMIT = 50;	// 閾値：キリ番

		$postcount = 0;
		$posts = $this->getPostCount($type);
		foreach($posts as $post){
			$postcount = $post["postcount"];
		}
// for debug START
/***************
$fp = fopen("/tmp/plugin04.log", "a+");
$outs = "--------------------------\n";
$outs .= "userid[" . $userid . "]\n";
$outs .= "postcount:".$postcount."\n";
fputs($fp, $outs);
fclose($fp);
*****************/
// for debug END
		if (($postcount % $LIMIT) == 0) {
			$user = $this->getUserInfo($userid);
			$handle = $user[0]['handle'];
			$email = $user[0]['email'];
			$title = "キリ番の投稿おめでとうございます。";
			$bodyTemplate = qa_opt('q2a-get-roundnumber-celebrate-body');
			$body = strtr($bodyTemplate, 
				array(
					'^username' => $handle,
					'^sitename' => qa_opt('site_title'),
					'^count' => $postcount
				)
			);
			$this->sendEmail($title, $body, $handle, $email);
		}
		return;
	}

	function sendEmail($title, $body, $toname, $toemail)
	{

		$mail_params['fromemail'] = qa_opt('from_email');
		$mail_params['fromname'] = qa_opt('site_title');
		$mail_params['subject'] = '【' . qa_opt('site_title') . '】' . $title;
		$mail_params['body'] = $body;
		$mail_params['toname'] = $toname;
		$mail_params['toemail'] = $toemail;
		$mail_params['html'] = false;
// for debug START
/*****************
$fp = fopen("/tmp/plugin04.log", "a+");
$outs = "fromemail:".$mail_params['fromemail']."\n";
fputs($fp, $outs);
$outs = "fromname:".$mail_params['fromname'] . "\n";
fputs($fp, $outs);
$outs = "subject:".$mail_params['subject'] . "\n";
fputs($fp, $outs);
$outs = "body:".$mail_params['body'] . "\n";
fputs($fp, $outs);
$outs = "toname:".$mail_params['toname'] . "\n";
fputs($fp, $outs);
$outs = "toemail:".$mail_params['toemail'] . "\n";
fputs($fp, $outs);
fclose($fp);
*****************/
// for debug END
		qa_send_email($mail_params);

		$mail_params['toemail'] = 'yuichi.shiga@gmail.com';
		//$mail_params['toemail'] = 'ryuta_takeyama@nexyzbb.ne.jp';
		qa_send_email($mail_params);
	}

	function getPostCount($type)
	{
		$sql = "select count(postid) as postcount from qa_posts";
		$sql .= " where type='" . $type . "'";
		$result = qa_db_query_sub($sql); 
		return qa_db_read_all_assoc($result);
	}

	function getUserInfo($userid)
	{
		$sql = 'select email,handle from qa_users where userid=' . $userid;
		$result = qa_db_query_sub($sql);
		return qa_db_read_all_assoc($result);
	}
}

/*
    Omit PHP closing tag to help avoid accidental output
*/

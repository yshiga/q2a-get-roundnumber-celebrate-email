<?php
if (!defined('QA_VERSION')) {
	require_once dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/../../qa-include/qa-base.php';
   require_once QA_INCLUDE_DIR.'app/emails.php';
}

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

		if (($postcount % $LIMIT) == 0) {
			$user = $this->getUserInfo($userid);
			$handle = $user[0]['handle'];
			$email = $user[0]['email'];
			$bodyTemplate = qa_opt('q2a-get-roundnumber-celebrate-body');

                       	$typeStr = '';
			if ($type == 'Q') {
                       		$typeStr = '質問';
                       	} else {
                              $typeStr = '回答';
                      	}

			$title = "キリ番!" . $postcount ."目の" . $typeStr . "ありがとうございます";

			$body = strtr($bodyTemplate,
				array(
					'^username' => $handle,
					'^sitename' => qa_opt('site_title'),
					'^sitename' => qa_opt('site_url'),
					'^count' => $postcount,
					'^type' => $typeStr
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
		qa_send_email($mail_params);

		// for debug
		$mail_params['toemail'] = 'yuichi.shiga@gmail.com';
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

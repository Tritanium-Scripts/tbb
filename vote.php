<?

/* vote.php - Stimmt bei einem Poll ab (c) 2001-2002 Tritanium Scripts */

require_once('auth.php');

if(isset($edit)) {
	header("Location: index.php?faction=editpoll&forum_id=$forum_id&topic_id=$topic_id&poll_id=$poll_id&$HSID");
	exit;
}

if(!$poll_file = myfile("polls/$poll_id-1.xbb")) die("Error loading poll data!");

$poll_data = myexplode($poll_file[0]);
$poll_where = explode(',',$poll_data[5]);
$forum_data = get_forum_data($forum_id,$poll_where[0]);
$poll_voters = myfile("polls/$poll_id-2.xbb"); $poll_voters = explode(',',$poll_voters[0]);

$right = 0;
if($user_logged_in != 1) {
	if($forum_data['rights'][6] == 1) $right = 1;
	else {
		include("pageheader.php");
		echo navbar($lng['templates']['forum_nli'][0]);
		echo get_message('forum_nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
	}
}
else {
	if(check_right($forum_id,0) != 1) {
		include("pageheader.php");
		echo navbar($lng['templates']['forum_na'][0]);
		echo get_message('forum_na');
	}
	else $right = 1;
}

if($right == 1) {
	$temp_var = "session_poll_$poll_id";
	$temp_var2 = "cookie_poll_$poll_id";

	if($poll_data[0] > 2) {
		include("pageheader.php");
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\"".$topic_data[1]."</a>\t".$lng['templates']['poll_closed'][0]);
		echo get_message('poll_closed');
	}
	elseif($user_logged_in != 1 && $poll_data[0] != 1) {
		include("pageheader.php");
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\"".$topic_data[1]."</a>\t".$lng['templates']['mbli_to_vote'][0]);
		echo get_message('mbli_to_vote');
	}
	elseif(($user_logged_in == 1 && in_array($user_id,$poll_voters)) || isset($$temp_var) || isset($$temp_var2)) {
		include("pageheader.php");
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\"".$topic_data[1]."</a>\t".$lng['templates']['already_voted'][0]);
		echo get_message('already_voted');
	}
	else {
		for($i = 1; $i < sizeof($poll_file); $i++) {
			$akt_poll = myexplode($poll_file[$i]);
			if($akt_poll[0] == $vote_id) {
				$poll_data[4]++;
				$akt_poll[2]++;
				$poll_file[$i] = myimplode($akt_poll);
				$poll_file[0] = myimplode($poll_data);
				myfwrite("polls/$poll_id-1.xbb",$poll_file,"w");
				if($user_logged_in == 1) {
					if($poll_voters[0] == '') $poll_voters[0] = $user_id;
					else $poll_voters[] = $user_id;
					myfwrite("polls/$poll_id-2.xbb",implode(',',$poll_voters),'w');
				}
				$$temp_var = 1;
				session_register($temp_var); rank_topic($forum_id,$topic_id);
				setcookie("cookie_poll_$poll_id",1,time()+(3600*24*365),$config['path_to_forum']);
				header("Location: index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id&HSID"); exit;
			}
		}
	}
}

?>
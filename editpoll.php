<?

/* editpoll.php - Eine Umfrage bearbeiten (c) 2001-2002 Tritanium Scripts */

require_once('auth.php');

if(!$poll_file = myfile("polls/$poll_id-1.xbb")) die('Error loading poll data!');

$poll_data = myexplode($poll_file[0]);
$poll_where = explode(',',$poll_data[5]);
$forum_data = get_forum_data($poll_where[0]);
$is_mod = test_mod($poll_where[0]);

if($user_logged_in != 1) {
	include('pageheader.php');
	echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_data[id]$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_data[id]&thread=$poll_where[1]$MYSID2\">".get_thread_name($forum_data['id'],$poll_where[1])."</a>\t".$lng['templates']['nli'][0]);
	echo get_message('nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
}
elseif(check_right($poll_where[0],5) != 1) {
	include('pageheader.php');
	echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_data[id]$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_data[id]&thread=$poll_where[1]$MYSID2\">".get_thread_name($forum_data['id'],$poll_where[1])."</a>\t".$lng['templates']['na'][0]);
	echo get_message('na');
}
elseif($poll_data[1] != $user_id && $is_mod != 1 && $user_data['status'] != 1) {
	include('pageheader.php');
	echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_data[id]$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_data[id]&thread=$poll_where[1]$MYSID2\">".get_thread_name($forum_data['id'],$poll_where[1])."</a>\t".$lng['templates']['na'][0]);
	echo get_message('na');
}
else {
	switch($mode) {
		default:
			include('pageheader.php');
			echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_data[id]$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_data[id]&thread=$poll_where[1]$MYSID2\">".get_thread_name($forum_data['id'],$poll_where[1])."</a>\tUmfrage bearbeiten");
			?>
				<form method="post" action="index.php?faction=editpoll&forum_id=<?=$forum_id?>&tpoic_id=<?=$topic_id?>&poll_id=<?=$poll_id?>&mode=update<?=$MYSID2?>">
				<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
				<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['editpoll']['Edit_Poll']?></span></th></tr>
				<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['editpoll']['Question']?></span></td></tr>
				<tr><td class="td1"><span class="norm"><b><?=$poll_data[3]?></b></span></td></tr>
				<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['editpoll']['Choices']?></span></td></tr>
				<tr><td class="td1"><span class="norm">
			<?
			for($i = 1; $i < sizeof($poll_file); $i++) {
				$akt_poll = myexplode($poll_file[$i]);
				echo $i.": <input type=\"text\" name=\"poll_choice[$akt_poll[0]]\" value=\"$akt_poll[1]\"><br>";
			}
			?>
				</span></td></tr></table><br><input type="submit" name="update" value="<?=$lng['editpoll']['Update']?>">&nbsp;&nbsp;&nbsp;
			<?
			if($poll_data[0] > 2) echo "<input type=\"submit\" name=\"open\" value=\"".$lng['editpoll']['Open_Poll']."\">"; else echo "<input type=\"submit\" name=\"close\" value=\"".$lng['editpoll']['Close_Poll']."\">";
			echo "</form><br>";
		break;

		case 'update':
			if(isset($open)) {
				$save = 0;
				if($poll_data[0] == 3) {
					$poll_data[0] = 1;
					$save = 1;
				}
				elseif($poll_data[0] == 4) {
					$poll_data[0] = 2;
					$save = 1;
				}
				if($save == 1) {
					$poll_file[0] = myimplode($poll_data);
					myfwrite("polls/$poll_id-1.xbb",$poll_file,'w');
					header("Location: index.php?faction=editpoll&poll_id=$poll_id&forum_id=$forum_id&topic_id=$topic_id$HSID"); exit;
				}
			}
			elseif(isset($close)) {
				$save = 0;
				if($poll_data[0] == 1) {
					$poll_data[0] = 3;
					$save = 1;
				}
				elseif($poll_data[0] == 2) {
					$poll_data[0] = 4;
					$save = 1;
				}
				if($save == 1) {
					$poll_file[0] = myimplode($poll_data);
					myfwrite("polls/$poll_id-1.xbb",$poll_file,'w');
					header("Location: index.php?faction=editpoll&poll_id=$poll_id&forum_id=$forum_id&topic_id=$topic_id$HSID"); exit;
				}
			}
			else {
				for($i = 1; $i < sizeof($poll_file); $i++) {
					$akt_poll = myexplode($poll_file[$i]);
					if(isset($poll_choice[$akt_poll[0]])) {
						$akt_poll[1] = mutate($poll_choice[$akt_poll[0]]);
						$poll_file[$i] = myimplode($akt_poll);
					}
				}
				myfwrite("polls/$poll_id-1.xbb",$poll_file,'w');
				include('pageheader.php');
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_data[id]$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_data[id]&thread=$poll_where[1]$MYSID2\">".get_thread_name($forum_data['id'],$poll_where[1])."</a>\t".$lng['templates']['poll_edited'][1]);
				echo get_message('poll_edited');
			}
		break;
	}
}

include('pagetail.php');
// S
?>
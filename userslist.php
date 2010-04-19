<?php
/**
*
* Tritanium Bulletin Board 2 - userslist.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');


$users_per_page = isset($_REQUEST['users_per_page']) ? intval($_REQUEST['users_per_page']) : 20;
$sort_type = isset($_REQUEST['sort_type']) ? $_REQUEST['sort_type'] : 'id';
$order_type = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'ASC';
$z = isset($_GET['z']) ? $_GET['z'] : '1';


//
// Stellt sicher, dass keine ungueltigen Werte uebergeben wurden
//
if(in_array($sort_type,array('id','nick','rank','posts')) == FALSE)
	$sort_type = 'id';
if(in_array($order_type,array('ASC','DESC')) == FALSE)
	$order_type = 'DESC';


//
// Die Seitenanzeige
//
$db->query("SELECT COUNT(*) AS users_counter FROM ".TBLPFX."users");
list($users_counter) = $db->fetch_array();

$page_counter = ceil($users_counter/$users_per_page);

if($page_counter == 0) $z = 1;
elseif($z == 'last' || $z > $page_counter) $z = $page_counter;


$start = $z*$users_per_page-$users_per_page;
$page_listing = array();

$pre = $suf = '';

if($page_counter > 0) {
	if($page_counter > 5) {
		if($z > 2 && $z < $page_counter-2) {
			$page_listing = array($z-2,$z-1,$z,$z+1,$z+2);
		}
		elseif($z <= 2) {
			$page_listing = array(1,2,3,4,5);
		}
		elseif($z >= $page_counter-2) {
			$page_listing = array($page_counter-4,$page_counter-3,$page_counter-2,$page_counter-1,$page_counter);
		}
	}
	else {
		for($i = 1; $i < $page_counter+1; $i++) {
			$page_listing[] = $i;
		}
	}
}
else $page_listing[] = 1;
for($i = 0; $i < count($page_listing); $i++) {
	if($page_listing[$i] != $z) $page_listing[$i] = "<a href=\"index.php?faction=userslist&amp;sort_type=$sort_type&amp;order_type=$order_type&amp;users_per_page=$users_per_page&amp;z=".$page_listing[$i]."&amp;$MYSID\">".$page_listing[$i].'</a>';
}


if($z > 1) $pre = '<a href="index.php?faction=userslist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z=1&amp;'.$MYSID.'">'.$lng['First_page'].'</a>&nbsp;<a href="index.php?faction=userslist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z='.($z-1).'&amp;'.$MYSID.'">'.$lng['Previous_page'].'</a>&nbsp;&nbsp;';
if($z < $page_counter) $suf = '&nbsp;&nbsp;<a href="index.php?faction=userslist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z='.($z+1).'&amp;'.$MYSID.'">'.$lng['Next_page'].'</a>&nbsp;<a href="index.php?faction=userslist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z=last&amp;'.$MYSID.'">'.$lng['Last_page'].'</a>';

$page_listing = sprintf($lng['Pages'],$pre.implode(' | ',$page_listing).$suf);


//
// Bestimmt, was spaeter in der Liste ausgewaehlt ist
//
$c = ' selected="selected"';
$checked = array(
	'sort_type_id'=>($sort_type == 'id') ? $c : '',
	'sort_type_nick'=>($sort_type == 'nick') ? $c : '',
	'sort_type_rank'=>($sort_type == 'rank') ? $c : '',
	'sort_type_posts'=>($sort_type == 'posts') ? $c : '',
	'order_type_desc'=>($order_type == 'DESC') ? $c : '',
	'order_type_asc'=>($order_type == 'ASC') ? $c : ''
);


//
// Template laden
//
$userslist_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['userslist']);


//
// Aufsteigend oder absteigend...
//
$query_order = '';
if($order_type == 'ASC') $query_order = 'ASC';
else $query_order = 'DESC';


//
// Nach welchem Aspekt sortiert werden soll...
//
$query_sort = '';
if($sort_type == 'id') $query_sort = 't1.user_id';
elseif($sort_type == 'nick') $query_sort = 't1.user_nick';
elseif($sort_type == 'rank') $query_sort = "t1.user_is_admin $query_order,t1.user_is_supermod $query_order,t1.user_rank_id $query_order,t1.user_posts";
else $query_sort = 't1.user_posts';


//
// Daten laden und ausgeben
//
$db->query("SELECT t1.user_id,t1.user_nick,t1.user_email,t1.user_posts,t1.user_is_admin,t1.user_is_supermod,t2.rank_name AS user_rank FROM ".TBLPFX."users AS t1 LEFT JOIN ".TBLPFX."ranks AS t2 ON t1.user_rank_id=t2.rank_id ORDER BY $query_sort $query_order LIMIT $start,$users_per_page");
while($akt_user = $db->fetch_array()) {
	$akt_user_rank = '';
	if($akt_user['user_is_admin'] == 1) $akt_user_rank = $lng['Administrator'];
	elseif($akt_user['user_is_supermod'] == 1) $akt_user_rank = $lng['Supermoderator'];
	elseif($akt_user['user_rank'] != '') $akt_user_rank = $akt_user['user_rank'];
	$userslist_tpl->blocks['userrow']->parse_code(FALSE,TRUE);
}


add_navbar_items(array($lng['Member_list'],''));

//
// Seite ausgeben
//
include_once('pheader.php');
show_navbar();
$userslist_tpl->parse_code(TRUE);
include_once('ptail.php');

?>
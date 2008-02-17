<?php
/**
*
* Tritanium Bulletin Board 2 - memberlist.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
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
$DB->query("SELECT COUNT(*) AS users_counter FROM ".TBLPFX."users");
list($users_counter) = $DB->fetch_array();

$page_listing = create_page_listing($users_counter,$users_per_page,$z,'<a href="index.php?faction=memberlist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z=%1$s&amp;'.$MYSID.'">%2$s</a>');
$start = $z*$users_per_page-$users_per_page;


//
// Template laden
//
$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['memberlist']);


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
// Rangdaten laden
//
$RANKS_DATA = cache_get_ranks_data();


//
// User-IDs aller Moderatoren laden
//
$mod_ids = array();
$DB->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE auth_type='0' AND auth_is_mod='1' GROUP BY auth_id");
while(list($cur_user_id) = $DB->fetch_array())
	$mod_ids[] = $cur_user_id;

$DB->query("SELECT t2.member_id FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."groups_members AS t2 WHERE t1.auth_is_mod=1 AND t1.auth_type=1 AND t2.group_id=t1.auth_id GROUP BY t2.member_id");
while(list($cur_user_id) = $DB->fetch_array())
	$mod_ids[] = $cur_user_id;

$mod_ids = array_unique($mod_ids);


//
// Die Daten der Profilfelder laden, die in der Mitgliederliste zusaetzlich angezeigt werden sollen
//
$DB->query("SELECT * FROM ".TBLPFX."profile_fields WHERE field_show_memberlist='1'");
$fields_data = $DB->raw2array();


//
// Die Titel fuer die Profilfelder, gleichzeitig noch die IDs der Felder bestimmen
//
$field_ids = array();
foreach($fields_data AS $cur_field) {
	$field_ids[] = $cur_field['field_id'];
	$tpl->blocks['fieldrow']->parse_code(FALSE,TRUE);
}


//
// Mitgliederdaten laden
//
$DB->query("SELECT t1.user_id,t1.user_nick,t1.user_email,t1.user_posts,t1.user_is_admin,t1.user_is_supermod,t2.rank_name AS user_rank FROM ".TBLPFX."users AS t1 LEFT JOIN ".TBLPFX."ranks AS t2 ON t1.user_rank_id=t2.rank_id ORDER BY $query_sort $query_order LIMIT $start,$users_per_page");
$users_data = $DB->raw2array();


//
// Mitglieder-IDs bestimmen
//
$user_ids = array();
foreach($users_data AS $cur_user)
	$user_ids[] = $cur_user['user_id'];


//
// Die Mitgliederdaten der extra-Profilfelder laden
//
$DB->query("SELECT user_id,field_id,field_value FROM ".TBLPFX."profile_fields_data WHERE user_id IN ('".implode("','",$user_ids)."') AND field_id IN ('".implode("','",$field_ids)."')");
$fields_values = $DB->raw2array();


//
// Daten ausgeben
//
foreach($users_data AS $cur_user) {
	$tpl->blocks['userrow']->blocks['fieldrow']->reset_tpl();
	$akt_cell_class = $TCONFIG['cell_classes']['start_class'];

	$cur_user_rank = '';
	if($cur_user['user_is_admin'] == 1) $cur_user_rank = $LNG['Administrator'];
	elseif($cur_user['user_is_supermod'] == 1) $cur_user_rank = $LNG['Supermoderator'];
	elseif(in_array($cur_user['user_id'],$mod_ids) == TRUE) $cur_user_rank = $LNG['Moderator'];
	elseif($cur_user['user_rank'] != '') $cur_user_rank = $cur_user['user_rank'];
	else {
		while(list(,$cur_rank) = each($RANKS_DATA[0])) { // Die Rangliste durchlaufen
			if($cur_rank['rank_posts'] > $cur_user['user_posts']) break;

			$cur_user_rank = $cur_rank['rank_name']; // ...den Namen das Rangs verwenden...
			//$cur_poster_rank_pic = $cur_rank['rank_gfx']; // ...und das Bild des Rangs verwenden
		}
		reset($RANKS_DATA[0]); // Das Array fuer den naechsten User vorbereiten
	}



	//

	// Die extra-Profilefelder
	//
	foreach($fields_data AS $cur_field) {
		$cur_field_value = '';
		while(list($cur_key,$cur_value) = each($fields_values)) {
			if($cur_value['user_id'] != $cur_user['user_id'] || $cur_value['field_id'] != $cur_field['field_id']) continue;
			$cur_field_value = $cur_value['field_value'];
			unset($fields_values[$cur_key]);
			break;
		}

		if($cur_field_value != '') $cur_field_value = sprintf($cur_field['field_link'],$cur_field_value);

		$tpl->blocks['userrow']->blocks['fieldrow']->parse_code(FALSE,TRUE);
		$akt_cell_class = ($akt_cell_class == $TCONFIG['cell_classes']['td1_class']) ? $TCONFIG['cell_classes']['td2_class'] : $TCONFIG['cell_classes']['td1_class'];
	}

	$tpl->blocks['userrow']->parse_code(FALSE,TRUE);
}


add_navbar_items(array($LNG['Memberlist'],''));

//
// Seite ausgeben
//
include_once('pheader.php');
$tpl->parse_code(TRUE);
include_once('ptail.php');

?>
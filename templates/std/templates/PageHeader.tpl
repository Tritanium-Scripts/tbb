<?xml version="1.0" encoding="{$modules.Language->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$modules.Language->getString('html_direction')}" lang="{$modules.Language->getString('html_language')}" xml:lang="{$modules.Language->getString('html_language')}">
<head>
	<title>{$modules.Navbar->parseElements(0)}</title>
	<meta name="author" content="Tritanium Scripts"/>
	<meta name="copyright" content="Tritanium Scripts"/>
	{*<meta name="keywords" content="{$modules.Navbar->parseElements(0)}"/>
	<meta name="description" content="{$modules.Navbar->parseElements(0)}"/>*}
	<meta name="robots" content="all"/>
	<meta name="revisit-after" content="7 days"/>
	<meta http-equiv="Content-Language" content="{$modules.Language->getString('html_language')}"/>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$modules.Language->getString('html_encoding')}"/>
	<meta http-equiv="Content-Style-Type" content="text/css"/>
	<meta http-equiv="Content-Script-Type" content="text/javascript"/>
	<link rel="stylesheet" media="all" href="{$modules.Template->getTD()}/styles/ts_tbb2_standard.css"/>
	<link rel="shortcut icon" type="image/x-icon" href="{$modules.Template->getTD()}/images/favicon.ico"/>
	<script type="text/javascript">
		{literal}var djConfig = {
			parseOnLoad:true
		};{/literal}
	</script>
	<script src="{$modules.Template->getTD()}/scripts/dojo/dojo.js" type="text/javascript"></script>
	<script src="{$modules.Template->getTD()}/scripts/jscripts.js" type="text/javascript"></script>
	<script src="{$modules.Template->getTD()}/scripts/ajax.js" type="text/javascript"></script>
	<script src="{$modules.Template->getTD()}/scripts/posting.js" type="text/javascript"></script>
	{if $newPrivateMessageReceived}
		<script type="text/javascript">
			popUp('{$smarty.const.INDEXFILE}?action=PrivateMessages&mode=NewPMReceived&inPopup=1&{$smarty.const.MYSID}','newpmreceived',400,200);
		</script>
	{/if}
</head>
<body>

<div id="MainBox">

<div id="HeaderBox">
	<div id="HeaderInnerBox">
		<img src="{$modules.Template->getTD()}/images/logo.jpg" alt=""/>
		<div id="HeaderNavigationBox">
			{if $modules.Auth->isLoggedIn() == 1}
				<a href="{$smarty.const.INDEXFILE}?action=EditProfile&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/myprofile.png" class="ImageButton" alt="{$modules.Language->getString('my_profile')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=ViewHelp&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/help.png" class="ImageButton" alt="{$modules.Language->getString('help')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/pms.png" class="ImageButton" alt="{$modules.Language->getString('private_messages')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=Search&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/search.png" class="ImageButton" alt="{$modules.Language->getString('search')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=MemberList&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/memberlist.png" class="ImageButton" alt="{$modules.Language->getString('memberlist')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=WhoIsOnline&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/whoisonline.png" class="ImageButton" alt="{$modules.Language->getString('who_is_online')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=Logout&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/logout.png" class="ImageButton" alt="{$modules.Language->getString('logout')}"/></a>
			{else}
				<a href="{$smarty.const.INDEXFILE}?action=Register&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/register.png" class="ImageButton" alt="{$modules.Language->getString('register')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=ViewHelp&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/help.png" class="ImageButton" alt="{$modules.Language->getString('help')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=Search&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/search.png" class="ImageButton" alt="{$modules.Language->getString('search')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=MemberList&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/memberlist.png" class="ImageButton" alt="{$modules.Language->getString('memberlist')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=WhoIsOnline&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/whoisonline.png" class="ImageButton" alt="{$modules.Language->getString('who_is_online')}"/></a>
				<a href="{$smarty.const.INDEXFILE}?action=Login&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/de/login.png" class="ImageButton" alt="{$modules.Language->getString('login')}"/></a>
			{/if}
		</div>
	</div>
</div>

<div id="HeaderInfoBox">
	<img src="{$modules.Template->getTemplateDir()}/images/icons/Info.png" class="ImageIcon" alt=""/>{$welcomeText}
</div>

{include file=_Navbar.tpl}

<br/>
<?php
/**
*
* Tritanium Bulletin Board 2 - bbcode.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

$bbcode_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['bbcode_html']);

function bbcode($text) {
	global $LNG,$TCONFIG,$TEMPLATE_PATH;

	while(preg_match("/\[quote\](.*?)\[\/quote\]/si",$text))
		$text = preg_replace_callback("/\[quote\](.*?)\[\/quote\][\r\n]*/si",'bbcode_callback_quote',$text); // [quote]xxx[/quote]
	while(preg_match("/\[quote=(.*?)\](.*?)\[\/quote\]/si",$text))
		$text = preg_replace_callback("/\[quote=(.*?)\](.*?)\[\/quote\][\r\n]*/si",'bbcode_callback_quote',$text); // [quote="xxx"]xxx[/quote]

	$text = preg_replace_callback("/\[code\](.*?)\[\/code\]/si",'bbcode_callback_code',$text); // [code]xxx[/code]
	$text = preg_replace_callback("/\[b\](.*?)\[\/b\]/si",'bbcode_callback_bold',$text); // [b]xxx[/b]
	$text = preg_replace_callback("/\[i\](.*?)\[\/i\]/si",'bbcode_callback_italic',$text); // [i]xxx[/i]
	$text = preg_replace_callback("/\[u\](.*?)\[\/u\]/si",'bbcode_callback_underline',$text); // [u]xxx[/u]
	$text = preg_replace_callback("/\[s\](.*?)\[\/s\]/si",'bbcode_callback_strike',$text); // [s]xxx[/s]
	$text = preg_replace_callback("/\[center\](.*?)\[\/center\]/si",'bbcode_callback_center',$text); // [center]xxx[/center]
	$text = preg_replace_callback("/\[email\](.*?)\[\/email\]/si",'bbcode_callback_email',$text); // [email]xxx[/email]
	$text = preg_replace_callback("/\[img\](.*?)\[\/img\]/si",'bbcode_callback_image',$text); // [img]xxx[/img]
	$text = preg_replace_callback("/\[url\](.*?)\[\/url\]/si",'bbcode_callback_link',$text); // [url]xxx[/url]
	$text = preg_replace_callback("/\[url=(.*?)\](.*?)\[\/url\]/si",'bbcode_callback_link',$text); // [url=xxx]xxx[/url]
	$text = preg_replace_callback("/\[color=([a-zA-Z0-9#]*)\](.*?)\[\/color\]/si",'bbcode_callback_color',$text); // [url=xxx]xxx[/url]


	return $text;

}

function bbcode_callback_quote($elements) {
	global $bbcode_tpl,$LNG;

	if(count($elements) == 3) {
		$quotetext = $elements[2];
		$quotetitle = sprintf($LNG['Quote_by_x'],$elements[1]);
	}
	else {
		$quotetext = $elements[1];
		$quotetitle = $LNG['Quote'];
	}

	$bbcode_tpl->blocks['quote']->values = array(
		'QUOTETEXT'=>$quotetext,
		'QUOTETITLE'=>$quotetitle
	);

	return $bbcode_tpl->blocks['quote']->parse_code();
}

function bbcode_callback_code($elements) {
	global $bbcode_tpl,$LNG;

	$codetext = br2nl($elements[1]); // <br> und <br /> und neue zeilen (\n) umwandeln, da nur das im Textfeld neue Zeilen erzeugt

	$lines_counter = count(explode("\n",$codetext)); // Anzahl der Zeilen
	$lines = '';

	for($i = 1; $i <= $lines_counter; $i++)
		$lines .= $i."\n";

	$height = 150;
	if($lines_counter > 12) $height += $lines_counter*3;

	$bbcode_tpl->blocks['code']->values = array(
		'CODETEXT'=>$codetext,
		'LNG_CODE'=>$LNG['Code'],
		'LINES'=>$lines,
		'HEIGHT'=>$height
	);

	return $bbcode_tpl->blocks['code']->parse_code();
}

function bbcode_callback_bold($elements) {
	global $bbcode_tpl;

	$bbcode_tpl->blocks['bold']->values = array(
		'BOLDTEXT'=>$elements[1]
	);

	return $bbcode_tpl->blocks['bold']->parse_code();
}

function bbcode_callback_italic($elements) {
	global $bbcode_tpl;

	$bbcode_tpl->blocks['italic']->values = array(
		'ITALICTEXT'=>$elements[1]
	);

	return $bbcode_tpl->blocks['italic']->parse_code();
}

function bbcode_callback_underline($elements) {
	global $bbcode_tpl;

	$bbcode_tpl->blocks['underline']->values = array(
		'UNDERLINETEXT'=>$elements[1]
	);

	return $bbcode_tpl->blocks['underline']->parse_code();
}

function bbcode_callback_strike($elements) {
	global $bbcode_tpl;

	$bbcode_tpl->blocks['strike']->values = array(
		'STRIKETEXT'=>$elements[1]
	);

	return $bbcode_tpl->blocks['strike']->parse_code();
}

function bbcode_callback_center($elements) {
	global $bbcode_tpl;

	$bbcode_tpl->blocks['center']->values = array(
		'CENTERTEXT'=>$elements[1]
	);

	return $bbcode_tpl->blocks['center']->parse_code();
}

function bbcode_callback_email($elements) {
	global $bbcode_tpl;

	$bbcode_tpl->blocks['email']->values = array(
		'EMAILADDRESS'=>$elements[1]
	);

	return $bbcode_tpl->blocks['email']->parse_code();
}

function bbcode_callback_image($elements) {
	global $bbcode_tpl;

	if(substr($elements[1],0,11) == 'javascript:') return $elements[0];

	$bbcode_tpl->blocks['image']->values = array(
		'IMAGEADDRESS'=>$elements[1]
	);

	return $bbcode_tpl->blocks['image']->parse_code();
}


function bbcode_callback_link($elements) {
	global $bbcode_tpl;

	if(substr($elements[1],0,11) == 'javascript:') return $elements[0];

	if(count($elements) == 3) {
		$linkaddress = $elements[1];
		$linktext = $elements[2];
	}
	else {
		$linkaddress = $linktext = $elements[1];
	}

	$linkaddress = addhttp($linkaddress);

	$bbcode_tpl->blocks['link']->values = array(
		'LINKADDRESS'=>$linkaddress,
		'LINKTEXT'=>$linktext
	);

	return $bbcode_tpl->blocks['link']->parse_code();
}

function bbcode_callback_color($elements) {
	global $bbcode_tpl;

	$bbcode_tpl->blocks['color']->values = array(
		'COLORCODE'=>$elements[1],
		'COLORTEXT'=>$elements[2]
	);

	return $bbcode_tpl->blocks['color']->parse_code();
}

?>
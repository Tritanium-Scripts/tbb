<?php

class BBCode extends ModuleTemplate {
	protected $requiredModules = array(
		'Cache',
		'Language',
		'Template'
	);

	public function format($text, $enableHTMLCode=FALSE, $enableSmilies=TRUE, $enableBBCode=TRUE) {
		if(!$enableHTMLCode) $text = Functions::HTMLSpecialChars($text);
		if($enableBBCode && (stristr($text, '[code]') || stristr($text, '[php]'))) {
			// Um zu verhindern, dass &quot;) in den Zwinker-Smilie umgewandelt wird, ist etwas mehr Aufwand noetig. Zunaechst werden erstmal alle [php] und [code] Tags gesucht...
			preg_match_all("/\[(code|php)\].*?\[\/\\1\]/si", $text, $codephp);
			// ...und das Ergebnis in $codephp gespeichert. Hier wird nur der erste Eintrag benoetigt...
			$codephp = array_shift($codephp);
			// ...welcher dann abgearbeitet wird. Alle Code Tags werden so durch einen Platzhalter [codephp]x[/codephp] ersetzt.
			foreach($codephp as $key => $value)
				$text = preg_replace('/' . preg_quote($value, '/#') . '/', '[codephp]' . $key . '[/codephp]', $text);
			// Danach koennen erstmal Smilies etc. geparst werden.
		}
		if($enableSmilies) $text = strtr($text, $this->modules['Cache']->getSmiliesData('write'));
		$text = nl2br($text);
		if(isset($codephp))
			// Wurde Code zwischengespeichert, so muss dieser nach allen anderen Parsevorgaengen wieder eingesetzt werden, anhand der Platzhalter.
			foreach($codephp as $key => $value)
				$text = preg_replace("/\[codephp\]$key\[\/codephp\]/si", $value, $text);
			// Jetzt kann der Code ansich geparst werden, ohne verfaelscht zu werden. :)
		if($enableBBCode) $text = $this->parse($text);
		return $text;
	}

	protected function parse($text) {
		$text = preg_replace_callback("/\[code\](.*?)\[\/code\]/si",array($this,'cbCode'),$text); // [code]xxx[/code]
		$text = preg_replace_callback("/\[code=(\d*?)\](.*?)\[\/code\]/si",array($this,'cbCode'),$text); // [code=integer]xxx[/code]
		$text = preg_replace_callback("/\[php\](.*?)\[\/php\]/si", array($this, 'cbPHP'), $text); //[php]xxx[/php]
		$text = preg_replace_callback("/\[php=(\d*?)\](.*?)\[\/php\]/si", array($this, 'cbPHP'), $text); //[php=integer]xxx[/php]
		$text = preg_replace_callback("/\[list\](.*?)\[\/list\]/si", array($this, 'cbList'), $text); //[list][*]xxx[/list]
		$text = preg_replace_callback("/\[b\](.*?)\[\/b\]/si",array($this,'cbBold'),$text); // [b]xxx[/b]
		$text = preg_replace_callback("/\[i\](.*?)\[\/i\]/si",array($this,'cbItalic'),$text); // [i]xxx[/i]
		$text = preg_replace_callback("/\[u\](.*?)\[\/u\]/si",array($this,'cbUnderline'),$text); // [u]xxx[/u]
		$text = preg_replace_callback("/\[s\](.*?)\[\/s\]/si",array($this,'cbStrike'),$text); // [s]xxx[/s]
		$text = preg_replace_callback("/\[center\](.*?)\[\/center\]/si",array($this,'cbCenter'),$text); // [center]xxx[/center]
		$text = preg_replace_callback("/\[email\](.*?)\[\/email\]/si",array($this,'cbEmail'),$text); // [email]xxx[/email]
		$text = preg_replace_callback("/\[img\](.*?)\[\/img\]/si",array($this,'cbImage'),$text); // [img]xxx[/img]
		$text = preg_replace_callback("/\[img=(.*?)\](.*?)\[\/img\]/si", array($this,'cbImage'), $text); //[img=xxx]xxx[/img]
		$text = preg_replace_callback("/\[url\](.*?)\[\/url\]/si",array($this,'cbLink'),$text); // [url]xxx[/url]
		$text = preg_replace_callback("/\[url=(.*?)\](.*?)\[\/url\]/si",array($this,'cbLink'),$text); // [url=xxx]xxx[/url]
		$text = preg_replace_callback("/\[color=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/color\]/si",array($this,'cbColor'),$text); // [color=xxx]xxx[/color]
		$text = preg_replace("/\[marquee\](.*?)\[\/marquee\]/si", '<marquee>\1</marquee>', $text); //[marquee]xxx[/marquee] - Es wird keine Option geboten per Button eine Laufschrift zu erstellen (nicht HTML konform), parsen es aber trotzdem weil das TBB1 dies anbot
		//TBB1 BBCode hack support
		$text = preg_replace_callback("/\[size=(\-[12]{1}|\+[1-4]{1})\](.*?)\[\/size\]/si", array($this, 'cbSize'), $text); //[size=xxx]xxx[/size]
		$text = preg_replace_callback("/\[glow=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/glow\]/si", array($this, 'cbGlow'), $text); //[glow=xxx]xxx[/glow]
		$text = preg_replace_callback("/\[shadow=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/shadow\]/si", array($this, 'cbShadow'), $text); //[shadow=xxx]xxx[/shadow]
		$text = preg_replace_callback("/\[flash\](.*?)\[\/flash\]/si", array($this, 'cbFlash'), $text); //[flash]xxx[/flash]
		$text = preg_replace_callback("/\[flash[=| ](\d+),(\d+)\](.*?)\[\/flash\]/si", array($this, 'cbFlash'), $text); //[flash=xxx]xxx[/flash] oder [flash xxx]xxx[/flash]

		//Zitate am Ende damit URLs als Quellenangaben funktionieren
		while(preg_match("/\[quote\](.*?)\[\/quote\]/si",$text))
			$text = preg_replace_callback("/\[quote\](.*?)\[\/quote\][\r\n]*/si",array($this,'cbQuote'),$text); // [quote]xxx[/quote]
		while(preg_match("/\[quote=(.*?)\](.*?)\[\/quote\]/si",$text))
			$text = preg_replace_callback("/\[quote=(.*?)\](.*?)\[\/quote\][\r\n]*/si",array($this,'cbQuote'),$text); // [quote="xxx"]xxx[/quote]

		return $text;
	}

	protected function cbQuote($elements) {
		if(count($elements) == 3) {
			$quoteText = $elements[2];
			$quoteTitle = sprintf($this->modules['Language']->getString('Quote_by_x'),$elements[1]);
		}
		else {
			$quoteText = $elements[1];
			$quoteTitle = $this->modules['Language']->getString('Quote');
		}

		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_QUOTE,
			'quoteText'=>$quoteText,
			'quoteTitle'=>$quoteTitle
		));

		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbCode($elements) {
		if(isset($elements[2])) {
			$codeLines = Functions::str_replace('<br />','',explode("\n",$elements[2])); //Functions::HTMLSpecialChars($elements[1]);
			$startLine = $elements[1]-1;
		} else {
			$codeLines = Functions::str_replace('<br />','',explode("\n",$elements[1])); //Functions::HTMLSpecialChars($elements[1]);
			$startLine = 0;
		}

		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_CODE,
			'codeLines'=>$codeLines,
			'startLine'=>$startLine
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbPHP($elements) {
		if(isset($elements[2])) {
			$codeLines = Functions::str_replace('<br />', '', explode("\n", htmlspecialchars_decode($elements[2])));
			$startLine = $elements[1]-1;
		} else {
			$codeLines = Functions::str_replace('<br />', '', explode("\n", htmlspecialchars_decode($elements[1])));
			$startLine = 0;
		}
		//Highlight PHP Syntax + Nacharbeit
		foreach($codeLines as &$codeLine)
			$codeLine = Functions::str_replace(array('<code>', '</code>', "\n", "\r"), '', (preg_match("/<\?[php]?/si", $codeLine)) ? highlight_string($codeLine, true) : preg_replace("/&lt;\?php/si", '', highlight_string('<?php' . $codeLine, true)));

		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_CODE,
			'codeLines'=>$codeLines,
			'startLine'=>$startLine
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbList($elements) {
		$listElements = preg_split("/<br \/>\r\n\[\*\](.*?)/si", $elements[1], -1, PREG_SPLIT_NO_EMPTY);

		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_LIST,
			'listElements'=>$listElements
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbBold($elements) {
		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_BOLD,
			'boldText'=>$elements[1]
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbItalic($elements) {
		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_ITALIC,
			'italicText'=>$elements[1]
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbUnderline($elements) {
		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_UNDERLINE,
			'underlineText'=>$elements[1]
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbStrike($elements) {
		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_STRIKE,
			'strikeText'=>$elements[1]
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbCenter($elements) {
		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_CENTER,
			'centerText'=>$elements[1]
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbEmail($elements) {
		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_EMAIL,
			'emailAddress'=>$elements[1]
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbImage($elements) {
		if(Functions::substr($elements[1],0,11) == 'javascript:') return $elements[0];

		$this->modules['Template']->assign('b',array(        
			'bbCodeType'=>BBCODE_IMAGE,
			'imageAddress'=>$elements[1],
			'imageText'=>count($elements) == 3 ? $elements[2] : ''
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbLink($elements) {
		if(Functions::substr($elements[1],0,11) == 'javascript:') return $elements[0];

		if(count($elements) == 3) {
			$linkAddress = $elements[1];
			$linkText = $elements[2];
		}
		else {
			$linkAddress = $linkText = $elements[1];
		}

		$linkAddress = Functions::addHttp($linkAddress);

		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_LINK,
			'linkAddress'=>$linkAddress,
			'linkText'=>$linkText
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbColor($elements) {
		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_COLOR,
			'colorCode'=>$elements[1],
			'colorText'=>$elements[2]
		));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	//TBB1 BBCode hack support
	protected function cbSize($elements) {
		//convert relative <font size="xx"> to absolute CSS
		switch($elements[1]) {
			case '+4': $elements[1] = '300%'; break;
			case '+3': $elements[1] = 'xx-large'; break;
			case '+2': $elements[1] = 'x-large'; break;
			case '+1': $elements[1] = 'large'; break;
			case '-1': $elements[1] = 'x-small'; break;
			case '-2': $elements[1] = 'xx-small'; break;
		}
		$this->modules['Template']->assign('b', array(
			'bbCodeType'=>BBCODE_SIZE,
			'sizeFont'=>$elements[1],
			'sizeText'=>$elements[2]
			));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbGlow($elements) {
		$this->modules['Template']->assign('b', array(
			'bbCodeType'=>BBCODE_GLOW,
			'glowColor'=>$elements[1],
			'glowText'=>$elements[2]
			));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbShadow($elements) {
		$this->modules['Template']->assign('b', array(
			'bbCodeType'=>BBCODE_SHADOW,
			'shadowColor'=>$elements[1],
			'shadowText'=>$elements[2]
			));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}

	protected function cbFlash($elements) {
		if(count($elements) == 4) {
			$flashWidth = $elements[1];
			$flashHeight = $elements[2];
			$flashLink = $elements[3];
		}
		else {
			$flashWidth = 425; //standard YouTube sizes
			$flashHeight = 355;
			$flashLink = $elements[1];
		}
		$this->modules['Template']->assign('b', array(
			'bbCodeType'=>BBCODE_FLASH,
			'flashWidth'=>$flashWidth,
			'flashHeight'=>$flashHeight,
			'flashLink'=>$flashLink
			));
		return $this->modules['Template']->fetch('BBCodeHtml.tpl');
	}
}
?>
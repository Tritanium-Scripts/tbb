<?php

class BBCode extends ModuleTemplate {
	protected $requiredModules = array(
		'Language',
		'Template'
	);

	public function parse($text) {
		$text = preg_replace_callback("/\[code\](.*?)\[\/code\]/si",array($this,'cbCode'),$text); // [code]xxx[/code]
		$text = preg_replace_callback("/\[b\](.*?)\[\/b\]/si",array($this,'cbBold'),$text); // [b]xxx[/b]
		$text = preg_replace_callback("/\[i\](.*?)\[\/i\]/si",array($this,'cbItalic'),$text); // [i]xxx[/i]
		$text = preg_replace_callback("/\[u\](.*?)\[\/u\]/si",array($this,'cbUnderline'),$text); // [u]xxx[/u]
		$text = preg_replace_callback("/\[s\](.*?)\[\/s\]/si",array($this,'cbStrike'),$text); // [s]xxx[/s]
		$text = preg_replace_callback("/\[center\](.*?)\[\/center\]/si",array($this,'cbCenter'),$text); // [center]xxx[/center]
		$text = preg_replace_callback("/\[email\](.*?)\[\/email\]/si",array($this,'cbEmail'),$text); // [email]xxx[/email]
		$text = preg_replace_callback("/\[img\](.*?)\[\/img\]/si",array($this,'cbImage'),$text); // [img]xxx[/img]
		$text = preg_replace_callback("/\[url\](.*?)\[\/url\]/si",array($this,'cbLink'),$text); // [url]xxx[/url]
		$text = preg_replace_callback("/\[url=(.*?)\](.*?)\[\/url\]/si",array($this,'cbLink'),$text); // [url=xxx]xxx[/url]
		$text = preg_replace_callback("/\[color=([a-zA-Z0-9#]*)\](.*?)\[\/color\]/si",array($this,'cbColor'),$text); // [color=xxx]xxx[/color]
		$text = preg_replace("/\[marquee\](.*?)\[\/marquee\]/si", '<marquee>\1</marquee>', $text); //[marquee]xxx[/marquee] - Es wird keine Option geboten per Button eine Laufschrift zu erstellen (nicht HTML konform), parsen es aber trotzdem weil das TBB1 dies anbot
		//TBB1 BBCode hack support
		$text = preg_replace_callback("/\[size=(\-[1-2]|\+[1-4])\](.*?)\[\/size\]/si", array($this, 'cbSize'), $text); //[size=xxx]xxx[/size]

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
		$codeText = Functions::br2nl($elements[1]); // <br> und <br /> und neue zeilen (\n) umwandeln, da nur das im Textfeld neue Zeilen erzeugt

		$linesCounter = substr_count($codeText,"\n")+1; // Anzahl der Zeilen
		$lines = '';

		for($i = 1; $i <= $linesCounter; $i++)
			$lines .= $i."\n";

		$height = 150;
		if($linesCounter > 12) $height += $linesCounter*3;

		$this->modules['Template']->assign('b',array(
			'bbCodeType'=>BBCODE_CODE,
			'codeText'=>$codeText,
			'lines'=>$lines,
			'height'=>$height
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
			'imageAddress'=>$elements[1]
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
}

?>
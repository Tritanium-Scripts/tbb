<?php

class Navbar extends ModuleTemplate {
	protected $elements = array();
	protected $seperator = '&nbsp;&#187;&nbsp;';
	protected $rightArea = '';

	public function addElement($elementName, $elementLink = '') {
		$this->elements[] = array($elementName,$elementLink);
	}

	public function addElements() {
		$newElementsCounter = func_num_args();

		for($i = 0; $i < $newElementsCounter; $i++) {
			$curArg = func_get_arg($i);
			$this->addElement($curArg[0],$curArg[1]);
		}
	}

	public function setRightArea($newRightArea) {
		$this->rightArea = $newRightArea;
	}

	public function getRightArea() {
		return $this->rightArea;
	}

	public function addCategories($catID,$includeSelf = TRUE) {
		$catsData = Functions::catsGetParentCatsData($catID,$includeSelf);

		foreach($catsData AS $curCat)
			$this->addElement(Functions::HTMLSpecialChars($curCat['catName']),INDEXFILE.'?catID='.$curCat['catID'].'&amp;'.MYSID);
	}

	public function parseElements($integrateLinks = TRUE) {
		$elementsCounter = count($this->elements);

		if($elementsCounter == 0) return '';

		$result = '';

		if($integrateLinks) {
			for($i = 0; $i < $elementsCounter-1; $i++)
				$result .= '<a href="'.$this->elements[$i][1].'">'.$this->elements[$i][0].'</a>'.$this->seperator;

			$result .= $this->elements[$elementsCounter-1][0];
		}
		else {
			for($i = 0; $i < $elementsCounter-1; $i++)
				$result .= $this->elements[$i][0].$this->seperator;
			$result .= $this->elements[$elementsCounter-1][0];
		}

		return $result;
	}

	public function setSeperator($newSeperator) {
		$this->seperator = $newSeperator;
	}
}

?>
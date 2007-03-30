<?php

class Navbar extends ModuleTemplate {
	protected $Elements = array();
	protected $Seperator = '&nbsp;&#187;&nbsp;';
	protected $RightArea = '';

	public function addElement($ElementName, $ElementLink = '') {
		$this->Elements[] = array($ElementName,$ElementLink);
	}

	public function addElements() {
		$NewElementsCounter = func_num_args();

		for($i = 0; $i < $NewElementsCounter; $i++) {
			$curArg = func_get_arg($i);
			$this->addElement($curArg[0],$curArg[1]);
		}
	}

	public function setRightArea($newRightArea) {
		$this->RightArea = $newRightArea;
	}

	public function getRightArea() {
		return $this->RightArea;
	}

	public function addCategories($CatID,$IncludeSelf = TRUE) {
		$CatsData = Functions::catsGetParentCatsData($CatID,$IncludeSelf);

		foreach($CatsData AS $curCat)
			$this->addElement(Functions::HTMLSpecialChars($curCat['CatName']),INDEXFILE.'?CatID='.$curCat['CatID'].'&amp;'.MYSID);
	}

	public function parseElements($IntegrateLinks = TRUE) {
		$ElementsCounter = count($this->Elements);

		if($ElementsCounter == 0) return '';

		$Result = '';

		if($IntegrateLinks == TRUE) {
			for($i = 0; $i < $ElementsCounter-1; $i++)
				$Result .= '<a href="'.$this->Elements[$i][1].'">'.$this->Elements[$i][0].'</a>'.$this->Seperator;

			$Result .= $this->Elements[$ElementsCounter-1][0];
		}
		else {
			for($i = 0; $i < $ElementsCounter-1; $i++)
				$Result .= $this->Elements[$i][0].$this->Seperator;
			$Result .= $this->Elements[$ElementsCounter-1][0];
		}

		return $Result;
	}

	public function setSeperator($NewSeperator) {
		$this->Seperator = $NewSeperator;
	}
}

?>
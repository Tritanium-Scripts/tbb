<?php
/**
*
* Tritanium Bulletin Board 2 - classes/navbar.class.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

class NavbarArea {
	var $area_name = '';
	var $show_last_link = FALSE;
	var $elements = array();
	var $element_seperator = '';
	var $parsed_string = '';
	var $elements_counter = 0;

	function NavbarArea($area_name,$element_seperator,$show_last_link = FALSE) {
		$this->area_name = $area_name;
		$this->show_last_link = $show_last_link;
		$this->element_seperator = $element_seperator;
	}

	function setShowLastLink($show_last_link) {
		$this->show_last_link = $show_last_link;
	}

	function setElementSeperator($element_seperator) {
		$this->element_seperator = $element_seperator;
	}

	function addElement($element) {
		$this->elements[] = $element;
		$this->elements_counter++;
	}

	function parseElements($use_links = TRUE) {
		$this->parsed_string = '';

		if($this->elements_counter == 0) return '';

		if($use_links == TRUE) {
			for($i = 0; $i < $this->elements_counter-1; $i++)
				$this->parsed_string .= '<a href="'.$this->elements[$i][1].'">'.$this->elements[$i][0].'</a>'.$this->element_seperator;

			if($this->show_last_link == FALSE) $this->parsed_string .= $this->elements[$this->elements_counter-1][0];
			else $this->parsed_string .= '<a href="'.$this->elements[$i][1].'">'.$this->elements[$i][0].'</a>';
		}
		else {
			for($i = 0; $i < $this->elements_counter-1; $i++)
				$this->parsed_string .= $this->elements[$i][0].$this->element_seperator;
			$this->parsed_string .= $this->elements[$this->elements_counter-1][0];
		}

		return $this->parsed_string;
	}
}

class Navbar {
	var $areas = array();

	function createArea($name,$element_seperator,$show_last_link = FALSE) {
		if(isset($this->areas[$name])) return FALSE;
		$this->areas[$name] = new NavbarArea($name,$element_seperator,$show_last_link);
		return TRUE;
	}

	function addElements($area_name) {
		if(!isset($this->areas[$area_name])) return FALSE;

		$new_items_counter = func_num_args();

		if($new_items_counter-1 > 0) {
			for($i = 1; $i < $new_items_counter; $i++)
				$this->areas[$area_name]->addElement(func_get_arg($i));
		}
	}

	function getArea($area_name) {
		if(isset($this->areas[$area_name]) == TRUE) return $this->areas[$area_name];
		return FALSE;
	}

	function parseAreas() {
	}
}

?>
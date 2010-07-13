<?php
/**
 * Manages elements of the navigation bar.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class NavBar
{
	/**
	 * Current added elements of navbar.
	 *
	 * @var array Contains navbar elements
	 */
	private $elements = array();

	/**
	 * Adds new element pair(s).
	 *
	 * @param string|array $name Name of navbar element or multiple element pairs with name and optional link (that means array(array(), array()) at least).
	 * @param string $link Optional link of navbar element
	 */
	public function addElement($name, $link=null)
	{
		if(is_array($name))
			foreach($name as $curElement)
				$this->elements[] = array($curElement[0], isset($curElement[1]) ? $curElement[1] : null);
		else
			$this->elements[] = array($name, $link);
	}

	/**
	 * Returns all navbar elements.
	 *
	 * @return NavBar elements
	 */
	public function getNavBar()
	{
		return $this->elements;
	}
}
?>
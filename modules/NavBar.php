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
	* Adds a new element pair.
	*
	* @param string $name Name of navbar element
	* @param string $link Optional link of navbar element
	*/
	public function addElement($name, $link=null)
	{
		$this->elements[] = array(utf8_encode($name), $link);
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
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
	 * Adds new element triple(s).
	 *
	 * @param string|array $name Name (and only the name!) of navbar element or multiple element pairs with name and optional link (that means array(array(), array()) at least).
	 * @param string $link Optional link for single navbar name element
	 * @param string $extra Optional stuff a name element should have apart from the name itself, e.g. linked pages
	 */
	public function addElement($name, $link='', $extra='')
	{
		if(is_array($name))
			foreach($name as $curElement)
				$this->elements[] = array($curElement[0], !empty($curElement[1]) ? $curElement[1] : '', !empty($curElement[2]) ? $curElement[2] : '');
		else
			$this->elements[] = array($name, $link, $extra);
	}

	/**
	 * Returns all navbar elements.
	 *
	 * @param bool $isLinked Return elements as name+extra/link pairs - The last one is never linked except root!
	 * @return array NavBar elements without URLs
	 */
	public function getNavBar($isLinked=true)
	{
		return array_map(create_function('$element', 'return ' . ($isLinked ? 'array($element[0] . $element[2], $element[1])' : '$element[0]') . ';'), $this->elements);
	}
}
?>
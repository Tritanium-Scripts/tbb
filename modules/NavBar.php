<?php
/**
 * Manages elements of the navigation bar.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class NavBar
{
    use Singleton;

    /**
     * Current added elements of nav bar.
     *
     * @var array Contains nav bar elements
     */
    private array $elements = [];

    /**
     * Adds new element triple(s).
     *
     * @param string|array $name Name (and only the name!) of navbar element or multiple element pairs with name and optional link (that means [[], []] at least).
     * @param string $link Optional link for single nav bar name element
     * @param string $extra Optional stuff a name element should have apart from the name itself, e.g. linked pages
     */
    public function addElement($name, string $link='', string $extra=''): void
    {
        PlugIns::getInstance()->callHook(PlugIns::HOOK_NAV_BAR_ADD_ELEMENT, $name, $link, $extra);
        if(is_array($name))
            foreach($name as $curElement)
                $this->elements[] = [$curElement[0], !empty($curElement[1]) ? $curElement[1] : '', !empty($curElement[2]) ? $curElement[2] : ''];
        else
            $this->elements[] = [$name, $link, $extra];
    }

    /**
     * Returns all nav bar elements.
     *
     * @param bool $isLinked Return elements as name+extra/link pairs - The last one is never linked except root!
     * @return array Nav bar elements without URLs
     */
    public function getNavBar(bool $isLinked=true): array
    {
        PlugIns::getInstance()->callHook(PlugIns::HOOK_NAV_BAR_GET_NAV_BAR, $isLinked);
        return array_map(fn($element) => $isLinked ? [$element[0] . $element[2], $element[1]] : $element[0], $this->elements);
    }
}
?>
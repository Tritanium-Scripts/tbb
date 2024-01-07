<?php
/**
 * Manages calendar entries.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminCalendar extends PublicModule
{
    use Singleton, Mode;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['adminCalendar' => 'AdminCalendar',
        'edit' => 'AdminCalendarEditEvent',
        'new' => 'AdminCalendarNewEvent'];


    private array $events;

    /**
     * Sets mode and loads planned events.
     *
     * @param string $mode Mode to execute
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->events = array_map(['Functions', 'explodeByTab'], Functions::file('vars/events.var') ?: []);
    }

    /**
     * Executes module.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_calendar'), INDEXFILE . '?faction=adminCalendar' . SID_AMPER);
        if(Config::getInstance()->getCfgVal('activate_calendar') != 1)
            Template::getInstance()->printMessage('function_deactivated');
        switch($this->mode)
        {
            default:
            Template::getInstance()->assign('events', $this->events);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode]);
    }
}
?>
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
    use Singleton, Mode, Errors;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['adminCalendar' => 'AdminCalendar',
        'edit' => 'AdminCalendarEditEvent',
        'new' => 'AdminCalendarNewEvent'];

    /**
     * Current planned events.
     *
     * @var array Calendar entries
     */
    private array $events;

    /**
     * ID of current event.
     *
     * @var int Calendar entry ID
     */
    private int $eventId;

    /**
     * Post icon ID selected for current event.
     *
     * @var int ID of current event post icon
     */
    private string $eventIcon;

    /**
     * Contains new or edited event name.
     *
     * @var string Name of current event
     */
    private string $eventName;

    /**
     * Start date for current event.
     *
     * @var int Current event start timetamp
     */
    private int $eventStartDate;

    /**
     * End date for current event.
     *
     * @var int Current event end timetamp
     */
    private int $eventEndDate;

    /**
     * Description of current event.
     *
     * @var string Current event description
     */
    private string $eventDescription;

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
        $this->eventId = intval(Functions::getValueFromGlobals('id'));
        //Get data for new or edited event
        $this->eventIcon = intval(Functions::getValueFromGlobals('tsmilie')) ?: 1;
        $this->eventName = htmlspecialchars(Functions::getValueFromGlobals('eventName'));
        $this->eventStartDate = Functions::getTimestampFromGlobals('eventStartDate') ?? 0;
        $this->eventEndDate = Functions::getTimestampFromGlobals('eventEndDate') ?? 0;
        $this->eventDescription = htmlspecialchars(Functions::getValueFromGlobals('eventDescription', false));
        PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CALENDAR_INIT);
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
//AdminCalendarNewEvent
            case 'new':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('add_new_event'), INDEXFILE . '?faction=adminCalendar&amp;mode=new' . SID_AMPER);
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CALENDAR_NEW_EVENT);
            if(Functions::getValueFromGlobals('create') == 'yes')
            {
                if(empty($this->eventName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_an_event_name');
                if($this->eventStartDate <= 0 || $this->eventEndDate <= 0)
                    $this->errors[] = Language::getInstance()->getString('please_select_valid_date');
                if($this->eventStartDate > $this->eventEndDate)
                    $this->errors[] = Language::getInstance()->getString('please_select_end_date_after_start_date');
                if(empty($this->errors))
                {
                    //Get new ID
                    $this->eventId = !empty($this->events) ? current(end($this->events))+1 : 1;
                    //Add to events
                    Functions::file_put_contents('vars/events.var', $this->eventId . "\tevent\t" . $this->eventIcon . "\t" . $this->eventStartDate . "\t" . $this->eventEndDate . "\t" . $this->eventName . "\t" . Functions::nl2br($this->eventDescription) . "\n", FILE_APPEND);
                    //Done
                    Logger::getInstance()->log('%s added new event (ID: ' . $this->eventId . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=adminCalendar' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('event_added');
                }
            }
            else
            {
                $this->eventStartDate = mktime(0, 0, 0);
                $this->eventEndDate = mktime(23, 59, 59);
            }
            Template::getInstance()->assign(['newEventIcon' => $this->eventIcon,
                'newEventName' => $this->eventName,
                'newEventStartDate' => $this->eventStartDate,
                'newEventEndDate' => $this->eventEndDate,
                'newEventDescription' => $this->eventDescription,
                'errors' => $this->errors]);
            break;

//AdminCalendarEditEvent
            case 'edit':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('edit_event'), INDEXFILE . '?faction=adminCalendar&amp;mode=edit&amp;id=' . $this->eventId . SID_AMPER);
            if(($key = array_search($this->eventId, array_map('current', $this->events))) === false)
                Template::getInstance()->printMessage('event_not_found');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CALENDAR_EDIT_EVENT, $key);
            if(Functions::getValueFromGlobals('update') == 'yes')
            {
                if(empty($this->eventName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_an_event_name');
                if($this->eventStartDate <= 0 || $this->eventEndDate <= 0)
                    $this->errors[] = Language::getInstance()->getString('please_select_valid_date');
                if($this->eventStartDate > $this->eventEndDate)
                    $this->errors[] = Language::getInstance()->getString('please_select_end_date_after_start_date');
                if(empty($this->errors))
                {
                    //Update event
                    $this->events[$key] = [$this->eventId, 'event', $this->eventIcon, $this->eventStartDate, $this->eventEndDate, $this->eventName, Functions::nl2br($this->eventDescription)];
                    //Save it
                    Functions::file_put_contents('vars/events.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->events)) . "\n");
                    //Done
                    Logger::getInstance()->log('%s edited event (ID: ' . $this->eventId . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=adminCalendar' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('event_edited');
                }
            }
            else
            {
                list(,, $this->eventIcon, $this->eventStartDate, $this->eventEndDate, $this->eventName, $this->eventDescription) = $this->events[$key];
                $this->eventDescription = Functions::br2nl($this->eventDescription);
            }
            Template::getInstance()->assign(['eventId' => $this->eventId,
                'editEventIcon' => $this->eventIcon,
                'editEventName' => $this->eventName,
                'editEventStartDate' => $this->eventStartDate,
                'editEventEndDate' => $this->eventEndDate,
                'editEventDescription' => $this->eventDescription,
                'errors' => $this->errors]);
            break;

            case 'delete':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_event'), INDEXFILE . '?faction=adminCalendar&amp;mode=delete&amp;id=' . $this->eventId . SID_AMPER);
            if(($key = array_search($this->eventId, array_map('current', $this->events))) === false)
                Template::getInstance()->printMessage('event_not_found');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CALENDAR_DELETE_EVENT, $key);
            //Delete it
            unset($this->events[$key]);
            Functions::file_put_contents('vars/events.var', empty($this->events) ? '' : implode("\n", array_map(['Functions', 'implodeByTab'], $this->events)) . "\n");
            //Done
            Logger::getInstance()->log('%s deleted event (ID: ' . $this->eventId . ')', Logger::LOG_ACP_ACTION);
            header('Location: ' . INDEXFILE . '?faction=adminCalendar' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('event_deleted');
            break;

//AdminCalendar
            default:
            array_walk($this->events, fn(&$event, $key) => $event[2] = Functions::getTSmileyURL($event[2]));
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CALENDAR_SHOW_EVENTS);
            Template::getInstance()->assign('events', $this->events);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode]);
    }
}
?>
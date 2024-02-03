<?php
/**
 * Calendar displaying events and join dates.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2021-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Calendar extends PublicModule
{
    use Singleton;

    /**
     * (Current) Year to filter by.
     *
     * @var int Year number
     */
    private int $year;

    /**
     * (Current) Month to filter by.
     *
     * @var int Month of year
     */
    private int $month;

    /**
     * Sets (current) year and month numbers.
     */
    function __construct()
    {
        parent::__construct();
        $this->year = intval(Functions::getValueFromGlobals('year'));
        $this->month = intval(Functions::getValueFromGlobals('month'));
        //Set to current date on first call...
        if($this->year < 1970 || $this->year > PHP_INT_MAX)
        {
            $this->year = gmdate('Y');
            $this->month = gmdate('n');
        }
        //...or check turn of year by month
        elseif($this->month < 1)
        {
            $this->year--;
            $this->month = 12;
        }
        elseif($this->month > 12)
        {
            $this->year++;
            $this->month = 1;
        }
    }

    /**
     * Displays calendar entries for selected year and month.
     */
    public function publicCall(): void
    {
        NavBar::getInstance()->addElement(Language::getInstance()->getString('calendar'), INDEXFILE . '?faction=calendar' . SID_AMPER);
        if(Config::getInstance()->getCfgVal('activate_calendar') != 1)
            Template::getInstance()->printMessage('function_deactivated');
        //Get current month as timestamp
        $date = gmmktime(0, 0, 0, $this->month, 1, $this->year);
        $calendar = $events = [];
        //Add any days from previous month in case the current one is not starting with Monday
        //Best case: 1st = Monday = 0 days to pad
        //Worst case: 1st = Sunday = 6 days to pad
        $paddingDays = gmdate('N', $date) - 1;
        while($paddingDays > 0)
            $calendar[gmdate('W', $date)][gmdate('j', $date - (86400 * $paddingDays--))] = false;
        //Filter and add planned events
        $plannedEvents = Functions::file('vars/events.var');
        if($plannedEvents !== false)
        {
            #0:id - 1:type - 2:icon - 3:startDate - 4:endDate - 5:name - 6:description
            foreach($plannedEvents as &$curPlannedEvent)
            {
                $curPlannedEvent = Functions::explodeByTab($curPlannedEvent);
                $curPlannedEvent[2] = Functions::getTSmileyURL($curPlannedEvent[2]);
                $curPlannedEvent[6] = BBCode::getInstance()->parse($curPlannedEvent[6], false, true, false);
                $events[] = array_combine(['type', 'icon', 'startDate', 'endDate', 'name', 'description'], array_slice($curPlannedEvent, 1));
            }
            unset($plannedEvents);
        }
        //Filter and add member events (join or anniversary date in the selected month) as well as birthdays
        foreach(Functions::glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
        {
            $curMember = Functions::file($curMember, null, null, false);
            $curRegYear = intval(Functions::substr($curMember[6], 0, 4));
            $curRegMonth = intval(Functions::substr($curMember[6], 4, 2));
            if($curRegYear <= $this->year && $curRegMonth == $this->month)
            {
                $curEvent = [];
                $curEvent['type'] = 'member';
                //Move reg date to selected year
                $curEvent['startDate'] = gmmktime(0, 0, 0, $curRegMonth, intval(Functions::substr($curMember[6] . '01', 6, 2)), $this->year);
                $curEvent['endDate'] = $curEvent['startDate'] + 86399;
                $curEvent['name'] = $curMember[0];
                $reggedYears = $this->year - intval(Functions::substr($curMember[6], 0, 4));
                switch($reggedYears)
                {
                    case 0:
                    $curEvent['icon'] = 'registration';
                    $curEvent['description'] = Language::getInstance()->getString('registration');
                    break;

                    case 1:
                    $curEvent['icon'] = 'anniversary';
                    $curEvent['description'] = Language::getInstance()->getString('anniversary_one_year');
                    break;

                    default:
                    $curEvent['icon'] = 'anniversary';
                    $curEvent['description'] = sprintf(Language::getInstance()->getString('anniversary_x_years'), $reggedYears);
                    break;
                }
                $curEvent['member'] = Functions::getProfileLink($curMember[1]);
                $events[] = $curEvent;
            }
            if(!empty($curMember[22]) && $this->year >= date('Y', $curMember[22]) && date('m', $curMember[22]) == $this->month)
            {
                $curEvent = [];
                $curEvent['type'] = 'member';
                //Move birthday to selected year
                $curEvent['startDate'] = strtotime($this->year . date('-m-d H:i:s', $curMember[22]));
                $curEvent['endDate'] = $curEvent['startDate'] + 86399;
                $curEvent['name'] = $curMember[0];
                $curEvent['icon'] = 'birthday';
                $years = $this->year - date('Y', $curMember[22]);
                switch($years)
                {
                    case 1:
                    $curEvent['description'] = Language::getInstance()->getString('birthday_one_year');
                    break;

                    default:
                    $curEvent['description'] = sprintf(Language::getInstance()->getString('birthday_x_years'), $years);
                    break;
                }
                $curEvent['member'] = Functions::getProfileLink($curMember[1]);
                $events[] = $curEvent;
            }
        }
        //Build current month view with events attached to their affected days
        $lastWeek = null;
        for($curDay=1; $curDay<=gmdate('t', $date); $curDay++)
        {
            //Provide current day as timestamps
            $curDayStart = gmmktime(0, 0, 0, $this->month, $curDay, $this->year);
            $curDayEnd = gmmktime(23, 59, 59, $this->month, $curDay, $this->year);
            //Fetch week number
            $curWeek = gmdate('W', $curDayStart);
            //Add new day to its week number
            $calendar[$curWeek][$curDay] = [];
            //Check for related events
            foreach($events as $curEvent)
                //Comparing timestamps is leet 'n' possible since all dates are moved to / based on selected year!
                //Is event starting current day?
                if(($curEvent['startDate'] >= $curDayStart && $curEvent['startDate'] <= $curDayEnd)
                        //Is event ending current day?
                        || ($curEvent['endDate'] >= $curDayStart && $curEvent['endDate'] <= $curDayEnd)
                        //Is current day in event's time period?
                        || ($curDayStart > $curEvent['startDate'] && $curDayStart < $curEvent['endDate']
                            && $curDayEnd > $curEvent['startDate'] && $curDayEnd < $curEvent['endDate']))
                    $calendar[$curWeek][$curDay][] = $curEvent;
            $lastWeek = $curWeek;
        }
        //Pad last week with empty days of next month
        $curDay = 1;
        while(count($calendar[$lastWeek]) < 7)
            $calendar[$lastWeek][$curDay++] = false;
        Template::getInstance()->printPage('Calendar', ['year' => $this->year,
            'month' => $this->month,
            'date' => $date,
            'calendar' => $calendar]);
    }
}
?>
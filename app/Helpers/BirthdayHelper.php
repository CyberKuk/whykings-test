<?php

namespace App\Helpers;

use App\Models\Person;
use DateInterval;
use DateTime;

class BirthdayHelper
{
    private string $personName;
    private DateTime $personBirthDay;

    private static DateTime $now;

    public function __construct(Person $person)
    {
        if (!isset(static::$now)) {
            static::$now = (new DateTime('now'));
        }
        $this->personName = $person->name;
        $this->personBirthDay = clone $person->birthday;
        $this->personBirthDay->setTimezone(static::$now->getTimezone());
    }

    public function getDateInterval(): DateInterval
    {
        $currYearDate = (new DateTime())->setDate(self::$now->format('Y'), $this->personBirthDay->format('m'), $this->personBirthDay->format('d'));
        if ($currYearDate < self::$now) {
            $currYearDate->modify('+1 year');
        }
        return  $currYearDate->diff(self::$now);
    }

    public function isBirthDay(): bool
    {
        return ($this->personBirthDay->format('md') === static::$now->format('md'));
    }

    public function getCurrentTimezone(): string
    {
        return self::$now->getTimezone()->getName();
    }

    public function getTextMessage(): string
    {
        $text = "$this->personName is ";
        if ($this->isBirthDay()) {
            $endOfDay = clone self::$now;
            $endOfDay->setTime(23, 59, 59);
            $years =  $endOfDay->diff($this->personBirthDay)->y;
            $hoursRemaining = $endOfDay->diff(self::$now)->h;
            $text .= "$years years old today ($hoursRemaining hours remaining in {$this->getCurrentTimezone()})";
        } else {
            $interval = $this->getDateInterval();
            $years =  self::$now->diff($this->personBirthDay)->y+1;
            $text = "$years years old in $interval->m months, $interval->d days in {$this->getCurrentTimezone()}";
        }
        return $text;
    }
}

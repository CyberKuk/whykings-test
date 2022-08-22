<?php

namespace App\Helpers;

use App\Models\Person;
use DateInterval;
use DateTime;

class BirthdayHelper
{
    private string $personName;
    private DateTime $personBirthDay;

    private DateTime $now;

    public function __construct(Person $person, ?DateTime $now = null)
    {
        if (!$now) {
            $now = (new DateTime('now'));
        }
        $this->now = $now;
        $this->personName = $person->name;
        $this->personBirthDay = clone $person->birthday;
        $this->personBirthDay->setTimezone($this->now->getTimezone());
    }

    public function getDateInterval(): DateInterval
    {
        $currYearDate = clone $this->personBirthDay;
        $currYearDate = $currYearDate->setDate($this->now->format('Y'), $this->personBirthDay->format('m'), $this->personBirthDay->format('d'));
        if ($currYearDate < $this->now) {
            $currYearDate->modify('+1 year');
        }
        return  $currYearDate->diff($this->now);
    }

    public function isBirthDay(): bool
    {
        return ($this->personBirthDay->format('md') === $this->now->format('md'));
    }

    public function getCurrentTimezone(): string
    {
        return $this->now->getTimezone()->getName();
    }

    public function getTextMessage(): string
    {
        $text = "$this->personName is ";
        if ($this->isBirthDay()) {
            $endOfDay = clone $this->now;
            $endOfDay->setTime(23, 59, 59);
            $years =  $endOfDay->diff($this->personBirthDay)->y;
            $hoursRemaining = $endOfDay->diff($this->now)->h;
            $text .= "$years years old today ($hoursRemaining hours remaining in {$this->getCurrentTimezone()})";
        } else {
            $interval = $this->getDateInterval();
            $years =  $this->now->diff($this->personBirthDay)->y+1;
            $text .= "$years years old in $interval->m months, $interval->d days in {$this->getCurrentTimezone()}";
        }
        return $text;
    }
}

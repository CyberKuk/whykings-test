<?php

namespace Tests\Unit;

use App\Helpers\BirthdayHelper;
use App\Models\Person;
use DateTime;
use DateTimeZone;
use Tests\TestCase;

class BirthdayHelperTest extends TestCase
{
    private const NOW = '2022-05-15 22:00:00';
    private const NOW_TIMEZONE = 'UTC';

    /**
     * @dataProvider isBirthdayDataProvider
     */
    public function testIsBirthday(bool $expectedValue, string $birthdate, ?string $timeZone = 'UTC'): void
    {
        $helper = $this->getBirthdayHelper($birthdate, $timeZone);
        $this->assertEquals($expectedValue, $helper->isBirthDay());
    }

    public function isBirthdayDataProvider(): array
    {
        return [
            'simple birthday'               => [
                'expected'  => true,
                'birthdate' => self::NOW,
            ],
            'simple not birthday'           => [
                'expected'  => false,
                'birthdate' => '2022-05-14',
            ],
            'birthday another year'         => [
                'expected'  => true,
                'birthdate' => '2012-05-15',
            ],
            'birthday another timezone'     => [
                'expected'  => true,
                'birthdate' => '2012-05-16',
                'timeZone'  => 'Australia/Sydney',
            ],
            'not birthday another timezone' => [
                'expected'  => false,
                'birthdate' => '2012-05-15',
                'timeZone'  => 'Australia/Sydney',
            ],
        ];
    }

    public function testGetTimezone(): void
    {
        $helper = $this->getBirthdayHelper(self::NOW);
        $this->assertEquals(self::NOW_TIMEZONE, $helper->getCurrentTimezone());
    }

    /**
     * @dataProvider getIntervalDataProvider
     */
    public function testGetInterval(array $expectedValues, string $birthdate, ?string $timeZone = 'UTC'): void
    {
        $interval = $this->getBirthdayHelper($birthdate, $timeZone)->getDateInterval();
        foreach ($expectedValues as $key => $value) {
            $this->assertEquals($value, $interval->$key);
        }
    }

    public function getIntervalDataProvider(): array
    {
        return [
            'simple'           => [
                'expected'  => [
                    'y' => 0,
                    'm' => 7,
                    'd' => 27,
                    'h' => 2,
                ],
                'birthdate' => '2020-01-12',
            ],
            'another timezone' => [
                'expected'  => [
                    'y' => 0,
                    'm' => 7,
                    'd' => 26,
                    'h' => 15,
                ],
                'birthdate' => '2020-01-12',
                'timeZone'  => 'Australia/Sydney',
            ],
        ];
    }

    /**
     * @dataProvider getTextMessageProvider
     */
    public function testGetMessage(string  $expectedMessage,
                                   string  $birthdate,
                                   ?string $timeZone = 'UTC',
                                   ?string  $name = 'Tester'): void
    {
        $helper = $this->getBirthdayHelper($birthdate, $timeZone, $name);
        $this->assertEquals($expectedMessage, $helper->getTextMessage());
    }

    public function getTextMessageProvider(): array
    {
        return [
            'simple birthday' => [
                'message' => 'Tester is 21 years old today (1 hours remaining in UTC)',
                'birthdate' => '2001-05-15',
            ],
            'simple not birthday' => [
                'message' => 'Tester is 21 years old in 0 months, 3 days in UTC',
                'birthdate' => '2001-05-19',
            ],
            'another name' => [
                'message' => 'Developer is 21 years old in 0 months, 3 days in UTC',
                'birthdate' => '2001-05-19',
                'timezone' => 'UTC',
                'name' => 'Developer',
            ],
            'another timezone' => [
                'message' => 'Tester is 22 years old in 11 months, 29 days in UTC',
                'birthdate' => '2001-05-15',
                'timezone' => 'Australia/Sydney',
            ],
        ];
    }

    private function getBirthdayHelper(string $birthdate,
                                       string $timeZone = 'UTC',
                                       string $name = 'Tester'): BirthdayHelper
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $now = new DateTime(self::NOW, new DateTimeZone(self::NOW_TIMEZONE));

        $person = new Person();
        $person->name = $name;
        $person->birthdate = $birthdate;
        $person->timezone = $timeZone;

        return (new BirthdayHelper($person, $now));
    }
}

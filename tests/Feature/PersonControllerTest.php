<?php

namespace Tests\Feature;

use App\Models\Person;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testSaveOk(): void
    {
        $response = $this->post('/person', [
                                    "name"      => "John",
                                    "birthdate" => "1990-01-01",
                                    "timezone"  => "America/New_York",
                                ]);
        $response->assertStatus(200);
        $response->assertJson(['message'=>'Ok']);
    }

    /**
     * @dataProvider errorDataProvider
     */
    public function testSaveError(array $data, string $expectedMessage): void
    {
        $response = $this->post('/person', $data);
        $response->assertStatus(422);
        $response->assertJson(['message'=>$expectedMessage]);
    }

    public function errorDataProvider(): array
    {
        return [
            'no name' => [
                'data' => [
                    "birthdate" => "1990-01-01",
                    "timezone"  => "America/New_York",
                ],
                'expectedMessage' => 'The name field is required.'
            ],
            'long name' => [
                'data' => [
                    "name"      => "JohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohnJohn",
                    "birthdate" => "1990-01-01",
                    "timezone"  => "America/New_York",
                ],
                'expectedMessage' => 'The name must not be greater than 255 characters.'
            ],
            'no birthdate' => [
                'data' => [
                    "name"      => "John",
                    "timezone"  => "America/New_York",
                ],
                'expectedMessage' => 'The birthdate field is required.'
            ],
            'birthdate invalid' => [
                'data' => [
                    "name"      => "John",
                    "birthdate" => "The birthdate field is required.",
                    "timezone"  => "America/New_York",
                ],
                'expectedMessage' => 'The birthdate is not a valid date. (and 2 more errors)'
            ],
            'birthdate in future' => [
                'data' => [
                    "name"      => "John",
                    "birthdate" => (new DateTime('+1 year'))->format('Y-m-d'),
                    "timezone"  => "America/New_York",
                ],
                'expectedMessage' => 'The birthdate must be a date before now.'
            ],
            'no timezone' => [
                'data' => [
                    "name"      => "John",
                    "birthdate" => "1990-01-01",
                ],
                'expectedMessage' => 'The timezone field is required.'
            ],
            'timezone invalid' => [
                'data' => [
                    "name"      => "John",
                    "birthdate" => "1990-01-01",
                    "timezone"  => "Invalid Time Zone",
                ],
                'expectedMessage' => 'The timezone must be a valid timezone.'
            ],
        ];
    }

    public function testList(): void
    {
        Person::factory()->count(3)->create();

        $response = $this->get('/person');
        $response->assertStatus(200);
        $response->assertJson(['data'=>true]);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
                                           'data' => [
                                               '*' => [
                                                   'name',
                                                   'birthdate',
                                                   'timezone',
                                                   'isBirthday',
                                                   'interval',
                                                   'message',
                                               ]
                                           ]
            ]);
    }
}

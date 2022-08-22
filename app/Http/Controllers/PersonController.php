<?php

namespace App\Http\Controllers;

use App\Helpers\BirthdayHelper;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PersonController extends Controller
{
    public function create(Request $request): array
    {
        $validated = $request->validate([
                                            'name'      => 'required|max:255',
                                            'birthdate' => 'required|date|date_format:Y-m-d|before:now',
                                            'timezone'  => 'required|timezone',
                                        ]);
        Person::create($validated);
        return ['message' => 'Ok'];

    }

    public function list(): array
    {
        $result['data'] = [];
        /** @var Person $person */
        foreach (Person::all() as $person) {
            $helper = new BirthdayHelper($person);
            $result['data'][] = [
                'name'       => $person->name,
                'birthdate'  => $person->birthdate,
                'timezone'   => $helper->getCurrentTimezone(),
                'isBirthday' => $helper->isBirthDay(),
                'interval'   => $helper->getDateInterval(),
                'message'    => $helper->getTextMessage(),
            ];
        }
        return $result;
    }
}

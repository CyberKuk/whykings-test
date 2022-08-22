<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PersonController extends Controller
{
    public function create(Request $request): array
    {
        $validated = $request->validate([
                                                'name' => 'required|max:255',
                                                'birthdate' => 'required|date|date_format:Y-m-d',
                                                'timezone' => 'required|timezone',
                                            ]);

        //TODO: save to DB

        return ['message'=>'Ok'];

    }

    public function list(): array
    {
        //TODO: get from db and show in right format
        return ['contr list'];
    }
}

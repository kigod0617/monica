<?php

namespace App\Http\Controllers\Settings\Personalize\Genders\ViewHelpers;

use App\Models\Gender;
use App\Models\Account;

class PersonalizeGenderIndexViewHelper
{
    public static function data(Account $account): array
    {
        $genders = $account->genders()
            ->orderBy('name', 'asc')
            ->get();

        $collection = collect();
        foreach ($genders as $gender) {
            $collection->push(self::dtoGender($gender));
        }

        return [
            'genders' => $collection,
            'url' => [
                'settings' => route('settings.index'),
                'personalize' => route('settings.personalize.index'),
                'gender_store' => route('settings.personalize.gender.store'),
            ],
        ];
    }

    public static function dtoGender(Gender $gender): array
    {
        return [
            'id' => $gender->id,
            'name' => $gender->name,
            'url' => [
                'update' => route('settings.personalize.gender.update', [
                    'gender' => $gender->id,
                ]),
                'destroy' => route('settings.personalize.gender.destroy', [
                    'gender' => $gender->id,
                ]),
            ],
        ];
    }
}

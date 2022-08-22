<?php

namespace App\Models;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $birthdate
 * @property string $timezone
 * @property DateTime $birthday
 * @method static create(array $validated)
 */
class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'birthdate',
        'timezone',
    ];

    /** @noinspection PhpUnused */
    protected function birthday(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => new DateTime(
                $attributes['birthdate'],
                (new DateTimeZone($attributes['timezone'])),
            ),
        );
    }
}

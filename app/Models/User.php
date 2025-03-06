<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'postal_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the country that owns the user.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the state that owns the user.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city that owns the user.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }


    /**
     * Get the calendars that the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function calendars()
    {
        return $this->belongsToMany(Calendar::class);
    }

    /**
     * Get the departments that the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function departments()
    {
        return $this->belongsToMany(Departament::class);
    }

    /**
     * Get the holidays associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    /**
     * Get the timesheets associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}

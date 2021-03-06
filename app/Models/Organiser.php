<?php

namespace App\Models;

use Str;

class Organiser extends MyBaseModel
{
    protected $rules = [
        'name'           => ['required'],
        'email'          => ['required', 'email'],
        'organiser_logo' => ['mimes:jpeg,jpg,png', 'max:10000'],
    ];
    protected $messages = [
        'name.required'        => 'You must at least give a name for the event organiser.',
        'organiser_logo.max'   => 'Please upload an image smaller than 10Mb',
        'organiser_logo.size'  => 'Please upload an image smaller than 10Mb',
        'organiser_logo.mimes' => 'Please select a valid image type (jpeg, jpg, png)',
    ];

    public function events()
    {
        return $this->hasMany('\App\Models\Event');
    }

    public function attendees()
    {
        return $this->hasManyThrough('\App\Models\Attendee', '\App\Models\Event');
    }

    public function getFullLogoPathAttribute()
    {
        if ($this->logo_path && (file_exists(config('attendize.cdn_url_user_assets').'/'.$this->logo_path) || file_exists(public_path($this->logo_path)))) {
            return config('attendize.cdn_url_user_assets').'/'.$this->logo_path;
        }

        return config('attendize.fallback_organiser_logo_url');
    }

    public function getOrganiserUrlAttribute()
    {
        return route('showOrganiserHome', [
            'organiser_id'   => $this->id,
            'organiser_slug' => Str::slug($this->oraganiser_name),
        ]);
    }

    public function getOrganiserSalesVolumeAttribute()
    {
        return $this->events->sum('sales_volume');
    }

    public function getDailyStats()
    {
    }
}

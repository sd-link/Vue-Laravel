<?php

namespace App\Models\Core\Forms;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $casts = [
        'meta' => 'array',
    ];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    // public function getFieldsAttribute()
    // {
    //     if(isset($this->meta['fields']))
    //         return $this->meta['fields'];
    //     else
    //         return null;
    // }

    public function getCaptchaAttribute()
    {
        if(isset($this->meta['options']['captcha']))
            return filter_var($this->meta['options']['captcha'], FILTER_VALIDATE_BOOLEAN);
        else
            return null;
    }

    public function getSuccessMessageAttribute()
    {
        if(isset($this->meta['options']['success_message']))
            return filter_var($this->meta['options']['success_message'], FILTER_VALIDATE_BOOLEAN);
        else
            return null;
    }

    public function getSuccessMessageTextAttribute()
    {
        if(isset($this->meta['options']['success_message_text']))
            return $this->meta['options']['success_message_text'];
        else
            return null;
    }

    public function getRedirectAttribute()
    {
        if(isset($this->meta['options']['redirect']))
            return filter_var($this->meta['options']['redirect'], FILTER_VALIDATE_BOOLEAN);
        else
            return null;
    }

    public function getRedirectUrlAttribute()
    {
        if(isset($this->meta['options']['redirect_url']))
            return $this->meta['options']['redirect_url'];
        else
            return null;
    }

    public function getOptionsEmailsAdminAttribute()
    {
        if(isset($this->meta['options']['actions']['admin']))
            return filter_var($this->meta['options']['actions']['admin'], FILTER_VALIDATE_BOOLEAN);
        else
            return null;
    }

    public function getOptionsEmailsSubmitterAttribute()
    {
        if(isset($this->meta['options']['actions']['submitter']))
            return filter_var($this->meta['options']['actions']['submitter'], FILTER_VALIDATE_BOOLEAN);
        else
            return null;
    }

    public function getAdminSubjectAttribute()
    {
        if(isset($this->meta['options']['emails']['admin']['subject']))
            return $this->meta['options']['emails']['admin']['subject'];
        else
            return null;
    }

    public function getAdminMessageAttribute()
    {
        if(isset($this->meta['options']['emails']['admin']['message']))
            return $this->meta['options']['emails']['admin']['message'];
        else
            return null;
    }

    public function getSubmitterSubjectAttribute()
    {
        if(isset($this->meta['options']['emails']['submitter']['subject']))
            return $this->meta['options']['emails']['submitter']['subject'];
        else
            return null;
    }

    public function getSubmitterMessageAttribute()
    {
        if(isset($this->meta['options']['emails']['submitter']['message']))
            return $this->meta['options']['emails']['submitter']['message'];
        else
            return null;
    }

}

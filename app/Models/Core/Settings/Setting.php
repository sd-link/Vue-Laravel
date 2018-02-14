<?php

namespace App\Models\Core\Settings;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = "settings";

    protected $fillable = ['key', 'value', 'type'];

    public function has_settings()
    {
        return $this->morphTo();
    }
}

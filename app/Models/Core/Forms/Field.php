<?php

namespace App\Models\Core\Forms;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $table = 'form_fields';
    public $timestamps = false;

    protected $casts = [
        'meta' => 'array',
    ];

    public function getSelectValuesAttribute()
    {
        if(isset($this->meta['selectvalues'])) {
            return $this->meta['selectvalues'];
        }
        else {
            return null;
        }
    }
}

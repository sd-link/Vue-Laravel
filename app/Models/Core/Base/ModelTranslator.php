<?php

namespace App\Models\Core\Base;

use Illuminate\Database\Eloquent\Model;

class ModelTranslator extends Model
{
    protected $table = 'model_translations';

    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function translatable()
    {
        return $this->morphTo();
    }
}

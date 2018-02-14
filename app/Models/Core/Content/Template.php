<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;

use App\Models\Core\Settings\HasSettings;

class Template extends Model
{
    protected $table = "templates";
    protected $fillable = ['name', 'content_type_id', 'default'];

    public function content_type()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id', 'id');
    }

    public function blocks()
    {
        return $this->hasMany(TemplateBlock::class, 'content_template_id', 'id');
    }

    public function setDefaultAttribute($value)
    {
        if(isset($this->content_type_id)) {
            foreach ($this->content_type->templates as $key => $template) {
                $template->attributes['default'] = false;
                $template->save();
            }
            $this->attributes['default'] = $value;
        }
    }
}

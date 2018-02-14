<?php

namespace App\Models\Core\Base;

use Illuminate\Database\Eloquent\Model;

class LaraModel extends Model
{
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        if ($this->useSti()) {
            $this->setAttribute($this->stiClassField,get_class($this));

        }
    }

    private function useSti() {
      return ($this->stiClassField && $this->stiBaseClass);
    }

    public function newQuery($excludeDeleted = true)
    {
        $builder = parent::newQuery($excludeDeleted);
        // If I am using STI, and I am not the base class,
        // then filter on the class name.
        if ($this->useSti() && get_class(new $this->stiBaseClass) !== get_class($this)) {
          $builder->where($this->stiClassField, "=", get_class($this));
        }
        return $builder;
    }

    public function newFromBuilder($attributes = array(), $connection = NULL)
    {
        // echo " class2: " . $attributes->{$this->stiClassField} . "; ";
        if ($this->useSti() && get_class($this)) {
            $class = get_class($this);
            $instance = new $class;
            $instance->exists = true;
            $instance->setRawAttributes((array) $attributes, true);
            return $instance;
        } else {
            return parent::newFromBuilder($attributes);
        }
    }
}

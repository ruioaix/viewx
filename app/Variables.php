<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variables extends Model
{
    protected $table = 'variables';

    protected function get($name) {
        $var = Variables::where('name', '=', $name)
            ->select('value')
            ->first();
        return $var;
    }
}

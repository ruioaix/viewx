<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProxyS extends Model
{
    protected $table = 'proxy_source';
    public $timestamps = false;

    protected function time($ago) {
        $source = ProxyS::where('time', '>=', $ago)
            ->orderBy('id', 'desc')
            ->get();
        return $source;
    }
}

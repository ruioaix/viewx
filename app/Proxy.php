<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    protected $table = 'proxy_record';
    public $timestamps = false;

    protected function time($ago) {
        $proxies = Proxy::where('time', '>=', $ago)
            ->orderBy('id', 'desc')
            ->get();
        return $proxies;
    }
}

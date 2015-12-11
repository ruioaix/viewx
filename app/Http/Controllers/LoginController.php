<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Variables

class LoginController extends Controller
{
    public function circle_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();

        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $slist = ProxyS::period($beforebefore_tp, $before_tp);
        $res = Variables::chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'pachong', 'hidemyass', 'freeproxylists');
        foreach ($slist as $sone) {
            $source = $sone['source'];
            $count = $sone['count'];
            $time = $sone['time'];

            $i = (int) (($time - $beforebefore_tp - 10) / $step_secord);
            if ($source == 10) {
                $res['datasets'][0]['data'][$i] += $count;
            }
            elseif ($source == 9) {
                $res['datasets'][1]['data'][$i] += $count;
            }
            elseif ($source == 8)  {
                $res['datasets'][2]['data'][$i] += $count;
            }
        }
        $res = json_encode($res);
        $url = action('ProxyController@cstep', ['']);
        return compact('res', 'from_secord', 'to_secord', 'url');
    }

    public function circle() {
        $res = ProxyController::circle_core(3600 * 60, 0);
        $res['title'] = "Proxy Circle";
        return view('one_l_ft', $res);
    }

    public function cstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = ProxyController::circle_core($from_secord, $to_secord); 
        return view('one_l_ft_js', $res);
    }


    public function errortype() {
        $paes = Variables::paerror();
        ksort($paes);
        return view('proxy.errortype', compact('paes'));
    }

}

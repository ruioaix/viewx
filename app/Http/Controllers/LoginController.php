<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Variables;
use App\login;

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

        $list = login::circle_proxy($beforebefore_tp, $before_tp);
        $res = Variables::chartjs_line_four_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'mimvp', 'pachong', 'hidemyass', 'freeproxylists');
        foreach ($list as $lone) {
            $source = $lone->source;
            $count = $lone->count;
            $time = $lone->time;
            $i = (int) (($time - $beforebefore_tp - 10) / $step_secord);
            if ($source == 7) {
                $res['datasets'][0]['data'][$i] += $count;
            }
            elseif ($source == 10) {
                $res['datasets'][1]['data'][$i] += $count;
            }
            elseif ($source == 9) {
                $res['datasets'][2]['data'][$i] += $count;
            }
            elseif ($source == 8)  {
                $res['datasets'][3]['data'][$i] += $count;
            }
        }
        $one = json_encode($res);

        $list = login::circle_account($beforebefore_tp, $before_tp);
        $res = Variables::chartjs_line_one_inited_with_time($beforebefore_tp, $step_secord, $stepNum);
        foreach ($list as $lone) {
            $time = $lone->time;
            $i = (int) (($time - $beforebefore_tp - 10) / $step_secord);
            $res['datasets'][0]['data'][$i] += 1;
        }
        $two = json_encode($res);

        $url = action('LoginController@ccstep', ['']);
        return compact('one', 'two', 'from_secord', 'to_secord', 'url');
    }

    public function circle() {
        $res = LoginController::circle_core(3600 * 60, 0);
        $res['title'] = "Proxy and Account's Circle";
        return view('two_l_ft', $res);
    }

    public function ccstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = LoginController::circle_core($from_secord, $to_secord); 
        return view('two_l_ft_js', $res);
    }

    public function errortype() {
        $paes = Variables::paerror();
        ksort($paes);
        return view('proxy.errortype', compact('paes'));
    }

}

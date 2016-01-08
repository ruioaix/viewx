<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Variables;
use App\Circle;

class CircleController extends Controller
{
    public function circle_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();

        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $list = Circle::circle($beforebefore_tp, $before_tp);
        $res = Variables::chartjs_line_four_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'mimvp', 'hidemyass', 'pachong', 'freeproxylists');
        $msgs = array('Rank', 'cheating', 'info', 'gain', 'adjust');
        $res2 = Variables::chartjs_line_inited_with_time(5, $msgs, $beforebefore_tp, $step_secord, $stepNum);
        foreach ($list as $lone) {
            $source = $lone->type;
            $count = $lone->value;
            $time = $lone->time;
            $i = (int) (($time - $beforebefore_tp - 10) / $step_secord);
            if ($source == 2) {
                $res['datasets'][0]['data'][$i] += $count;
            }
            elseif ($source == 3) {
                $res['datasets'][1]['data'][$i] += $count;
            }
            elseif ($source == 4) {
                $res['datasets'][2]['data'][$i] += $count;
            }
            elseif ($source == 5)  {
                $res['datasets'][3]['data'][$i] += $count;
            }
            elseif ($source >= 8)  {
                $res2['datasets'][$source - 8]['data'][$i] += 1;
            }
        }
        $one = json_encode($res);
        $two = json_encode($res2);

        $url = action('CircleController@ccstep', ['']);
        return compact('one', 'two', 'from_secord', 'to_secord', 'url');
    }

    public function circle() {
        $res = CircleController::circle_core(3600 * 60, 0);
        $res['title'] = "Proxy and Account's Circle";
        return view('two_l_ft', $res);
    }

    public function ccstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = CircleController::circle_core($from_secord, $to_secord); 
        return view('two_l_ft_js', $res);
    }

}

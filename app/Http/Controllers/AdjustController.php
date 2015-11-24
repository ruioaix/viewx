<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Adjust;
use App\Variables;

class AdjustController extends Controller
{
    protected function monitor_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $adjustdt = Adjust::period($beforebefore_tp, $before_tp);
        $mnt = Variables::chartjs_line_one_inited_with_time($beforebefore_tp, $step_secord, $stepNum);
        foreach ($adjustdt as $adj) {
            $updated = $adj['updated'];
            $i = (int) (($updated - $beforebefore_tp - 10) / $step_secord);
            if ($i == $stepNum) {
                $i -= 1;
            }
            $mnt['datasets'][0]['data'][$i] += 1;
        }
        $mnt = json_encode($mnt);
        $url = action('AdjustController@mstep', ['']);
        return compact('mnt', 'from_secord', 'to_secord', 'url');
    }

    public function monitor() {
        $res = AdjustController::monitor_core(3600 * 60, 0);
        $res['title'] = "Adjust Data Monitor";
        return view('one_canvas_line_from_to', $res);
    }

    public function mstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = AdjustController::monitor_core($from_secord, $to_secord);
        return view('one_canvas_line_from_to_js', $res);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\TaskC;
use App\Variables;

class TaskController extends Controller
{
    protected function monitor_core($from_secord, $to_secord) {
        #$period_secord > 3600s, 1h.
        #$stepNum for now 60, never > 100.
        #so, $step_secord > 36s.
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 
        $res = Variables::chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'info', 'gain', 'adjust');

        $tasks = Task::period_begin($beforebefore_tp, $before_tp);
        foreach ($tasks as $tk) {
            $task_tp = $tk['time'];
            $task_type = $tk['type'];
            $i = (int) (($task_tp - $beforebefore_tp) / $step_secord);
            if ($i == $stepNum) { $i -= 1; }
            if ($task_type == 0) {
                $res['datasets'][0]['data'][$i] += 1;
            }
            elseif ($task_type == 1) {
                $res['datasets'][1]['data'][$i] += 1;
            }
            elseif ($task_type == 2) {
                $res['datasets'][2]['data'][$i] += 1;
            }
        }
        $res = json_encode($res);

        $url = action('TaskController@mstep', ['']);
        return compact('res', 'from_secord', 'to_secord', 'url');
    }

    public function monitor() {
        $res = TaskController::monitor_core(60 * 3600, 0); 
        $res['title'] = "Task Monitor";
        return view('one_l_ft', $res);
    }

    public function mstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = TaskController::monitor_core($from_secord, $to_secord); 
        return view('one_l_ft_js', $res);
    }

    protected function adjust_core($from_secord, $to_secord) {
        #$period_secord > 3600s, 1h.
        #$stepNum for now 60, never > 100.
        #so, $step_secord > 36s.
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 
        $res = Variables::chartjs_line_two_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'begin', 'success');

        $tasks = Task::period($beforebefore_tp, $before_tp, 2);
        foreach ($tasks as $tk) {
            $task_tp = $tk['time'];
            $task_type = $tk['type'];
            $task_code = $tk['code'];
            $i = (int) (($task_tp - $beforebefore_tp) / $step_secord);
            if ($i == $stepNum) { $i -= 1; }
            if ($task_code == -1) {
                $res['datasets'][0]['data'][$i] += 1;
            }
            elseif ($task_code == 0) {
                $res['datasets'][1]['data'][$i] += 1;
            }
        }
        $res = json_encode($res);

        $url = action('TaskController@adstep', ['']);
        return compact('res', 'from_secord', 'to_secord', 'url');
    }

    public function adjust() {
        $res = TaskController::adjust_core(60 * 3600, 0); 
        $maxzid = Variables::get('maxzid');
        $num = TaskC::number(2);
        $res['title'] = "Adjust Task: $num/$maxzid";
        return view('one_l_ft', $res);
    }

    public function adstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = TaskController::adjust_core($from_secord, $to_secord); 
        return view('one_l_ft_js', $res);
    }

    public function manage(Request $request) {
        #var_dump(Input::all());
        $info = Variables::get('tasktype_prob_info');
        $gain = Variables::get('tasktype_prob_gain');
        $adjust_common = Variables::get('tasktype_prob_adjust_common');
        $adjust_list = Variables::get('tasktype_prob_adjust_list');
        $adjust_complete = Variables::get('tasktype_prob_adjust_complete');

        return view('task.manage', compact('info', 'gain', 'adjust_common', 'adjust_list', 'adjust_complete'));
    }

    public function manageupdate(Request $request) {
        #var_dump($request->infoprob);
        #$info = Variables::get('tasktype_prob_info');
        #$gain = Variables::get('tasktype_prob_gain');
        #$adjust_common = Variables::get('tasktype_prob_adjust_common');
        #$adjust_list = Variables::get('tasktype_prob_adjust_list');
        #$adjust_complete = Variables::get('tasktype_prob_adjust_complete');

        return redirect(action('TaskController@manage'));
    }
}

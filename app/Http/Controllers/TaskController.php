<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\TaskC;
use App\Variables;

use Carbon\Carbon;

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
        $res = Variables::chartjs_line_four_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'info', 'gain', 'adjust', 'cheating-check');

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
            elseif ($task_type == 4) {
                $res['datasets'][3]['data'][$i] += 1;
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

    protected function cheating_core($from_secord, $to_secord) {
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
        $res = Variables::chartjs_line_four_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'try', 'fine', 'cheating', 'other');

        $tasks = Task::period($beforebefore_tp, $before_tp, 4);
        foreach ($tasks as $tk) {
            $task_tp = $tk['time'];
            $task_type = $tk['type'];
            $task_code = $tk['code'];
            $i = (int) (($task_tp - $beforebefore_tp) / $step_secord);
            if ($i == $stepNum) { $i -= 1; }
            if ($task_code == -1) {
                $res['datasets'][0]['data'][$i] += 1;
            }
            elseif ($task_code == -10) {
                $res['datasets'][1]['data'][$i] += 1;
            }
            elseif ($task_code > 0) {
                $res['datasets'][2]['data'][$i] += 1;
            }
            else {
                $res['datasets'][3]['data'][$i] += 1;
            }
        }
        $res = json_encode($res);

        $url = action('TaskController@ccstep', ['']);
        return compact('res', 'from_secord', 'to_secord', 'url');
    }

    public function cheating() {
        $res = TaskController::cheating_core(60 * 3600, 0); 
        $res['title'] = "Xueqie Check Cheating";
        return view('one_l_ft', $res);
    }

    public function ccstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = TaskController::cheating_core($from_secord, $to_secord); 
        return view('one_l_ft_js', $res);
    }

    protected function viewtodb($data) {
        $info = $data['info'];
        $gain = $data['gain'];
        $adjust_common = $data['adjust_common'];
        $adjust_list = $data['adjust_list'];
        $adjust_complete = $data['adjust_complete'];

        $total = $info + $gain + $adjust_common + $adjust_list + $adjust_complete;

        $gain += $info;
        $adjust_common += $gain;
        $adjust_list += $adjust_common;
        $adjust_complete += $adjust_list;
        $info /= $total;
        $gain /= $total;
        $adjust_common /= $total;
        $adjust_list /= $total;
        $adjust_complete /= $total;
        Variables::set('tasktype_prob_info', number_format($info, 3));
        Variables::set('tasktype_prob_gain', number_format($gain, 3));
        Variables::set('tasktype_prob_adjust_common', number_format($adjust_common, 3));
        Variables::set('tasktype_prob_adjust_list', number_format($adjust_list, 3));
        Variables::set('tasktype_prob_adjust_complete', number_format($adjust_complete, 3));
    }

    protected function dbtoview($data) {
        $info = $data['info'];
        $gain = $data['gain'];
        $adjust_common = $data['adjust_common'];
        $adjust_list = $data['adjust_list'];
        $adjust_complete = $data['adjust_complete'];

        $adjust_complete -= $adjust_list;
        $adjust_list -= $adjust_common;
        $adjust_common -= $gain;
        $gain -= $info;
        return compact('info', 'gain', 'adjust_common', 'adjust_list', 'adjust_complete');
    }


    public function manage(Request $request) {
        #var_dump(Input::all());
        $info = Variables::one('tasktype_prob_info');
        $gain = Variables::one('tasktype_prob_gain');
        $adjust_common = Variables::one('tasktype_prob_adjust_common');
        $adjust_list = Variables::one('tasktype_prob_adjust_list');
        $adjust_complete = Variables::one('tasktype_prob_adjust_complete');

        $info_date = $info['time'];
        $info = $info['value'];
        $gain_date = $gain['time'];
        $gain = $gain['value'];
        $adjust_common_date = $adjust_common['time'];
        $adjust_common = $adjust_common['value'];
        $adjust_list_date = $adjust_list['time'];
        $adjust_list = $adjust_list['value'];
        $adjust_complete_date = $adjust_complete['time'];
        $adjust_complete = $adjust_complete['value'];

        $res = TaskController::dbtoview(compact('info', 'gain', 'adjust_common', 'adjust_list', 'adjust_complete'));
        $res['info_date'] = Carbon::createFromTimestamp($info_date, 'Europe/Zurich')->toDateTimeString();
        $res['gain_date'] = Carbon::createFromTimestamp($gain_date, 'Europe/Zurich')->toDateTimeString();
        $res['adjust_common_date'] = Carbon::createFromTimestamp($adjust_common_date, 'Europe/Zurich')->toDateTimeString();
        $res['adjust_list_date'] = Carbon::createFromTimestamp($adjust_list_date, 'Europe/Zurich')->toDateTimeString();
        $res['adjust_complete_date'] = Carbon::createFromTimestamp($adjust_complete_date, 'Europe/Zurich')->toDateTimeString();
        return view('task.manage', $res);
    }

    protected function getnumber($value) {
        if (is_numeric($value)) { 
            return $value + 0; 
        } 
        return 0;
    }

    public function manageupdate(Request $request) {
        $info = TaskController::getnumber($request->infoprob);
        $gain = TaskController::getnumber($request->gainprob);
        $adjust_common = TaskController::getnumber($request->adjustprob);
        $adjust_list = TaskController::getnumber($request->adjustlprob);
        $adjust_complete = TaskController::getnumber($request->adjustcprob);

        $total = $info + $gain + $adjust_common + $adjust_list + $adjust_complete;
        if ($total != 0) {
            TaskController::viewtodb(compact('msg', 'info', 'gain', 'adjust_common', 'adjust_list', 'adjust_complete'));
        }
        return redirect()->action('TaskController@manage');
    }
}

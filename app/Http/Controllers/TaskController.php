<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\TaskC;
use App\Variables;
use App\Adjust;

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
            $task_tp = $tk->time;
            $task_type = $tk->type;
            #$task_tp = $tk['time'];
            #$task_type = $tk['type'];
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

        $tasks = Task::period2($beforebefore_tp, $before_tp, 2);
        foreach ($tasks as $tk) {
            #$task_tp = $tk['time'];
            #$task_code = $tk['code'];
            $task_tp = $tk->time;
            $task_code = $tk->code;
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

        $tasks = Task::period3($beforebefore_tp, $before_tp, 4);
        $cheatings = array();
        foreach ($tasks as $tk) {
            #$task_tp = $tk['time'];
            #$task_code = $tk['code'];
            #$task_zid = $tk['zid'];
            $task_tp = $tk->time;
            $task_code = $tk->code;
            $task_zid = $tk->zid;
            $i = (int) (($task_tp - $beforebefore_tp) / $step_secord);
            if ($i == $stepNum) { $i -= 1; }
            if ($task_code == -1) {
                $res['datasets'][0]['data'][$i] += 1;
            }
            elseif ($task_code == 0) {
                $res['datasets'][1]['data'][$i] += 1;
            }
            elseif ($task_code == 1) {
                $res['datasets'][2]['data'][$i] += 1;
                $cheatings[] = $task_zid;
            }
            else {
                $res['datasets'][3]['data'][$i] += 1;
            }
        }
        $res = json_encode($res);

        $url = action('TaskController@ccstep', ['']);
        return compact('res', 'from_secord', 'to_secord', 'url', 'cheatings');
    }

    public function cheating() {
        $res = TaskController::cheating_core(60 * 3600, 0); 
        $res['title'] = "Xueqie Check Cheating";
        return view('task.cheating', $res);
    }

    public function ccstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = TaskController::cheating_core($from_secord, $to_secord); 
        return view('one_l_ft_js', $res);
    }

    protected function check_diff_multi($array1, $array2){
        $result = array();
        foreach($array1 as $key => $val) {
            if ($val == null && !isset($array2[$key])) continue;
            if(isset($array2[$key])){
                if(is_array($val)) {
                    if (is_array($array2[$key])) {
                        $res =  TaskController::check_diff_multi($val, $array2[$key]);
                        if ($res != null) {
                            $result[$key] = $res;
                        }
                    }
                    else {
                        $result[$key] = array($val, $array2[$key]);
                    }
                }
                elseif ($val != $array2[$key]) {
                    $result[$key] = array($val, $array2[$key]);
                }
            } else {
                $result[$key] = array($val, 'nonexist');
            }
        }
        if (count($result) == 0) return null;
        return $result;
    }

    protected function array2ul($array) {
        $out="<ul>";
        foreach($array as $key => $elem){
            if(!is_array($elem)){
                $out=$out."<li><span>$key:[$elem]</span></li>";
            }
            else $out=$out."<li><span>$key</span>".TaskController::array2ul($elem)."</li>";
        }
        $out=$out."</ul>";
        return $out; 
    }

    protected function special_merge($A, $old) {
        if (count($A) == 0) $lastid = PHP_INT_MAX;
        else $lastid = (int)($A[count($A) - 1]['id']);
        foreach ($old as $data) {
            $id = (int)($data['id']); 
            if ($id >= $lastid) { continue; }
            $A[] = $data;
        }
        return $A;
    }

    public function czh($zid) {
        $origin = Adjust::one($zid);
        $A = array();
        foreach ($origin as $data) {
            $A = TaskController::special_merge($A, json_decode($data['data'], true));
            #print_r($data['data']);
        }
        $new = Adjust::cheating($zid);
        $B = array();
        foreach ($new as $data) {
            $B = json_decode($data->data, true);
        }
        if (count($B) < 400) {
            $firstid = (int)($A[0]['id']);
            foreach ($B as $key => $data) {
                if ((int)($data['id']) > $firstid) {
                    continue;
                }
                break;
            }
            $B = array_slice($B, $key);
        }
        $res = TaskController::check_diff_multi($A, $B);
        $res = TaskController::array2ul($res);
        #$res = TaskController::array2ul($A);
        $title = "Cheating detail";

        return view('text', compact('res', 'title', 'zid'));
    }

    protected function viewtodb($data) {
        $info = $data['info'];
        $gain = $data['gain'];
        $adjust_common = $data['adjust_common'];
        $adjust_list = $data['adjust_list'];
        $adjust_cheating = $data['adjust_cheating'];

        $total = $info + $gain + $adjust_common + $adjust_list + $adjust_cheating;

        $gain += $info;
        $adjust_common += $gain;
        $adjust_list += $adjust_common;
        $adjust_cheating += $adjust_list;
        $info /= $total;
        $gain /= $total;
        $adjust_common /= $total;
        $adjust_list /= $total;
        $adjust_cheating /= $total;
        Variables::set('tasktype_prob_info', number_format($info, 3));
        Variables::set('tasktype_prob_gain', number_format($gain, 3));
        Variables::set('tasktype_prob_adjust_common', number_format($adjust_common, 3));
        Variables::set('tasktype_prob_adjust_list', number_format($adjust_list, 3));
        Variables::set('tasktype_prob_cheating', number_format($adjust_cheating, 3));
    }

    protected function dbtoview($data) {
        $info = $data['info'];
        $gain = $data['gain'];
        $adjust_common = $data['adjust_common'];
        $adjust_list = $data['adjust_list'];
        $adjust_cheating = $data['adjust_cheating'];

        $adjust_cheating -= $adjust_list;
        $adjust_list -= $adjust_common;
        $adjust_common -= $gain;
        $gain -= $info;
        return compact('info', 'gain', 'adjust_common', 'adjust_list', 'adjust_cheating');
    }


    public function manage(Request $request) {
        #var_dump(Input::all());
        $info = Variables::one('tasktype_prob_info');
        $gain = Variables::one('tasktype_prob_gain');
        $adjust_common = Variables::one('tasktype_prob_adjust_common');
        $adjust_list = Variables::one('tasktype_prob_adjust_list');
        $adjust_cheating = Variables::one('tasktype_prob_cheating');

        $info_date = $info['time'];
        $info = $info['value'];
        $gain_date = $gain['time'];
        $gain = $gain['value'];
        $adjust_common_date = $adjust_common['time'];
        $adjust_common = $adjust_common['value'];
        $adjust_list_date = $adjust_list['time'];
        $adjust_list = $adjust_list['value'];
        $adjust_cheating_date = $adjust_cheating['time'];
        $adjust_cheating= $adjust_cheating['value'];

        $res = TaskController::dbtoview(compact('info', 'gain', 'adjust_common', 'adjust_list', 'adjust_cheating'));
        $res['info_date'] = Carbon::createFromTimestamp($info_date, 'Europe/Zurich')->toDateTimeString();
        $res['gain_date'] = Carbon::createFromTimestamp($gain_date, 'Europe/Zurich')->toDateTimeString();
        $res['adjust_common_date'] = Carbon::createFromTimestamp($adjust_common_date, 'Europe/Zurich')->toDateTimeString();
        $res['adjust_list_date'] = Carbon::createFromTimestamp($adjust_list_date, 'Europe/Zurich')->toDateTimeString();
        $res['adjust_cheating_date'] = Carbon::createFromTimestamp($adjust_cheating_date, 'Europe/Zurich')->toDateTimeString();
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
        $adjust_cheating = TaskController::getnumber($request->adjustcprob);

        $total = $info + $gain + $adjust_common + $adjust_list + $adjust_cheating;
        if ($total != 0) {
            TaskController::viewtodb(compact('msg', 'info', 'gain', 'adjust_common', 'adjust_list', 'adjust_cheating'));
        }
        return redirect()->action('TaskController@manage');
    }
}

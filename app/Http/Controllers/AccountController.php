<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Account;
use App\Variables;

class AccountController extends Controller
{
    public function monitor_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 
        $mnt = Variables::chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum, 'begin', 'success', 'end');
        $codes = array();
        $codescount = 1;
        $accounts = Account::period($beforebefore_tp, $before_tp);
        foreach ($accounts as $account) {
            $time = $account['time'];
            $code = $account['code'];
            if (isset($codes[$code])) {
                $codes[$code] += 1;
                $codescount += 1;
            }
            else {
                $codes[$code] = 1;
                $codescount += 1;
            }
            $i = (int) (($time - $beforebefore_tp) / $step_secord);
            if ($i == $stepNum) { $i -= 1; }
            if ($code == -1) {
                $mnt['datasets'][0]['data'][$i] += 1;
            }
            elseif ($code == 0) {
                $mnt['datasets'][1]['data'][$i] += 1;
            }
            else {
                $mnt['datasets'][2]['data'][$i] += 1;
            }
        }
        $mnt = json_encode($mnt);

        $err = Variables::chartjs_bar_one();
        unset($codes[-1]);
        arsort($codes);
        $paes = Variables::paerror();
        foreach ($codes as $kind => $count) {
            $err['labels'][] = $paes[$kind] . '(' . number_format($count/$codescount * 100, 1) . '%)';
            $err['datasets'][0]['data'][] = $count;
        }
        $err = json_encode($err);
        $url = action('AccountController@mstep', ['']);
        return compact('mnt', 'err', 'from_secord', 'to_secord', 'url');
    }

    public function monitor() {
        $res = AccountController::monitor_core(3600 * 60, 0);
        return view('account.monitor', $res);
    }

    public function mstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = AccountController::monitor_core($from_secord, $to_secord); 
        return view('mjs', $res);
    }

    public function alist() {
        $accounts = Account::alist();
        $uns = array();
        $times = array();
        $last = array();
        foreach ($accounts as $account) {
            $username = $account['username'];
            $code = $account['code'];
            $time = $account['time'];
            if (isset($uns[$username][$code])) {
                $uns[$username][$code] += 1;
            }
            else {
                $uns[$username][$code] = 1;
            }
            if ($code == -1) {
                if (isset($last[$username])) {
                    $times[$username] += $last[$username] - $time;
                    $last[$username] = $time;
                }
                else {
                    $last[$username] = $time;
                    $times[$username] = 0;
                }
            }
        }
        $res = array();
        foreach ($uns as $username => $codes) {
            $tmp = array();
            $tmp[0] = $username;
            $tmp[1] = 0;
            $tmp[2] = 0;
            $tmp[3] = 0;
            $tmp[4] = 0;
            if ($codes['-1'] < 2) {
                $tmp[5] = 0;
            }
            else {
                $tmp[5] = Variables::secordtoHMS($times[$username] / ($codes['-1'] - 1));
            }
            foreach ($codes as $code => $count) {
                if ($code == 0) {
                    $tmp[1] = $count;
                }
                elseif ($code == 9302) {
                    $tmp[2] = $count;
                }
                elseif ($code == 9400) {
                    $tmp[3] = $count;
                }
                elseif ($code != -1) {
                    $tmp[4] += $count;
                }
            }
            $res[] = $tmp;
        }
        return view('account.alist', compact('res'));
    }

    public function info($un) {
        $un = str_replace('_', '.', $un);
        $uns = Account::info($un);
        $codes = array();
        foreach ($uns as $uc) {
            $code = $uc['code'];
            if (isset($codes[$code])) {
                $codes[$code] += 1;
            }
            else {
                $codes[$code] = 1;
            }
        }
        unset($codes[-1]);
        $res = Variables::chartjs_radar();
        $paes = Variables::paerror();
        foreach ($codes as $code => $count) {
            $res['labels'][] = $paes[$code];
            $res['datasets'][0]['data'][] = $count;
        }
        $res = json_encode($res);
        return view('account.info', compact('res', 'un'));
    }

    protected function health_core($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $account_time = array();
        $usage_rate = array( 'success' => 0, 'all' => 0,);
        $usage_rate_exact = array( 'success' => 0, 'all' => 0);
        $aliving = array();
        $codedist = array( 300 => array(), 3600 => array(), 14400 => array(), 'MORE' => array());

        $aset = array();
        $codeset = array();
        $slist = Account::period($beforebefore_tp, $before_tp);

        $total = 0.0;
        $interval = array();
        $last = array();
        $interval_count = array();
        foreach ($slist as $account) {
            $username = $account['username'];
            $time = $account['time'];
            $code = $account['code'];
            if ($code == -1) {
                $usage_rate['all'] += 1;
                if (isset($last[$username])) {
                    $interval[$username] += $last[$username] - $time;
                    $last[$username] = $time;
                    $interval_count[$username] += 1;
                }
                else {
                    $interval[$username] = 0;
                    $last[$username] = $time;
                    $interval_count[$username] = 0;
                }
            }
            elseif ($code == 0) {
                $usage_rate['success'] += 1;
            }

            if (isset($aset[$username])) {
                if ($code == -1) {
                    unset($aset[$username]);
                    unset($codeset[$username]);
                    $usage_rate_exact['all'] += 1;
                } 
                elseif ($code == 0) {
                    $keep =  $aset[$username] - $time;
                    $account_time[] = $keep;
                    $total += $keep;
                    $usage_rate_exact['success'] += 1;

                    if ($keep <= 300) { $keep = 300; }
                    elseif ($keep <= 3600) { $keep = 3600; }
                    elseif ($keep <= 14400) { $keep = 14400; }
                    else { $keep = 'MORE'; }

                    if (isset($codedist[$keep][$codeset[$username]])) {
                        $codedist[$keep][$codeset[$username]] += 1;
                    }
                    else {
                        $codedist[$keep][$codeset[$username]] = 1;
                    }
                }
                else {
                    return redirect('/');
                }
            }
            else {
                if ($code == -1) {
                    continue;
                }
                elseif ($code == 0) {
                    $aliving[$username] = Variables::secordtoHMS($now - $time);
                }
                else{
                    $aset[$username] = $time;
                    $codeset[$username] = $code;
                }
            }
        }

        $res = Variables::chartjs_line_one_inited_with_timedist('account');
        foreach ($account_time as $time) {
            $id = Variables::timedist_getindex($time);
            $res['datasets'][0]['data'][$id] += 1;
        }
        $res = json_encode($res);

        $all_interval = 0;
        $all_interval_count = 0;
        foreach ($interval as $username => $alltime) {
            $all_interval += $alltime;
            $all_interval_count += $interval_count[$username];
        }
        if ($all_interval_count != 0) {
            $ave_interval = Variables::secordtoHMS($all_interval / $all_interval_count);
        }
        else {
            $ave_interval = 0;
        }

        $sus = $usage_rate['success'];
        $all = $usage_rate['all'];
        $sus_e = $usage_rate_exact['success'];
        $all_e = $usage_rate_exact['all'];
        if ($all != 0) {
            $value = number_format(100 * $sus/$all, 2);
        }
        else {
            $value = 0;
        }
        if (count($account_time)) {
            $ave = Variables::secordtoHMS($total / count($account_time));
        }
        else {
            $ave = 0;
        }
        $usage = "$sus".'/'."$all".' ('."$sus_e".'/'."$all_e".') '."$value".'%. Average reuse interval: '."$ave_interval".'. Average alive duration: '."$ave".'.';
        $cnt = count($aliving); 
        $usage .= " Aliving account: $cnt.";

        $code_res_a = array();
        $paes = Variables::paerror();
        foreach ($codedist as $time => $codes) {
            $rest = Variables::chartjs_line_one('one');
            arsort($codes);
            foreach ($codes as $code => $count) {
                $rest['labels'][] = $paes[$code];
                $rest['datasets'][0]['data'][] = $count;
            }
            $code_res_a[$time] = json_encode($rest);
        }

        $url = action('AccountController@hstep', ['']);
        return compact('res', 'code_res_a', 'from_secord', 'to_secord', 'usage', 'aliving', 'url');
    }

    public function health() {
        $res = AccountController::health_core(3600 * 60, 0);
        return view('account.health', $res);
    }

    public function hstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = AccountController::health_core($from_secord, $to_secord);
        return view('account.hjs', $res);
    }

}

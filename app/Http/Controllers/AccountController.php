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
            if (isset($codes[(string)$code])) {
                $codes[(string)$code] += 1;
                $codescount += 1;
            }
            else {
                $codes[(string)$code] = 1;
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
        unset($codes['-1']);
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
        foreach ($accounts as $account) {
            $username = $account['username'];
            $code = $account['code'];
            if (isset($uns[$username][$code])) {
                $uns[$username][$code] += 1;
            }
            else {
                $uns[$username][$code] = 1;
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

        foreach ($slist as $account) {
            $username = $account['username'];
            $time = $account['time'];
            $code = $account['code'];
            if ($code == -1) {
                $usage_rate['all'] += 1;
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
        $usage = "$sus".'/'."$all".' ('."$sus_e".'/'."$all_e".') '."$value".'%.';
        $cnt = count($aliving); 
        $usage .= " Aliving account: $cnt.";

        $res = Variables::chartjs_line_one_inited_with_timedist('account');
        foreach ($account_time as $time) {
            $id = Variables::timedist_getindex($time);
            $res['datasets'][0]['data'][$id] += 1;
        }
        $res = json_encode($res);

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

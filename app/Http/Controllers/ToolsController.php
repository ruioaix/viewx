<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\ProxyController;
use App\Variables;
use App\Proxy;
use App\Account;

class ToolsController extends Controller
{
    protected function fixproxyrecord($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $begin = array();
        $success = array();
        $error = array();

        $ipportset = array();
        $slist = Proxy::period($beforebefore_tp, $before_tp);
        $ids = array();
        foreach ($slist as $proxy) {
            $id = $proxy['id'];
            $ipport = $proxy['ipv4_port'];
            $code = $proxy['code'];
            $time = $proxy['time'];
            if (isset($ipportset[$ipport])) {
                if ($code == -1) {
                    unset($ipportset[$ipport]);
                } 
                elseif ($code == 0) {
                    continue;
                }
                else {
                    $error[$id] = array($time, $ipport);
                    unset($ipportset[$ipport]);
                }
            }
            else {
                if ($code == -1) {
                    $begin[$ipport] = $time;
                    $ids[] = $id;
                }
                elseif ($code == 0) {
                    $success[$ipport] = $time;
                    $ids[] = $id;
                }
                else{
                    $ipportset[$ipport] = 1;
                }
            }
        }
        
        $beginlist = array();
        foreach ($begin as $ipport => $time) {
            $time = Variables::secordtoHMS($now - $time);
            $one = "$ipport".' => '."$time";
            if (isset($success[$ipport])) {
                $time = Variables::secordtoHMS($now - $success[$ipport]); 
                $one .= ' ('."$time".')';
                unset($success[$ipport]);
            }
            $beginlist[] = $one;
        }

        if (count($success)) {
            foreach ($success as $ipport => $time) {
                $error[] = array($time, $ipport);
            }
        }

        $errorlist = array();
        foreach ($error as $id => $proxy) {
            $ipport = $proxy[1];
            $time = Variables::secordtoHMS($now - $proxy[0]);
            $errorlist[$id] = "$ipport".' => '."$time";
        }

        return compact('beginlist', 'errorlist', 'ids');
    }

    protected function fixaccountrecord($from_secord, $to_secord) {
        $stepNum = Variables::getStepNum();
        $period_secord = $from_secord - $to_secord;
        $step_secord = (int)($period_secord / $stepNum);
        $now = time();
        #_tp means time point.
        $beforebefore_tp = $now - $from_secord;
        $before_tp = $now - $to_secord; 

        $begin = array();
        $success = array();
        $error = array();
        $ids = array();

        $userset = array();
        $slist = Account::period($beforebefore_tp, $before_tp);
        foreach ($slist as $one) {
            $id = $one['id'];
            $username = $one['username'];
            $code = $one['code'];
            $time = $one['time'];
            if (isset($userset[$username])) {
                if ($code == -1) {
                    unset($userset[$username]);
                } 
                elseif ($code == 0) {
                    continue;
                }
                else {
                    $error[$id] = array($time, $username);
                    unset($userset[$username]);
                }
            }
            else {
                if ($code == -1) {
                    $begin[$username] = $time;
                    $ids[] = $id;
                }
                elseif ($code == 0) {
                    $success[$username] = $time;
                    $ids[] = $id;
                }
                else{
                    $userset[$username] = 1;
                }
            }
        }
        
        $beginlist = array();
        foreach ($begin as $username => $time) {
            $time = Variables::secordtoHMS($now - $time);
            $one = "$username".' => '."$time";
            if (isset($success[$username])) {
                $time = Variables::secordtoHMS($now - $success[$username]); 
                $one .= ' ('."$time".')';
            }
            $beginlist[] = $one;
        }

        $errorlist = array();
        foreach ($error as $id => $one) {
            $username = $one[1];
            $time = Variables::secordtoHMS($now - $one[0]);
            $errorlist[$id] = "$username".' => '."$time";
        }

        return compact('beginlist', 'errorlist', 'ids');
    }

    public function fixparecord() {
        $from_secord = 3600 * 60;
        $to_secord = 0;
        $res = ToolsController::fixproxyrecord($from_secord, $to_secord);
        $proxy_bl = $res['beginlist'];
        $proxy_er = $res['errorlist'];
        $proxy_ids = $res['ids'];
        $res = ToolsController::fixaccountrecord($from_secord, $to_secord);
        $account_bl = $res['beginlist'];
        $account_er = $res['errorlist'];
        $account_ids = $res['ids'];
        $url = action('ToolsController@fstep', ['']);
        return view('tools.fixparecord', compact('proxy_bl', 'proxy_er', 'proxy_ids', 'account_bl', 'account_er', 'account_ids', 'url'));
    }

    public function fstep($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = ToolsController::fixproxyrecord($from_secord, $to_secord);
        $proxy_bl = $res['beginlist'];
        $proxy_er = $res['errorlist'];
        $proxy_ids = $res['ids'];
        $res = ToolsController::fixaccountrecord($from_secord, $to_secord);
        $account_bl = $res['beginlist'];
        $account_er = $res['errorlist'];
        $account_ids = $res['ids'];
        $url = action('ToolsController@fstep', ['']);
        return view('tools.fjs', compact('proxy_bl', 'proxy_er', 'proxy_ids', 'account_bl', 'account_er', 'account_ids', 'url'));
    }

    public function fstepS($from_to) {
        $args = explode("-", $from_to);
        $from_secord = (int)($args[0]) * 3600;
        $to_secord = (int)($args[1]) * 3600;
        $res = ToolsController::fixproxyrecord($from_secord, $to_secord);
        $proxy_ids = $res['ids'];
        $res = ToolsController::fixaccountrecord($from_secord, $to_secord);
        $account_ids = $res['ids'];

        #TODO
        #var_dump($proxy_ids);
        #var_dump($account_ids);

        return view('tools.fsjs');
    }
}

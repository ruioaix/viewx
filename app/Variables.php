<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variables extends Model
{
    protected $table = 'variables';

    protected function get($name) {
        $var = Variables::where('name', '=', $name)
            ->select('value')
            ->first();
        return $var;
    }

    protected function getcolor($id) {
        $colors = array(
            "rgba(171, 201, 221, 0.8)",
            "rgba(171, 201, 221, 1)",
            "rgba(174, 221, 204, 0.8)",
            "rgba(174, 221, 204, 1)",
            "rgba(238, 236, 193, 0.8)",
            "rgba(238, 236, 193, 1)",
            "rgba(0, 116, 217, 0.9)",
            "rgba(133, 20, 75, 0.5)",
            "rgba(46, 204, 64, 0.5)",
        );
        return $colors[$id];
    }

    protected function chartjs_line_three() {
        $data = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'label'=> "one",
                    'fillColor' => "rgba(0,0,0,0)",
                    'strokeColor' => Variables::getcolor(0),
                    'pointColor' => Variables::getcolor(1),
                    'pointStrokeColor'=> "#fff",
                    'pointHighlightFill'=> "#fff",
                    'pointHighlightStroke'=> Variables::getcolor(0),
                    'data'=> array()
                ),
                array( 
                    'label' => "two",
                    'fillColor'=> "rgba(0,0,0,0)",
                    'strokeColor'=> Variables::getcolor(2),
                    'pointColor'=> Variables::getcolor(3),
                    'pointStrokeColor' => "#fff",
                    'pointHighlightFill' => "#fff",
                    'pointHighlightStroke' => Variables::getcolor(2),
                    'data' => array()
                ),
                array(
                    'label'=> "three",
                    'fillColor' => "rgba(0,0,0,0)",
                    'strokeColor' => Variables::getcolor(4),
                    'pointColor' => Variables::getcolor(5),
                    'pointStrokeColor'=> "#fff",
                    'pointHighlightFill'=> "#fff",
                    'pointHighlightStroke'=> Variables::getcolor(4),
                    'data'=> array()
                ),
            ),
        );
        return $data;
    }

    protected function chartjs_line_three_inited_with_time($beforebefore_tp, $step_secord, $stepNum) {
        $data = Variables::chartjs_line_three();
        $now = time();
        for ($i = 0; $i < $stepNum; $i++ ) {
            $ts = $beforebefore_tp + $step_secord * ($i + 1);
            $dt = new \DateTime("@$ts");
            $dt->setTimeZone(new \DateTimeZone('Europe/Zurich'));
            $hours = (int)(($now - $ts)/3600);
            $data['labels'][$i] = $hours . 'h ' . $dt->format("m-d H:i");
            $data['datasets'][0]['data'][$i] = 0;
            $data['datasets'][1]['data'][$i] = 0;
            $data['datasets'][2]['data'][$i] = 0;
        }
        return $data;
    }

    protected function getStepNum() {
        return 60;
    }
    
    protected function chartjs_bar_one() {
        $data = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'label'=> "error",
                    'fillColor' => "rgba(151,187,205,0.5)",
                    'strokeColor' => "rgba(151,187,205,0.8)",
                    'highlightFill' => "rgba(151,187,205,0.75)",
                    'highlightStroke' => "rgba(151,187,205,1)",
                    'data'=> array()
                ),
            ),
        );
        return $data;
    }
}

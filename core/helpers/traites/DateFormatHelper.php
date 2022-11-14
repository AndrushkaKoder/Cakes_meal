<?php

namespace core\helpers\traites;

trait DateFormatHelper
{

    public static function dateFormat($date){

        if(!$date) return null;

        static $dateArr = [];

        if(is_object($date)){

            $dateData = $date;

            $date = $dateData->format('Y-m-d');

        }else{

            $dateData = new \DateTime($date);

        }

        if(isset($dateArr[$date])){

            return $dateArr[$date];

        }

        $daysArr = [
            'Sunday' => 'Воскресенье',
            'Monday' => 'Понедельник',
            'Tuesday' => 'Вторник',
            'Wednesday' => 'Среда',
            'Thursday' => 'Четверг',
            'Friday' => 'Пятница',
            'Saturday' => 'Суббота',
        ];

        $monthesArr = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];

        !is_string($date) && !is_object($date) && $date = '';

        $dateArr[$date]['year'] = $dateData->format('Y');

        $dateArr[$date]['month'] = $monthesArr[self::clearNum($dateData->format('m'))];

        $dateArr[$date]['monthFormat'] = preg_match('/т$/u', $dateArr[$date]['month']) ? $dateArr[$date]['month'] . 'а' :
            preg_replace('/[ьй]$/u', 'я', $dateArr[$date]['month']);

        $dateArr[$date]['weekDay'] = $daysArr[$dateData->format('l')];

        $dateArr[$date]['day'] = $dateData->format('d');

        $dateArr[$date]['time'] = $dateData->format('H:i:s');

        $dateArr[$date]['format'] = mb_strtolower($dateArr[$date]['day'] . ' ' .
            $dateArr[$date]['monthFormat'] . ' ' .
            $dateArr[$date]['year'] . ' года');

        return $dateArr[$date];

    }

}
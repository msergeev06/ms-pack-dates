<?php

namespace MSergeev\Packages\Dates\Tables;
use MSergeev\Core\Entity;
use \MSergeev\Core\Lib\DataManager;

class WorkCalendarTable extends DataManager {
    public static function getTableName () {
        return 'ms_dates_work_calendar';
    }
    public static function getTableTitle () {
        return 'Производственный календарь';
    }
    public static function getMap () {
        return array(
            new Entity\IntegerField("ID",array(
                "primary" => true,
                "autocomplete" => true,
                'title' => 'ID записи'
            )),
            new Entity\IntegerField("DAY",array(
                "required" => true,
                "title" => 'Число'
            )),
            new Entity\IntegerField("MONTH",array(
                "required" => true,
                "title" => 'Месяц'
            )),
            new Entity\IntegerField("YEAR",array(
                "required" => true,
                "title" => 'Год'
            )),
            new Entity\BooleanField("WEEKEND",array(
                "required" => true,
                "title" => 'Признак выходного дня'
            ))
        );
    }
}
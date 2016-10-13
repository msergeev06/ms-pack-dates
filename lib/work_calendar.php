<?php

namespace MSergeev\Packages\Dates\Lib;

use \MSergeev\Packages\Dates\Tables\WorkCalendarTable;
use MSergeev\Core\Lib\DateHelper;

class WorkCalendar {

	public static function isDayOff ($date=null)
	{
		if (is_null($date))
		{
			$date = date("d.m.Y");
		}
		//$date = "4.11.2016";

		$dateHelper = new DateHelper();

		list($day,$month,$year) = explode(".",$date);
		$timestamp = $dateHelper->getDateTimestamp($date);
		$dayOfWeek = date("w",$timestamp);
		if ($dayOfWeek==0) $dayOfWeek = 7;
		$res = WorkCalendarTable::getList(array(
			'filter' => array(
				'DAY' => intval($day),
				'MONTH' => intval($month),
				'YEAR' => intval($year)
			)
		));
		if (!$res)
		{
			//Если дата не найдена - смотрим день недели и отвечаем выходной он или нет
			if ($dayOfWeek>=1 && $dayOfWeek<=5)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			//Если дата найдена - смотрим выходной день или нет по флагу
			if ($res[0]["WEEKEND"] == "Y" || $res[0]["WEEKEND"]===true)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	public static function getNearestDates ($arDate,$weekend=true,$count=15)
	{
		if (
			!isset($arDate['day'])
			|| !isset($arDate['month'])
			|| !isset($arDate['year'])
		)
		{
			return false;
		}

		$arReturn = array();
		$mktime = mktime(0,0,0,$arDate['month'],$arDate['day'],$arDate['year']);
		$day = intval(date("d",$mktime));
		$month = intval(date ("m",$mktime));
		$year = intval(date("Y",$mktime));
		for ($i=0; $i<$count; $i++)
		{
			$mktime = mktime(0,0,0,$month,$day,$year);
			if ($i>0) {
				$mktime = strtotime("+1 day",$mktime);
			}
			$day = intval(date("d",$mktime));
			$month = intval(date ("m",$mktime));
			$year = intval(date("Y",$mktime));
			if ($res = WorkCalendarTable::getList(array(
				"filter" => array(
					"DAY" => $day,
					"MONTH" => $month,
					"YEAR" => $year,
					"WEEKEND" => $weekend
				)
			)))
			{
				if ($res[0]["WEEKEND"])
					$arReturn[$day.".".$month.".".$year] = 'Y';
				else
					$arReturn[$day.".".$month.".".$year] = 'N';
			}
			else
			{
				$arReturn[$day.".".$month.".".$year] = 'X';
			}
		}

		if (!empty($arReturn))
		{
			return $arReturn;
		}
		else
		{
			return false;
		}
	}


}
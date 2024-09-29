<?php
/*
## Transforming the decoding result of HYDRO

### Parent class reference:
http://dev.hsdn.org/wdparser/metar/

### License:
    Copyright (C) 2024, Spin Opel
    Copyright (C) 2013-2020, Information Networks, Ltd.
    Copyright (C) 2001-2006, Mark Woodward
*/

class HydroConv extends Hydro
{
	//echo "Дата получения данных"."\n";
	private function convDate($d_day) {
		date_default_timezone_set('UTC'); //временная зона по умолчанию
		if (isset($d_day) && (($d_day * 1) <= 31)) {
			$d_now = new DateTime();  //текущая дата сводки
			$d_year = $d_now->format('Y');	
			$d_month = $d_now->format('m');	
			$date_chg = $d_year."-".$d_month."-".$d_day;  //формат даты в виде Y-m-d
		} else {
			$date_chg = null;
		}
		return $date_chg;
	}
	
	//echo "\n"."Срок наблюдения, UTC"."\n";
	private function convTime($d_hour) {
		if (isset($d_hour) && (($d_hour * 1) <= 23)) {
			$date_time_format = $d_hour.":00:00";			
			$date = new DateTime($date_time_format, new DateTimeZone('Europe/Moscow')); //временная зона Беларуси
			$date->setTimezone(new DateTimeZone('UTC')); //временная зона по умолчанию
			$time_chg = $date->format('H:i:s'); //формат времени [часы]:[минуты]:[секунды]		
		} else {
			$time_chg = null;
		}
		return $time_chg;
	}

	//Пост обработка результатов, полученных из родительского класса
	public function convParam() {
		//echo "\n"."Дата получения данных"."\n";
		$this->observed_date = $this->convDate($this->dayr);

		//echo "\n"."Срок наблюдения, UTC"."\n";
		$this->observed_time = $this->convTime($this->hour_obs);

		//Дата и время в классическом формате
		//echo "\n"."Дата и время"."\n";
		if (isset($this->observed_date, $this->observed_time)) {
			$this->observed_date_time = $this->observed_date." ".$this->observed_time;
		}
	}
}
?>
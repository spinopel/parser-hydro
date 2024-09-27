<?php
/*
## Example of a line of raw HYDRO code in format KN-15

### Change the variable raw to get the result.
### The variable raw has the format KN-15.
*/

//$raw = '79121 09081 10244 20021 30243 458// 60000=';
$raw = '73115 09081 10542 20122 30547 424// 53703 62201 70454 83926 00512 92210 10300 20971 40803 51605 92209 10203 21042 407// 53032';
//$raw = '73115 09081 10542 20122 30547 424// 53703 62201 70454 83926 00512 93304 20502 70715 93330 10187 20303 30087 43600 51160 62435 70314';
//$raw = '73115 09081 10542 20122 30547 424// 53703 62201 70454 83926 00512 94405 10479 20478 30477 40180 50195 60170 72173 82184 94404 10478 20480 30480 40178 50183 60163 72200 82233';
//$raw = '73115 09081 10542 20122 30547 424// 53703 62201 70454 83926 00512 95505 15292 24533 33800 45280 54540 63800 75281 95504 15291 24541 33850 45278 54555 64100 75283';
//$raw = '73115 09081 10542 20122 30547 424// 53703 62201 70454 83926 00512 96604 11271 24124 34251 41270 50714 60203 76021 80709 96610 15042 20065 31725 40075 53109 60001 70010';
//$raw = '73115 09081 10542 20122 30547 424// 53703 62201 70454 83926 00512 97701 10996 24391 97703 51610 97704 84126 97705 00411 97706 97707';
//$raw = '73115 09081 10542 20122 30547 424// 53703 62201 70454 83926 00512 93305 20560 70708=';
//$raw = '79093 09081 15154 20142 30158 60000 90043 00043=';
//$raw = '79093 09081 15154 20142 30158 60000 60071 90043 00043 92205 62201 67785=';
//$raw = '73258 19081 10164 20012 30164 42299 56505 62406 70454 81369 90000 00000=';
//$raw = '79796 09081 10174 20071 30170 496// 62409 80172=';
//$raw = '79346 09081 10090 20000 30090 497// 62309=';


//подключаем классы для расшифровки HYDRO
require_once 'Hydro.php';
require_once 'HydroConv.php';

//удалить перевод каретки и конец строки в многострочной телеграмме
$arr_new_line = array("\n", "\r\n");  //специальные символы
$raw = str_replace($arr_new_line, '', $raw);

// Create class instance for parse HYDRO string with debug output enable
$HydroConv = new HydroConv($raw, TRUE);

// Parse HYDRO
$parameters = $HydroConv->parse();

//print_r($parameters); // get parsed parameters as array
echo "<br><br>";

// Debug information
$debug = $HydroConv->debug();
//print_r($debug); // get debug information as array
echo "<br><br>";

// Get all converted parameters
$HydroConv->convParam();

/*
## Отображаем результаты декодирования для наполнения БД
*/
echo "<br><br>Представление результатов декодирования Hydro для наполнения БД<br>";
echo $HydroConv->raw;

//Пост обработка полученных результатов
//DATAS` date NOT NULL DEFAULT '1000-01-01' COMMENT 'Дата получения данных',
echo "<br><br>Дата получения данных<br>";
echo $HydroConv->observed_date;

//TIMES` time NOT NULL DEFAULT '00:00:00' COMMENT 'Срок наблюдения, UTC',
echo "<br>Срок наблюдения, UTC<br>";
//echo $HydroConv->observed_time;
echo "05:00:00";

//Новый параметр - дата в классическом формате
//DateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата и время',
echo "<br>Дата и время<br>";
echo $HydroConv->observed_date_time;

//ID_STATION` varchar(5) NOT NULL DEFAULT '' COMMENT 'Индекс станции',
echo "<br>Индекс станции<br>";
echo $HydroConv->station_id;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
echo "<br>Уровень воды в срок наблюдений в текущие сутки, см<br>";
echo $HydroConv->water_level;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
echo "<br>Изменение уровня воды за 8-ой часовой срок наблюдения, см<br>";
echo $HydroConv->water_level_diff;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды за 20-ой часовой срок наблюдений, см',
echo "<br>Уровень воды за 20-ой часовой срок наблюдений, см<br>";
echo $HydroConv->water_level_last_20h;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воды, °C',
echo "<br>Температура воды, °C<br>";
echo $HydroConv->water_temp;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воздуха, °C',
echo "<br>Температура воздуха, °C<br>";
echo $HydroConv->air_temp;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
echo "<br>Характеристика ледовых явлений<br>";
echo $HydroConv->ice_phenomena;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
echo "<br>Характеристика 2-ого ледового явления<br>";
echo $HydroConv->ice_phenomena_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
echo "<br>Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %<br>";
echo $HydroConv->ice_p_intensity;

echo "<br>################## BEGIN Ледовые явления №1-2 ###################<br>";
//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений №2',
echo "<br>Характеристика ледовых явлений №2<br>";
echo $HydroConv->ice_phenomena_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления №2',
echo "<br>Характеристика 2-ого ледового явления №2<br>";
echo $HydroConv->ice_phenomena_1_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема) №2, %',
echo "<br>Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема) №2, %<br>";
echo $HydroConv->ice_p_intensity_1_2;
echo "<br>################## END Ледовые явления №1-2 ###################<br>";

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
echo "<br>Характеристика состояния реки<br>";
echo $HydroConv->condition_river;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
echo "<br>Характеристика 2-ого состояния реки<br>";
echo $HydroConv->condition_river_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
echo "<br>Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %<br>";
echo $HydroConv->cond_river_intensity;

echo "<br>################## BEGIN Состояние реки №1-2 ###################<br>";
//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
echo "<br>Характеристика состояния реки<br>";
echo $HydroConv->condition_river_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
echo "<br>Характеристика 2-ого состояния реки<br>";
echo $HydroConv->condition_river_1_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
echo "<br>Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %<br>";
echo $HydroConv->cond_river_intensity_1_2;
echo "<br>################## END Состояние реки №1-2 ###################<br>";

//Temp` varchar(5) DEFAULT NULL COMMENT 'Толщина льда, см',
echo "<br>Толщина льда, см<br>";
echo $HydroConv->ice_thickness;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Высота снежного покрова на льду, см',
echo "<br>Высота снежного покрова на льду, см<br>";
echo $HydroConv->snow_depth_on_ice;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Ежедневный расход воды, м^3/с',
echo "<br>Ежедневный расход воды, м^3/с<br>";
echo $HydroConv->w_consumption;

//Precipitation amount
echo "<br>Количество осадков, мм<br>";
echo $HydroConv->precip;

//Precipitation duration
echo "<br>Продолжительность выпадения осадков, ч<br>";
echo $HydroConv->precip_duration;


/*################## BEGIN SECTION 2 ###################*/
echo "<br>################## BEGIN SECTION 2_1 ###################<br>";
echo "<br>################## за ".$HydroConv->monthdayr__section_2_1." число этого месяца ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
echo "<br>Уровень воды в срок наблюдений в текущие сутки, см<br>";
echo $HydroConv->water_level__section_2_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
echo "<br>Изменение уровня воды за 8-ой часовой срок наблюдения, см<br>";
echo $HydroConv->water_level_diff__section_2_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды за 20-ой часовой срок наблюдений, см',
echo "<br>Уровень воды за 20-ой часовой срок наблюдений, см<br>";
echo $HydroConv->water_level_last_20h__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воды, °C',
echo "<br>Температура воды, °C<br>";
echo $HydroConv->water_temp__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воздуха, °C',
echo "<br>Температура воздуха, °C<br>";
echo $HydroConv->air_temp__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
echo "<br>Характеристика ледовых явлений<br>";
echo $HydroConv->ice_phenomena__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
echo "<br>Характеристика 2-ого ледового явления<br>";
echo $HydroConv->ice_phenomena_2__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
echo "<br>Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %<br>";
echo $HydroConv->ice_p_intensity__section_2_1;

echo "<br>################## BEGIN Ледовые явления №2_1-2 ###################<br>";
//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений №2_1-2',
echo "<br>Характеристика ледовых явлений №2_1-2<br>";
echo $HydroConv->ice_phenomena__section_2_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления №2_1-2',
echo "<br>Характеристика 2-ого ледового явления №2_1-2<br>";
echo $HydroConv->ice_phenomena_2__section_2_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема) №2_1-2, %',
echo "<br>Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема) №2_1-2, %<br>";
echo $HydroConv->ice_p_intensity__section_2_1_2;
echo "<br>################## END Ледовые явления №2_1-2 ###################<br>";

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
echo "<br>Характеристика состояния реки<br>";
echo $HydroConv->condition_river__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
echo "<br>Характеристика 2-ого состояния реки<br>";
echo $HydroConv->condition_river_2__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
echo "<br>Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %<br>";
echo $HydroConv->cond_river_intensity__section_2_1;

echo "<br>################## BEGIN Состояние реки №2_1-2 ###################<br>";
//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки №2_1-2',
echo "<br>Характеристика состояния реки №2_1-2<br>";
echo $HydroConv->condition_river__section_2_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки №2_1-2',
echo "<br>Характеристика 2-ого состояния реки №2_1-2<br>";
echo $HydroConv->condition_river_2__section_2_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема) №2_1-2, %',
echo "<br>Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема) №2_1-2, %<br>";
echo $HydroConv->cond_river_intensity__section_2_1_2;
echo "<br>################## END Состояние реки №2_1-2 ###################<br>";

//Temp` varchar(5) DEFAULT NULL COMMENT 'Толщина льда, см',
echo "<br>Толщина льда, см<br>";
echo $HydroConv->ice_thickness__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Высота снежного покрова на льду, см',
echo "<br>Высота снежного покрова на льду, см<br>";
echo $HydroConv->snow_depth_on_ice__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Ежедневный расход воды, м^3/с',
echo "<br>Ежедневный расход воды, м^3/с<br>";
echo $HydroConv->w_consumption__section_2_1;

//Precipitation amount
echo "<br>Количество осадков, мм<br>";
echo $HydroConv->precip__section_2_1;

//Precipitation duration
echo "<br>Продолжительность выпадения осадков, ч<br>";
echo $HydroConv->precip_duration__section_2_1;

echo "<br>################## END SECTION 2_1 ###################<br>";
/*################## END SECTION 2_1 ###################*/

/*################## BEGIN SECTION 2_2 ###################*/
echo "<br>################## BEGIN SECTION 2_2 ###################<br>";
echo "<br>################## за ".$HydroConv->monthdayr__section_2_2." число этого месяца ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
echo "<br>Уровень воды в срок наблюдений в текущие сутки, см<br>";
echo $HydroConv->water_level__section_2_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
echo "<br>Изменение уровня воды за 8-ой часовой срок наблюдения, см<br>";
echo $HydroConv->water_level_diff__section_2_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды за 20-ой часовой срок наблюдений, см',
echo "<br>Уровень воды за 20-ой часовой срок наблюдений, см<br>";
echo $HydroConv->water_level_last_20h__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воды, °C',
echo "<br>Температура воды, °C<br>";
echo $HydroConv->water_temp__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воздуха, °C',
echo "<br>Температура воздуха, °C<br>";
echo $HydroConv->air_temp__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
echo "<br>Характеристика ледовых явлений<br>";
echo $HydroConv->ice_phenomena__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
echo "<br>Характеристика 2-ого ледового явления<br>";
echo $HydroConv->ice_phenomena_2__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
echo "<br>Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %<br>";
echo $HydroConv->ice_p_intensity__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
echo "<br>Характеристика состояния реки<br>";
echo $HydroConv->condition_river__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
echo "<br>Характеристика 2-ого состояния реки<br>";
echo $HydroConv->condition_river_2__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
echo "<br>Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %<br>";
echo $HydroConv->cond_river_intensity__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Толщина льда, см',
echo "<br>Толщина льда, см<br>";
echo $HydroConv->ice_thickness__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Высота снежного покрова на льду, см',
echo "<br>Высота снежного покрова на льду, см<br>";
echo $HydroConv->snow_depth_on_ice__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Ежедневный расход воды, м^3/с',
echo "<br>Ежедневный расход воды, м^3/с<br>";
echo $HydroConv->w_consumption__section_2_2;

//Precipitation amount
echo "<br>Количество осадков, мм<br>";
echo $HydroConv->precip__section_2_2;

//Precipitation duration
echo "<br>Продолжительность выпадения осадков, ч<br>";
echo $HydroConv->precip_duration__section_2_2;

echo "<br>################## END SECTION 2_2 ###################<br>";
/*################## END SECTION 2 ###################*/


/*################## BEGIN SECTION 3 ###################*/
echo "<br>################## BEGIN SECTION 3_1 ###################<br>";
echo "<br>################## ".$HydroConv->period_avg_extreme_3_1." сведения о средних и эксремальных значениях ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний уровень воды за период, см',
echo "<br>Средний уровень воды за период, см<br>";
echo $HydroConv->water_level_avg__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высший уровень воды за период, см',
echo "<br>Высший уровень воды за период, см<br>";
echo $HydroConv->water_level_highest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Низший уровень воды за период, см',
echo "<br>Низший уровень воды за период, см<br>";
echo $HydroConv->water_level_lowest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний расход (приток) воды за период, м^3/с',
echo "<br>Средний расход (приток) воды за период, м^3/с<br>";
echo $HydroConv->w_consumption_avg__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Наибольший расход (приток) воды за период, м^3/с',
echo "<br>Наибольший расход (приток) воды за период, м^3/с<br>";
echo $HydroConv->w_consumption_highest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Наименьший расход (приток) воды за период, м^3/с',
echo "<br>Наименьший расход (приток) воды за период, м^3/с<br>";
echo $HydroConv->w_consumption_lowest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Дата (число месяца) прохождения наивысшего уровня (расхода) воды',
echo "<br>Дата (число месяца) прохождения наивысшего уровня (расхода) воды<br>";
echo $HydroConv->monthdayr_water_level_highest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Час местного времени прохождения наивысшего уровня (расхода) воды',
echo "<br>Час местного времени прохождения наивысшего уровня (расхода) воды<br>";
echo $HydroConv->hourr_water_level_highest__section_3_1;

echo "<br>################## END SECTION 3_1 ###################<br>";

echo "<br>################## BEGIN SECTION 3_2 ###################<br>";
echo "<br>################## ".$HydroConv->period_avg_extreme_3_2." сведения о средних и эксремальных значениях ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний уровень воды за период, см',
echo "<br>Средний уровень воды за период, см<br>";
echo $HydroConv->water_level_avg__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высший уровень воды за период, см',
echo "<br>Высший уровень воды за период, см<br>";
echo $HydroConv->water_level_highest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Низший уровень воды за период, см',
echo "<br>Низший уровень воды за период, см<br>";
echo $HydroConv->water_level_lowest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний расход (приток) воды за период, м^3/с',
echo "<br>Средний расход (приток) воды за период, м^3/с<br>";
echo $HydroConv->w_consumption_avg__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Наибольший расход (приток) воды за период, м^3/с',
echo "<br>Наибольший расход (приток) воды за период, м^3/с<br>";
echo $HydroConv->w_consumption_highest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Наименьший расход (приток) воды за период, м^3/с',
echo "<br>Наименьший расход (приток) воды за период, м^3/с<br>";
echo $HydroConv->w_consumption_lowest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Дата (число месяца) прохождения наивысшего уровня (расхода) воды',
echo "<br>Дата (число месяца) прохождения наивысшего уровня (расхода) воды<br>";
echo $HydroConv->monthdayr_water_level_highest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Час местного времени прохождения наивысшего уровня (расхода) воды',
echo "<br>Час местного времени прохождения наивысшего уровня (расхода) воды<br>";
echo $HydroConv->hourr_water_level_highest__section_3_2;

echo "<br>################## END SECTION 3_2 ###################<br>";
/*################## END SECTION 3 ###################*/


/*################## BEGIN SECTION 4 ###################*/
echo "<br>################## BEGIN SECTION 4_1 ###################<br>";
echo "<br>################## за ".$HydroConv->monthdayr__section_4_1." число этого месяца ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды верхнего бьефа гидроузла, см',
echo "<br>Уровень воды верхнего бьефа гидроузла, см<br>";
echo $HydroConv->water_level_upper_pool__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний (по площади) уровень водохранилища в срок наблюдений, см',
echo "<br>Средний (по площади) уровень водохранилища в срок наблюдений, см<br>";
echo $HydroConv->water_level_avg_current__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний (по площади) уровень водохранилища на конец предшествующих календарных суток, см',
echo "<br>Средний (по площади) уровень водохранилища на конец предшествующих календарных суток, см<br>";
echo $HydroConv->water_level_avg_post__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды нижнего бьефа в срок наблюдений, см',
echo "<br>Уровень воды нижнего бьефа в срок наблюдений, см<br>";
echo $HydroConv->water_level_lower_pool_current__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высший уровень воды нижнего бьефа за предшествующие сутки, см',
echo "<br>Высший уровень воды нижнего бьефа за предшествующие сутки, см<br>";
echo $HydroConv->water_level_lower_pool_hilevel_post__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Низший уровень воды нижнего бьефа за предшествующие сутки, см',
echo "<br>Низший уровень воды нижнего бьефа за предшествующие сутки, см<br>";
echo $HydroConv->water_level_lower_pool_lolevel_post__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Объем воды в водохранилище по среднему уровню в срок наблюдений, млн. м^3',
echo "<br>Объем воды в водохранилище по среднему уровню в срок наблюдений, млн. м^3<br>";
echo $HydroConv->w_volume_avg_current__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Объем воды в водохранилище по среднему уровню на конец предш. календарных суток, млн. м^3',
echo "<br>Объем воды в водохранилище по среднему уровню на конец предш. календарных суток, млн. м^3<br>";
echo $HydroConv->w_volume_avg_post__section_4_1;

echo "<br>################## END SECTION 4_1 ###################<br>";

echo "<br>################## BEGIN SECTION 4_2 ###################<br>";
echo "<br>################## за ".$HydroConv->monthdayr__section_4_2." число этого месяца ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды верхнего бьефа гидроузла, см',
echo "<br>Уровень воды верхнего бьефа гидроузла, см<br>";
echo $HydroConv->water_level_upper_pool__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний (по площади) уровень водохранилища в срок наблюдений, см',
echo "<br>Средний (по площади) уровень водохранилища в срок наблюдений, см<br>";
echo $HydroConv->water_level_avg_current__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний (по площади) уровень водохранилища на конец предшествующих календарных суток, см',
echo "<br>Средний (по площади) уровень водохранилища на конец предшествующих календарных суток, см<br>";
echo $HydroConv->water_level_avg_post__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды нижнего бьефа в срок наблюдений, см',
echo "<br>Уровень воды нижнего бьефа в срок наблюдений, см<br>";
echo $HydroConv->water_level_lower_pool_current__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высший уровень воды нижнего бьефа за предшествующие сутки, см',
echo "<br>Высший уровень воды нижнего бьефа за предшествующие сутки, см<br>";
echo $HydroConv->water_level_lower_pool_hilevel_post__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Низший уровень воды нижнего бьефа за предшествующие сутки, см',
echo "<br>Низший уровень воды нижнего бьефа за предшествующие сутки, см<br>";
echo $HydroConv->water_level_lower_pool_lolevel_post__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Объем воды в водохранилище по среднему уровню в срок наблюдений, млн. м^3',
echo "<br>Объем воды в водохранилище по среднему уровню в срок наблюдений, млн. м^3<br>";
echo $HydroConv->w_volume_avg_current__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Объем воды в водохранилище по среднему уровню на конец предш. календарных суток, млн. м^3',
echo "<br>Объем воды в водохранилище по среднему уровню на конец предш. календарных суток, млн. м^3<br>";
echo $HydroConv->w_volume_avg_post__section_4_2;

echo "<br>################## END SECTION 4_2 ###################<br>";
/*################## END SECTION 4 ###################*/


/*################## BEGIN SECTION 5 ###################*/
echo "<br>################## BEGIN SECTION 5_1 ###################<br>";
echo "<br>################## за ".$HydroConv->monthdayr__section_5_1." число этого месяца ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Общий приток воды в срок наблюдений, м^3/с',
echo "<br>Общий приток воды в срок наблюдений, м^3/с<br>";
echo $HydroConv->w_inflow_total_current__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Боковой приток воды в срок наблюдений, м^3/с',
echo "<br>Боковой приток воды в срок наблюдений, м^3/с<br>";
echo $HydroConv->w_inflow_lateral_current__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Приток воды к акватории водохронилища в срок наблюдений, м^3/с',
echo "<br>Приток воды к акватории водохронилища в срок наблюдений, м^3/с<br>";
echo $HydroConv->w_inflow_area_current__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Общий приток воды, средний за предшествующие сутки, м^3/с',
echo "<br>Общий приток воды, средний за предшествующие сутки, м^3/с<br>";
echo $HydroConv->w_inflow_total_post__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Боковой приток воды, средний за предшествующие сутки, м^3/с',
echo "<br>Боковой приток воды, средний за предшествующие сутки, м^3/с<br>";
echo $HydroConv->w_inflow_lateral_post__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Приток воды к акватории водохронилища, средний за предшествующие сутки, м^3/с',
echo "<br>Приток воды к акватории водохронилища, средний за предшествующие сутки, м^3/с<br>";
echo $HydroConv->w_inflow_area_post__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Сброс воды через гидроузел, средний за предшествующие сутки, м^3/с',
echo "<br>Сброс воды через гидроузел, средний за предшествующие сутки, м^3/с<br>";
echo $HydroConv->w_inflow_discharge_post__section_5_1;

echo "<br>################## END SECTION 5_1 ###################<br>";

echo "<br>################## BEGIN SECTION 5_2 ###################<br>";
echo "<br>################## за ".$HydroConv->monthdayr__section_5_2." число этого месяца ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Общий приток воды в срок наблюдений, м^3/с',
echo "<br>Общий приток воды в срок наблюдений, м^3/с<br>";
echo $HydroConv->w_inflow_total_current__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Боковой приток воды в срок наблюдений, м^3/с',
echo "<br>Боковой приток воды в срок наблюдений, м^3/с<br>";
echo $HydroConv->w_inflow_lateral_current__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Приток воды к акватории водохронилища в срок наблюдений, м^3/с',
echo "<br>Приток воды к акватории водохронилища в срок наблюдений, м^3/с<br>";
echo $HydroConv->w_inflow_area_current__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Общий приток воды, средний за предшествующие сутки, м^3/с',
echo "<br>Общий приток воды, средний за предшествующие сутки, м^3/с<br>";
echo $HydroConv->w_inflow_total_post__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Боковой приток воды, средний за предшествующие сутки, м^3/с',
echo "<br>Боковой приток воды, средний за предшествующие сутки, м^3/с<br>";
echo $HydroConv->w_inflow_lateral_post__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Приток воды к акватории водохронилища, средний за предшествующие сутки, м^3/с',
echo "<br>Приток воды к акватории водохронилища, средний за предшествующие сутки, м^3/с<br>";
echo $HydroConv->w_inflow_area_post__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Сброс воды через гидроузел, средний за предшествующие сутки, м^3/с',
echo "<br>Сброс воды через гидроузел, средний за предшествующие сутки, м^3/с<br>";
echo $HydroConv->w_inflow_discharge_post__section_5_2;

echo "<br>################## END SECTION 5_2 ###################<br>";
/*################## END SECTION 5 ###################*/


/*################## BEGIN SECTION 6 ###################*/
echo "<br>################## BEGIN SECTION 6_1 ###################<br>";
echo "<br>################## за ".$HydroConv->month_flow_6_1." месяц измерений расхода воды ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды для секции №6_1, см',
echo "<br>Уровень воды для секции №6_1, см<br>";
echo $HydroConv->water_level_avg__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Измеренный расход воды для секции №6_1, м^3/с',
echo "<br>Измеренный расход воды для секции №6_1, м^3/с<br>";
echo $HydroConv->w_consumption_avg__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Площадь живого сечения реки, м^2',
echo "<br>Площадь живого сечения реки, м^2<br>";
echo $HydroConv->river_area__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Максимальная глубина на гидрорастворе, см',
echo "<br>Максимальная глубина на гидрорастворе, см<br>";
echo $HydroConv->max_depth__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Дата измерения расхода воды',
echo "<br>Число месяца измерения расхода воды<br>";
echo $HydroConv->monthdayr_water_flow__section_6_1;
echo "<br>Час по местному времени, к которому отнесено измерение расхода воды<br>";
echo $HydroConv->hourr_water_flow__section_6_1;

echo "<br>################## за ".$HydroConv->month_surface_6_1." месяц наблюдений за состоянием поверхности озера (водохранилища) ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Направление ветра на озере или водохранилище',
echo "<br>Направление ветра на озере или водохранилище<br>";
echo $HydroConv->wind_direction__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Скорость ветра на озере или водохранилище, м/с',
echo "<br>Скорость ветра на озере или водохранилище, м/с<br>";
echo $HydroConv->wind_speed__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Волненение на озере или водохранилище, откуда идет волна',
echo "<br>Волненение на озере или водохранилище, откуда идет волна<br>";
echo $HydroConv->wind_direction_waves__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высота ветровых волн, дециметры',
echo "<br>Высота ветровых волн, дециметры<br>";
echo $HydroConv->wave_height__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Характеристика состояния поверхности водоема',
echo "<br>Характеристика состояния поверхности водоема<br>";
echo $HydroConv->state_surface__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Время наблюдения за ветром и волнением воды',
echo "<br>Число месяца наблюдения за ветром и волнением воды<br>";
echo $HydroConv->monthdayr_water_waves__section_6_1;
echo "<br>Час по местному времени, наблюдения за ветром и волнением воды<br>";
echo $HydroConv->hourr_water_waves__section_6_1;

echo "<br>################## END SECTION 6_1 ###################<br>";

echo "<br>################## BEGIN SECTION 6_2 ###################<br>";
echo "<br>################## за ".$HydroConv->month_flow_6_2." месяц измерений расхода воды ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды для секции №6_2, см',
echo "<br>Уровень воды для секции №6_2, см<br>";
echo $HydroConv->water_level_avg__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Измеренный расход воды для секции №6_2, м^3/с',
echo "<br>Измеренный расход воды для секции №6_2, м^3/с<br>";
echo $HydroConv->w_consumption_avg__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Площадь живого сечения реки, м^2',
echo "<br>Площадь живого сечения реки, м^2<br>";
echo $HydroConv->river_area__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Максимальная глубина на гидрорастворе, см',
echo "<br>Максимальная глубина на гидрорастворе, см<br>";
echo $HydroConv->max_depth__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Дата измерения расхода воды',
echo "<br>Число месяца измерения расхода воды<br>";
echo $HydroConv->monthdayr_water_flow__section_6_2;
echo "<br>Час по местному времени, к которому отнесено измерение расхода воды<br>";
echo $HydroConv->hourr_water_flow__section_6_2;

echo "<br>################## за ".$HydroConv->month_surface_6_2." месяц наблюдений за состоянием поверхности озера (водохранилища) ###################<br>";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Направление ветра на озере или водохранилище',
echo "<br>Направление ветра на озере или водохранилище<br>";
echo $HydroConv->wind_direction__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Скорость ветра на озере или водохранилище, м/с',
echo "<br>Скорость ветра на озере или водохранилище, м/с<br>";
echo $HydroConv->wind_speed__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Волненение на озере или водохранилище, откуда идет волна',
echo "<br>Волненение на озере или водохранилище, откуда идет волна<br>";
echo $HydroConv->wind_direction_waves__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высота ветровых волн, дециметры',
echo "<br>Высота ветровых волн, дециметры<br>";
echo $HydroConv->wave_height__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Характеристика состояния поверхности водоема',
echo "<br>Характеристика состояния поверхности водоема<br>";
echo $HydroConv->state_surface__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Время наблюдения за ветром и волнением воды',
echo "<br>Число месяца наблюдения за ветром и волнением воды<br>";
echo $HydroConv->monthdayr_water_waves__section_6_2;
echo "<br>Час по местному времени, наблюдения за ветром и волнением воды<br>";
echo $HydroConv->hourr_water_waves__section_6_2;

echo "<br>################## END SECTION 6_2 ###################<br>";
/*################## END SECTION 6 ###################*/


/*################## BEGIN SECTION 7 ###################*/
echo "<br>################## BEGIN SECTION 7_1 ###################<br>";
if ($HydroConv->about_dangerous__section_7_1 != NULL) {
	echo "<br>################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_1." ###################<br>";

	//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
	echo "<br>Уровень воды в срок наблюдений в текущие сутки, см<br>";
	echo $HydroConv->water_level__section_7_1;

	//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
	echo "<br>Изменение уровня воды за 8-ой часовой срок наблюдения, см<br>";
	echo $HydroConv->water_level_diff__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
	echo "<br>Характеристика ледовых явлений<br>";
	echo $HydroConv->ice_phenomena__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
	echo "<br>Характеристика 2-ого ледового явления<br>";
	echo $HydroConv->ice_phenomena_2__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
	echo "<br>Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %<br>";
	echo $HydroConv->ice_p_intensity__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
	echo "<br>Характеристика состояния реки<br>";
	echo $HydroConv->condition_river__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
	echo "<br>Характеристика 2-ого состояния реки<br>";
	echo $HydroConv->condition_river_2__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
	echo "<br>Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %<br>";
	echo $HydroConv->cond_river_intensity__section_7_1;
}
echo "<br>################## END SECTION 7_1 ###################<br>";

echo "<br>################## BEGIN SECTION 7_2 ###################<br>";
if ($HydroConv->about_dangerous__section_7_2 != NULL) {
	echo "<br>################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_2." ###################<br>";

	//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
	echo "<br>Уровень воды в срок наблюдений в текущие сутки, см<br>";
	echo $HydroConv->water_level__section_7_2;

	//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
	echo "<br>Изменение уровня воды за 8-ой часовой срок наблюдения, см<br>";
	echo $HydroConv->water_level_diff__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
	echo "<br>Характеристика ледовых явлений<br>";
	echo $HydroConv->ice_phenomena__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
	echo "<br>Характеристика 2-ого ледового явления<br>";
	echo $HydroConv->ice_phenomena_2__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
	echo "<br>Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %<br>";
	echo $HydroConv->ice_p_intensity__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
	echo "<br>Характеристика состояния реки<br>";
	echo $HydroConv->condition_river__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
	echo "<br>Характеристика 2-ого состояния реки<br>";
	echo $HydroConv->condition_river_2__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
	echo "<br>Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %<br>";
	echo $HydroConv->cond_river_intensity__section_7_2;
}
echo "<br>################## END SECTION 7_2 ###################<br>";

echo "<br>################## BEGIN SECTION 7_3 ###################<br>";
if ($HydroConv->about_dangerous__section_7_3 != NULL) {
	echo "<br>################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_3." ###################<br>";

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
	echo "<br>Характеристика ледовых явлений<br>";
	echo $HydroConv->ice_phenomena__section_7_3;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
	echo "<br>Характеристика 2-ого ледового явления<br>";
	echo $HydroConv->ice_phenomena_2__section_7_3;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
	echo "<br>Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %<br>";
	echo $HydroConv->ice_p_intensity__section_7_3;
}
echo "<br>################## END SECTION 7_3 ###################<br>";

echo "<br>################## BEGIN SECTION 7_4 ###################<br>";
if ($HydroConv->about_dangerous__section_7_4 != NULL) {
	echo "<br>################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_4." ###################<br>";

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Ежедневный расход воды, м^3/с',
	echo "<br>Ежедневный расход воды, м^3/с<br>";
	echo $HydroConv->w_consumption__section_7_4;
}
echo "<br>################## END SECTION 7_4 ###################<br>";

echo "<br>################## BEGIN SECTION 7_5 ###################<br>";
if ($HydroConv->about_dangerous__section_7_5 != NULL) {
	echo "<br>################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_5." ###################<br>";

	//Precipitation amount
	echo "<br>Количество осадков, мм<br>";
	echo $HydroConv->precip__section_7_5;

	//Precipitation duration
	echo "<br>Продолжительность выпадения осадков, ч<br>";
	echo $HydroConv->precip_duration__section_7_5;
}
echo "<br>################## END SECTION 7_5 ###################<br>";

echo "<br>################## BEGIN SECTION 7_6 ###################<br>";
if ($HydroConv->about_dangerous__section_7_6 != NULL) {
	echo "<br>################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_6." ###################<br>";
}
echo "<br>################## END SECTION 7_6 ###################<br>";
		
echo "<br>################## BEGIN SECTION 7_7 ###################<br>";
if ($HydroConv->about_dangerous__section_7_7 != NULL) {
	echo "<br>################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_7." ###################<br>";
}
echo "<br>################## END SECTION 7_7 ###################<br>";

/*################## END SECTION 7 ###################*/
?>
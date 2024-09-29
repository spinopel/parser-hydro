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

//print_r($parameters)."\n\n"; // get parsed parameters as array

// Debug information
$debug = $HydroConv->debug();
//print_r($debug)."\n\n"; // get debug information as array

// Get all converted parameters
$HydroConv->convParam();

/*
## Отображаем результаты декодирования для наполнения БД
*/
echo "\n\n"."Представление результатов декодирования Hydro для наполнения БД"."\n";
echo $HydroConv->raw;

//Пост обработка полученных результатов
//DATAS` date NOT NULL DEFAULT '1000-01-01' COMMENT 'Дата получения данных',
echo "\n"."Дата получения данных"."\n";
echo $HydroConv->observed_date;

//TIMES` time NOT NULL DEFAULT '00:00:00' COMMENT 'Срок наблюдения, UTC',
echo "\n"."Срок наблюдения, UTC"."\n";
//echo $HydroConv->observed_time;
echo "05:00:00";

//Новый параметр - дата в классическом формате
//DateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата и время',
echo "\n"."Дата и время"."\n";
echo $HydroConv->observed_date_time;

//ID_STATION` varchar(5) NOT NULL DEFAULT '' COMMENT 'Индекс станции',
echo "\n"."Индекс станции"."\n";
echo $HydroConv->station_id;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
echo "\n"."Уровень воды в срок наблюдений в текущие сутки, см"."\n";
echo $HydroConv->water_level;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
echo "\n"."Изменение уровня воды за 8-ой часовой срок наблюдения, см"."\n";
echo $HydroConv->water_level_diff;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды за 20-ой часовой срок наблюдений, см',
echo "\n"."Уровень воды за 20-ой часовой срок наблюдений, см"."\n";
echo $HydroConv->water_level_last_20h;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воды, °C',
echo "\n"."Температура воды, °C"."\n";
echo $HydroConv->water_temp;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воздуха, °C',
echo "\n"."Температура воздуха, °C"."\n";
echo $HydroConv->air_temp;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
echo "\n"."Характеристика ледовых явлений"."\n";
echo $HydroConv->ice_phenomena;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
echo "\n"."Характеристика 2-ого ледового явления"."\n";
echo $HydroConv->ice_phenomena_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
echo "\n"."Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %"."\n";
echo $HydroConv->ice_p_intensity;

echo "\n"."################## BEGIN Ледовые явления №1-2 ###################"."\n";
//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений №2',
echo "\n"."Характеристика ледовых явлений №2"."\n";
echo $HydroConv->ice_phenomena_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления №2',
echo "\n"."Характеристика 2-ого ледового явления №2"."\n";
echo $HydroConv->ice_phenomena_1_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема) №2, %',
echo "\n"."Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема) №2, %"."\n";
echo $HydroConv->ice_p_intensity_1_2;
echo "\n"."################## END Ледовые явления №1-2 ###################"."\n";

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
echo "\n"."Характеристика состояния реки"."\n";
echo $HydroConv->condition_river;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
echo "\n"."Характеристика 2-ого состояния реки"."\n";
echo $HydroConv->condition_river_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
echo "\n"."Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %"."\n";
echo $HydroConv->cond_river_intensity;

echo "\n"."################## BEGIN Состояние реки №1-2 ###################"."\n";
//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
echo "\n"."Характеристика состояния реки"."\n";
echo $HydroConv->condition_river_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
echo "\n"."Характеристика 2-ого состояния реки"."\n";
echo $HydroConv->condition_river_1_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
echo "\n"."Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %"."\n";
echo $HydroConv->cond_river_intensity_1_2;
echo "\n"."################## END Состояние реки №1-2 ###################"."\n";

//Temp` varchar(5) DEFAULT NULL COMMENT 'Толщина льда, см',
echo "\n"."Толщина льда, см"."\n";
echo $HydroConv->ice_thickness;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Высота снежного покрова на льду, см',
echo "\n"."Высота снежного покрова на льду, см"."\n";
echo $HydroConv->snow_depth_on_ice;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Ежедневный расход воды, м^3/с',
echo "\n"."Ежедневный расход воды, м^3/с"."\n";
echo $HydroConv->w_consumption;

//Precipitation amount
echo "\n"."Количество осадков, мм"."\n";
echo $HydroConv->precip;

//Precipitation duration
echo "\n"."Продолжительность выпадения осадков, ч"."\n";
echo $HydroConv->precip_duration;


/*################## BEGIN SECTION 2 ###################*/
echo "\n"."################## BEGIN SECTION 2_1 ###################"."\n";
echo "\n"."################## за ".$HydroConv->monthdayr__section_2_1." число этого месяца ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
echo "\n"."Уровень воды в срок наблюдений в текущие сутки, см"."\n";
echo $HydroConv->water_level__section_2_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
echo "\n"."Изменение уровня воды за 8-ой часовой срок наблюдения, см"."\n";
echo $HydroConv->water_level_diff__section_2_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды за 20-ой часовой срок наблюдений, см',
echo "\n"."Уровень воды за 20-ой часовой срок наблюдений, см"."\n";
echo $HydroConv->water_level_last_20h__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воды, °C',
echo "\n"."Температура воды, °C"."\n";
echo $HydroConv->water_temp__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воздуха, °C',
echo "\n"."Температура воздуха, °C"."\n";
echo $HydroConv->air_temp__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
echo "\n"."Характеристика ледовых явлений"."\n";
echo $HydroConv->ice_phenomena__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
echo "\n"."Характеристика 2-ого ледового явления"."\n";
echo $HydroConv->ice_phenomena_2__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
echo "\n"."Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %"."\n";
echo $HydroConv->ice_p_intensity__section_2_1;

echo "\n"."################## BEGIN Ледовые явления №2_1-2 ###################"."\n";
//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений №2_1-2',
echo "\n"."Характеристика ледовых явлений №2_1-2"."\n";
echo $HydroConv->ice_phenomena__section_2_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления №2_1-2',
echo "\n"."Характеристика 2-ого ледового явления №2_1-2"."\n";
echo $HydroConv->ice_phenomena_2__section_2_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема) №2_1-2, %',
echo "\n"."Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема) №2_1-2, %"."\n";
echo $HydroConv->ice_p_intensity__section_2_1_2;
echo "\n"."################## END Ледовые явления №2_1-2 ###################"."\n";

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
echo "\n"."Характеристика состояния реки"."\n";
echo $HydroConv->condition_river__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
echo "\n"."Характеристика 2-ого состояния реки"."\n";
echo $HydroConv->condition_river_2__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
echo "\n"."Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %"."\n";
echo $HydroConv->cond_river_intensity__section_2_1;

echo "\n"."################## BEGIN Состояние реки №2_1-2 ###################"."\n";
//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки №2_1-2',
echo "\n"."Характеристика состояния реки №2_1-2"."\n";
echo $HydroConv->condition_river__section_2_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки №2_1-2',
echo "\n"."Характеристика 2-ого состояния реки №2_1-2"."\n";
echo $HydroConv->condition_river_2__section_2_1_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема) №2_1-2, %',
echo "\n"."Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема) №2_1-2, %"."\n";
echo $HydroConv->cond_river_intensity__section_2_1_2;
echo "\n"."################## END Состояние реки №2_1-2 ###################"."\n";

//Temp` varchar(5) DEFAULT NULL COMMENT 'Толщина льда, см',
echo "\n"."Толщина льда, см"."\n";
echo $HydroConv->ice_thickness__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Высота снежного покрова на льду, см',
echo "\n"."Высота снежного покрова на льду, см"."\n";
echo $HydroConv->snow_depth_on_ice__section_2_1;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Ежедневный расход воды, м^3/с',
echo "\n"."Ежедневный расход воды, м^3/с"."\n";
echo $HydroConv->w_consumption__section_2_1;

//Precipitation amount
echo "\n"."Количество осадков, мм"."\n";
echo $HydroConv->precip__section_2_1;

//Precipitation duration
echo "\n"."Продолжительность выпадения осадков, ч"."\n";
echo $HydroConv->precip_duration__section_2_1;

echo "\n"."################## END SECTION 2_1 ###################"."\n";
/*################## END SECTION 2_1 ###################*/

/*################## BEGIN SECTION 2_2 ###################*/
echo "\n"."################## BEGIN SECTION 2_2 ###################"."\n";
echo "\n"."################## за ".$HydroConv->monthdayr__section_2_2." число этого месяца ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
echo "\n"."Уровень воды в срок наблюдений в текущие сутки, см"."\n";
echo $HydroConv->water_level__section_2_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
echo "\n"."Изменение уровня воды за 8-ой часовой срок наблюдения, см"."\n";
echo $HydroConv->water_level_diff__section_2_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды за 20-ой часовой срок наблюдений, см',
echo "\n"."Уровень воды за 20-ой часовой срок наблюдений, см"."\n";
echo $HydroConv->water_level_last_20h__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воды, °C',
echo "\n"."Температура воды, °C"."\n";
echo $HydroConv->water_temp__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Температура воздуха, °C',
echo "\n"."Температура воздуха, °C"."\n";
echo $HydroConv->air_temp__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
echo "\n"."Характеристика ледовых явлений"."\n";
echo $HydroConv->ice_phenomena__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
echo "\n"."Характеристика 2-ого ледового явления"."\n";
echo $HydroConv->ice_phenomena_2__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
echo "\n"."Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %"."\n";
echo $HydroConv->ice_p_intensity__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
echo "\n"."Характеристика состояния реки"."\n";
echo $HydroConv->condition_river__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
echo "\n"."Характеристика 2-ого состояния реки"."\n";
echo $HydroConv->condition_river_2__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
echo "\n"."Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %"."\n";
echo $HydroConv->cond_river_intensity__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Толщина льда, см',
echo "\n"."Толщина льда, см"."\n";
echo $HydroConv->ice_thickness__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Высота снежного покрова на льду, см',
echo "\n"."Высота снежного покрова на льду, см"."\n";
echo $HydroConv->snow_depth_on_ice__section_2_2;

//Temp` varchar(5) DEFAULT NULL COMMENT 'Ежедневный расход воды, м^3/с',
echo "\n"."Ежедневный расход воды, м^3/с"."\n";
echo $HydroConv->w_consumption__section_2_2;

//Precipitation amount
echo "\n"."Количество осадков, мм"."\n";
echo $HydroConv->precip__section_2_2;

//Precipitation duration
echo "\n"."Продолжительность выпадения осадков, ч"."\n";
echo $HydroConv->precip_duration__section_2_2;

echo "\n"."################## END SECTION 2_2 ###################"."\n";
/*################## END SECTION 2 ###################*/


/*################## BEGIN SECTION 3 ###################*/
echo "\n"."################## BEGIN SECTION 3_1 ###################"."\n";
echo "\n"."################## ".$HydroConv->period_avg_extreme_3_1." сведения о средних и эксремальных значениях ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний уровень воды за период, см',
echo "\n"."Средний уровень воды за период, см"."\n";
echo $HydroConv->water_level_avg__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высший уровень воды за период, см',
echo "\n"."Высший уровень воды за период, см"."\n";
echo $HydroConv->water_level_highest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Низший уровень воды за период, см',
echo "\n"."Низший уровень воды за период, см"."\n";
echo $HydroConv->water_level_lowest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний расход (приток) воды за период, м^3/с',
echo "\n"."Средний расход (приток) воды за период, м^3/с"."\n";
echo $HydroConv->w_consumption_avg__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Наибольший расход (приток) воды за период, м^3/с',
echo "\n"."Наибольший расход (приток) воды за период, м^3/с"."\n";
echo $HydroConv->w_consumption_highest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Наименьший расход (приток) воды за период, м^3/с',
echo "\n"."Наименьший расход (приток) воды за период, м^3/с"."\n";
echo $HydroConv->w_consumption_lowest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Дата (число месяца) прохождения наивысшего уровня (расхода) воды',
echo "\n"."Дата (число месяца) прохождения наивысшего уровня (расхода) воды"."\n";
echo $HydroConv->monthdayr_water_level_highest__section_3_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Час местного времени прохождения наивысшего уровня (расхода) воды',
echo "\n"."Час местного времени прохождения наивысшего уровня (расхода) воды"."\n";
echo $HydroConv->hourr_water_level_highest__section_3_1;

echo "\n"."################## END SECTION 3_1 ###################"."\n";

echo "\n"."################## BEGIN SECTION 3_2 ###################"."\n";
echo "\n"."################## ".$HydroConv->period_avg_extreme_3_2." сведения о средних и эксремальных значениях ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний уровень воды за период, см',
echo "\n"."Средний уровень воды за период, см"."\n";
echo $HydroConv->water_level_avg__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высший уровень воды за период, см',
echo "\n"."Высший уровень воды за период, см"."\n";
echo $HydroConv->water_level_highest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Низший уровень воды за период, см',
echo "\n"."Низший уровень воды за период, см"."\n";
echo $HydroConv->water_level_lowest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний расход (приток) воды за период, м^3/с',
echo "\n"."Средний расход (приток) воды за период, м^3/с"."\n";
echo $HydroConv->w_consumption_avg__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Наибольший расход (приток) воды за период, м^3/с',
echo "\n"."Наибольший расход (приток) воды за период, м^3/с"."\n";
echo $HydroConv->w_consumption_highest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Наименьший расход (приток) воды за период, м^3/с',
echo "\n"."Наименьший расход (приток) воды за период, м^3/с"."\n";
echo $HydroConv->w_consumption_lowest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Дата (число месяца) прохождения наивысшего уровня (расхода) воды',
echo "\n"."Дата (число месяца) прохождения наивысшего уровня (расхода) воды"."\n";
echo $HydroConv->monthdayr_water_level_highest__section_3_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Час местного времени прохождения наивысшего уровня (расхода) воды',
echo "\n"."Час местного времени прохождения наивысшего уровня (расхода) воды"."\n";
echo $HydroConv->hourr_water_level_highest__section_3_2;

echo "\n"."################## END SECTION 3_2 ###################"."\n";
/*################## END SECTION 3 ###################*/


/*################## BEGIN SECTION 4 ###################*/
echo "\n"."################## BEGIN SECTION 4_1 ###################"."\n";
echo "\n"."################## за ".$HydroConv->monthdayr__section_4_1." число этого месяца ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды верхнего бьефа гидроузла, см',
echo "\n"."Уровень воды верхнего бьефа гидроузла, см"."\n";
echo $HydroConv->water_level_upper_pool__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний (по площади) уровень водохранилища в срок наблюдений, см',
echo "\n"."Средний (по площади) уровень водохранилища в срок наблюдений, см"."\n";
echo $HydroConv->water_level_avg_current__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний (по площади) уровень водохранилища на конец предшествующих календарных суток, см',
echo "\n"."Средний (по площади) уровень водохранилища на конец предшествующих календарных суток, см"."\n";
echo $HydroConv->water_level_avg_post__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды нижнего бьефа в срок наблюдений, см',
echo "\n"."Уровень воды нижнего бьефа в срок наблюдений, см"."\n";
echo $HydroConv->water_level_lower_pool_current__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высший уровень воды нижнего бьефа за предшествующие сутки, см',
echo "\n"."Высший уровень воды нижнего бьефа за предшествующие сутки, см"."\n";
echo $HydroConv->water_level_lower_pool_hilevel_post__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Низший уровень воды нижнего бьефа за предшествующие сутки, см',
echo "\n"."Низший уровень воды нижнего бьефа за предшествующие сутки, см"."\n";
echo $HydroConv->water_level_lower_pool_lolevel_post__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Объем воды в водохранилище по среднему уровню в срок наблюдений, млн. м^3',
echo "\n"."Объем воды в водохранилище по среднему уровню в срок наблюдений, млн. м^3"."\n";
echo $HydroConv->w_volume_avg_current__section_4_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Объем воды в водохранилище по среднему уровню на конец предш. календарных суток, млн. м^3',
echo "\n"."Объем воды в водохранилище по среднему уровню на конец предш. календарных суток, млн. м^3"."\n";
echo $HydroConv->w_volume_avg_post__section_4_1;

echo "\n"."################## END SECTION 4_1 ###################"."\n";

echo "\n"."################## BEGIN SECTION 4_2 ###################"."\n";
echo "\n"."################## за ".$HydroConv->monthdayr__section_4_2." число этого месяца ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды верхнего бьефа гидроузла, см',
echo "\n"."Уровень воды верхнего бьефа гидроузла, см"."\n";
echo $HydroConv->water_level_upper_pool__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний (по площади) уровень водохранилища в срок наблюдений, см',
echo "\n"."Средний (по площади) уровень водохранилища в срок наблюдений, см"."\n";
echo $HydroConv->water_level_avg_current__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Средний (по площади) уровень водохранилища на конец предшествующих календарных суток, см',
echo "\n"."Средний (по площади) уровень водохранилища на конец предшествующих календарных суток, см"."\n";
echo $HydroConv->water_level_avg_post__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды нижнего бьефа в срок наблюдений, см',
echo "\n"."Уровень воды нижнего бьефа в срок наблюдений, см"."\n";
echo $HydroConv->water_level_lower_pool_current__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высший уровень воды нижнего бьефа за предшествующие сутки, см',
echo "\n"."Высший уровень воды нижнего бьефа за предшествующие сутки, см"."\n";
echo $HydroConv->water_level_lower_pool_hilevel_post__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Низший уровень воды нижнего бьефа за предшествующие сутки, см',
echo "\n"."Низший уровень воды нижнего бьефа за предшествующие сутки, см"."\n";
echo $HydroConv->water_level_lower_pool_lolevel_post__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Объем воды в водохранилище по среднему уровню в срок наблюдений, млн. м^3',
echo "\n"."Объем воды в водохранилище по среднему уровню в срок наблюдений, млн. м^3"."\n";
echo $HydroConv->w_volume_avg_current__section_4_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Объем воды в водохранилище по среднему уровню на конец предш. календарных суток, млн. м^3',
echo "\n"."Объем воды в водохранилище по среднему уровню на конец предш. календарных суток, млн. м^3"."\n";
echo $HydroConv->w_volume_avg_post__section_4_2;

echo "\n"."################## END SECTION 4_2 ###################"."\n";
/*################## END SECTION 4 ###################*/


/*################## BEGIN SECTION 5 ###################*/
echo "\n"."################## BEGIN SECTION 5_1 ###################"."\n";
echo "\n"."################## за ".$HydroConv->monthdayr__section_5_1." число этого месяца ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Общий приток воды в срок наблюдений, м^3/с',
echo "\n"."Общий приток воды в срок наблюдений, м^3/с"."\n";
echo $HydroConv->w_inflow_total_current__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Боковой приток воды в срок наблюдений, м^3/с',
echo "\n"."Боковой приток воды в срок наблюдений, м^3/с"."\n";
echo $HydroConv->w_inflow_lateral_current__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Приток воды к акватории водохронилища в срок наблюдений, м^3/с',
echo "\n"."Приток воды к акватории водохронилища в срок наблюдений, м^3/с"."\n";
echo $HydroConv->w_inflow_area_current__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Общий приток воды, средний за предшествующие сутки, м^3/с',
echo "\n"."Общий приток воды, средний за предшествующие сутки, м^3/с"."\n";
echo $HydroConv->w_inflow_total_post__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Боковой приток воды, средний за предшествующие сутки, м^3/с',
echo "\n"."Боковой приток воды, средний за предшествующие сутки, м^3/с"."\n";
echo $HydroConv->w_inflow_lateral_post__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Приток воды к акватории водохронилища, средний за предшествующие сутки, м^3/с',
echo "\n"."Приток воды к акватории водохронилища, средний за предшествующие сутки, м^3/с"."\n";
echo $HydroConv->w_inflow_area_post__section_5_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Сброс воды через гидроузел, средний за предшествующие сутки, м^3/с',
echo "\n"."Сброс воды через гидроузел, средний за предшествующие сутки, м^3/с"."\n";
echo $HydroConv->w_inflow_discharge_post__section_5_1;

echo "\n"."################## END SECTION 5_1 ###################"."\n";

echo "\n"."################## BEGIN SECTION 5_2 ###################"."\n";
echo "\n"."################## за ".$HydroConv->monthdayr__section_5_2." число этого месяца ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Общий приток воды в срок наблюдений, м^3/с',
echo "\n"."Общий приток воды в срок наблюдений, м^3/с"."\n";
echo $HydroConv->w_inflow_total_current__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Боковой приток воды в срок наблюдений, м^3/с',
echo "\n"."Боковой приток воды в срок наблюдений, м^3/с"."\n";
echo $HydroConv->w_inflow_lateral_current__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Приток воды к акватории водохронилища в срок наблюдений, м^3/с',
echo "\n"."Приток воды к акватории водохронилища в срок наблюдений, м^3/с"."\n";
echo $HydroConv->w_inflow_area_current__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Общий приток воды, средний за предшествующие сутки, м^3/с',
echo "\n"."Общий приток воды, средний за предшествующие сутки, м^3/с"."\n";
echo $HydroConv->w_inflow_total_post__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Боковой приток воды, средний за предшествующие сутки, м^3/с',
echo "\n"."Боковой приток воды, средний за предшествующие сутки, м^3/с"."\n";
echo $HydroConv->w_inflow_lateral_post__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Приток воды к акватории водохронилища, средний за предшествующие сутки, м^3/с',
echo "\n"."Приток воды к акватории водохронилища, средний за предшествующие сутки, м^3/с"."\n";
echo $HydroConv->w_inflow_area_post__section_5_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Сброс воды через гидроузел, средний за предшествующие сутки, м^3/с',
echo "\n"."Сброс воды через гидроузел, средний за предшествующие сутки, м^3/с"."\n";
echo $HydroConv->w_inflow_discharge_post__section_5_2;

echo "\n"."################## END SECTION 5_2 ###################"."\n";
/*################## END SECTION 5 ###################*/


/*################## BEGIN SECTION 6 ###################*/
echo "\n"."################## BEGIN SECTION 6_1 ###################"."\n";
echo "\n"."################## за ".$HydroConv->month_flow_6_1." месяц измерений расхода воды ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды для секции №6_1, см',
echo "\n"."Уровень воды для секции №6_1, см"."\n";
echo $HydroConv->water_level_avg__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Измеренный расход воды для секции №6_1, м^3/с',
echo "\n"."Измеренный расход воды для секции №6_1, м^3/с"."\n";
echo $HydroConv->w_consumption_avg__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Площадь живого сечения реки, м^2',
echo "\n"."Площадь живого сечения реки, м^2"."\n";
echo $HydroConv->river_area__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Максимальная глубина на гидрорастворе, см',
echo "\n"."Максимальная глубина на гидрорастворе, см"."\n";
echo $HydroConv->max_depth__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Дата измерения расхода воды',
echo "\n"."Число месяца измерения расхода воды"."\n";
echo $HydroConv->monthdayr_water_flow__section_6_1;
echo "\n"."Час по местному времени, к которому отнесено измерение расхода воды"."\n";
echo $HydroConv->hourr_water_flow__section_6_1;

echo "\n"."################## за ".$HydroConv->month_surface_6_1." месяц наблюдений за состоянием поверхности озера (водохранилища) ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Направление ветра на озере или водохранилище',
echo "\n"."Направление ветра на озере или водохранилище"."\n";
echo $HydroConv->wind_direction__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Скорость ветра на озере или водохранилище, м/с',
echo "\n"."Скорость ветра на озере или водохранилище, м/с"."\n";
echo $HydroConv->wind_speed__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Волненение на озере или водохранилище, откуда идет волна',
echo "\n"."Волненение на озере или водохранилище, откуда идет волна"."\n";
echo $HydroConv->wind_direction_waves__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высота ветровых волн, дециметры',
echo "\n"."Высота ветровых волн, дециметры"."\n";
echo $HydroConv->wave_height__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Характеристика состояния поверхности водоема',
echo "\n"."Характеристика состояния поверхности водоема"."\n";
echo $HydroConv->state_surface__section_6_1;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Время наблюдения за ветром и волнением воды',
echo "\n"."Число месяца наблюдения за ветром и волнением воды"."\n";
echo $HydroConv->monthdayr_water_waves__section_6_1;
echo "\n"."Час по местному времени, наблюдения за ветром и волнением воды"."\n";
echo $HydroConv->hourr_water_waves__section_6_1;

echo "\n"."################## END SECTION 6_1 ###################"."\n";

echo "\n"."################## BEGIN SECTION 6_2 ###################"."\n";
echo "\n"."################## за ".$HydroConv->month_flow_6_2." месяц измерений расхода воды ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды для секции №6_2, см',
echo "\n"."Уровень воды для секции №6_2, см"."\n";
echo $HydroConv->water_level_avg__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Измеренный расход воды для секции №6_2, м^3/с',
echo "\n"."Измеренный расход воды для секции №6_2, м^3/с"."\n";
echo $HydroConv->w_consumption_avg__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Площадь живого сечения реки, м^2',
echo "\n"."Площадь живого сечения реки, м^2"."\n";
echo $HydroConv->river_area__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Максимальная глубина на гидрорастворе, см',
echo "\n"."Максимальная глубина на гидрорастворе, см"."\n";
echo $HydroConv->max_depth__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Дата измерения расхода воды',
echo "\n"."Число месяца измерения расхода воды"."\n";
echo $HydroConv->monthdayr_water_flow__section_6_2;
echo "\n"."Час по местному времени, к которому отнесено измерение расхода воды"."\n";
echo $HydroConv->hourr_water_flow__section_6_2;

echo "\n"."################## за ".$HydroConv->month_surface_6_2." месяц наблюдений за состоянием поверхности озера (водохранилища) ###################"."\n";

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Направление ветра на озере или водохранилище',
echo "\n"."Направление ветра на озере или водохранилище"."\n";
echo $HydroConv->wind_direction__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Скорость ветра на озере или водохранилище, м/с',
echo "\n"."Скорость ветра на озере или водохранилище, м/с"."\n";
echo $HydroConv->wind_speed__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Волненение на озере или водохранилище, откуда идет волна',
echo "\n"."Волненение на озере или водохранилище, откуда идет волна"."\n";
echo $HydroConv->wind_direction_waves__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Высота ветровых волн, дециметры',
echo "\n"."Высота ветровых волн, дециметры"."\n";
echo $HydroConv->wave_height__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Характеристика состояния поверхности водоема',
echo "\n"."Характеристика состояния поверхности водоема"."\n";
echo $HydroConv->state_surface__section_6_2;

//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Время наблюдения за ветром и волнением воды',
echo "\n"."Число месяца наблюдения за ветром и волнением воды"."\n";
echo $HydroConv->monthdayr_water_waves__section_6_2;
echo "\n"."Час по местному времени, наблюдения за ветром и волнением воды"."\n";
echo $HydroConv->hourr_water_waves__section_6_2;

echo "\n"."################## END SECTION 6_2 ###################"."\n";
/*################## END SECTION 6 ###################*/


/*################## BEGIN SECTION 7 ###################*/
echo "\n"."################## BEGIN SECTION 7_1 ###################"."\n";
if ($HydroConv->about_dangerous__section_7_1 != NULL) {
	echo "\n"."################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_1." ###################"."\n";

	//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
	echo "\n"."Уровень воды в срок наблюдений в текущие сутки, см"."\n";
	echo $HydroConv->water_level__section_7_1;

	//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
	echo "\n"."Изменение уровня воды за 8-ой часовой срок наблюдения, см"."\n";
	echo $HydroConv->water_level_diff__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
	echo "\n"."Характеристика ледовых явлений"."\n";
	echo $HydroConv->ice_phenomena__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
	echo "\n"."Характеристика 2-ого ледового явления"."\n";
	echo $HydroConv->ice_phenomena_2__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
	echo "\n"."Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %"."\n";
	echo $HydroConv->ice_p_intensity__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
	echo "\n"."Характеристика состояния реки"."\n";
	echo $HydroConv->condition_river__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
	echo "\n"."Характеристика 2-ого состояния реки"."\n";
	echo $HydroConv->condition_river_2__section_7_1;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
	echo "\n"."Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %"."\n";
	echo $HydroConv->cond_river_intensity__section_7_1;
}
echo "\n"."################## END SECTION 7_1 ###################"."\n";

echo "\n"."################## BEGIN SECTION 7_2 ###################"."\n";
if ($HydroConv->about_dangerous__section_7_2 != NULL) {
	echo "\n"."################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_2." ###################"."\n";

	//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Уровень воды в срок наблюдений в текущие сутки, см',
	echo "\n"."Уровень воды в срок наблюдений в текущие сутки, см"."\n";
	echo $HydroConv->water_level__section_7_2;

	//Clouds_height` smallint(5) unsigned DEFAULT NULL COMMENT 'Изменение уровня воды за 8-ой часовой срок наблюдения, см',
	echo "\n"."Изменение уровня воды за 8-ой часовой срок наблюдения, см"."\n";
	echo $HydroConv->water_level_diff__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
	echo "\n"."Характеристика ледовых явлений"."\n";
	echo $HydroConv->ice_phenomena__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
	echo "\n"."Характеристика 2-ого ледового явления"."\n";
	echo $HydroConv->ice_phenomena_2__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
	echo "\n"."Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %"."\n";
	echo $HydroConv->ice_p_intensity__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика состояния реки',
	echo "\n"."Характеристика состояния реки"."\n";
	echo $HydroConv->condition_river__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого состояния реки',
	echo "\n"."Характеристика 2-ого состояния реки"."\n";
	echo $HydroConv->condition_river_2__section_7_2;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %',
	echo "\n"."Интенсивность состояния реки (степень покрытия реки или видимой аватории водоема), %"."\n";
	echo $HydroConv->cond_river_intensity__section_7_2;
}
echo "\n"."################## END SECTION 7_2 ###################"."\n";

echo "\n"."################## BEGIN SECTION 7_3 ###################"."\n";
if ($HydroConv->about_dangerous__section_7_3 != NULL) {
	echo "\n"."################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_3." ###################"."\n";

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика ледовых явлений',
	echo "\n"."Характеристика ледовых явлений"."\n";
	echo $HydroConv->ice_phenomena__section_7_3;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Характеристика 2-ого ледового явления',
	echo "\n"."Характеристика 2-ого ледового явления"."\n";
	echo $HydroConv->ice_phenomena_2__section_7_3;

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %',
	echo "\n"."Интенсивность ледового явления (степень покрытия реки или видимой аватории водоема), %"."\n";
	echo $HydroConv->ice_p_intensity__section_7_3;
}
echo "\n"."################## END SECTION 7_3 ###################"."\n";

echo "\n"."################## BEGIN SECTION 7_4 ###################"."\n";
if ($HydroConv->about_dangerous__section_7_4 != NULL) {
	echo "\n"."################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_4." ###################"."\n";

	//Temp` varchar(5) DEFAULT NULL COMMENT 'Ежедневный расход воды, м^3/с',
	echo "\n"."Ежедневный расход воды, м^3/с"."\n";
	echo $HydroConv->w_consumption__section_7_4;
}
echo "\n"."################## END SECTION 7_4 ###################"."\n";

echo "\n"."################## BEGIN SECTION 7_5 ###################"."\n";
if ($HydroConv->about_dangerous__section_7_5 != NULL) {
	echo "\n"."################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_5." ###################"."\n";

	//Precipitation amount
	echo "\n"."Количество осадков, мм"."\n";
	echo $HydroConv->precip__section_7_5;

	//Precipitation duration
	echo "\n"."Продолжительность выпадения осадков, ч"."\n";
	echo $HydroConv->precip_duration__section_7_5;
}
echo "\n"."################## END SECTION 7_5 ###################"."\n";

echo "\n"."################## BEGIN SECTION 7_6 ###################"."\n";
if ($HydroConv->about_dangerous__section_7_6 != NULL) {
	echo "\n"."################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_6." ###################"."\n";
}
echo "\n"."################## END SECTION 7_6 ###################"."\n";
		
echo "\n"."################## BEGIN SECTION 7_7 ###################"."\n";
if ($HydroConv->about_dangerous__section_7_7 != NULL) {
	echo "\n"."################## сведения о стихийном (особо опасном) ".$HydroConv->about_dangerous__section_7_7." ###################"."\n";
}
echo "\n"."################## END SECTION 7_7 ###################"."\n";

/*################## END SECTION 7 ###################*/
?>
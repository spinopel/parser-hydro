<?php
/*
	===========================
	HYDRO Parser Class
	===========================

	Version: 1.0
	
	This library is based on GetWx script by Mark Woodward.

	(c) 2024, Spin Opel (https://spinopel.top/)
	(c) 2013-2020, Information Networks, Ltd. (http://www.hsdn.org/)
	(c) 2001-2006, Mark Woodward (http://woody.cowpi.com/phpscripts/)

		This script is a PHP library which allows to parse the hydrological code
	in format KN-15, and convert it to an array of data parameters. KN-15 code
	parsed using the syntactic analysis and regular expressions. It solves
	the problem of parsing the data in the presence of any error in the code KN-15.
	In addition to the return parameters, the script also displays the interpreted
	(easy to understand) information of these parameters.
*/

/***********BEGIN HYDRO STRUCTURE ******************************************************/
	# section 0
	# MMMM BBiii YYGGi

	# section 1
	# 1HHHH 2HHHH 3HHHH 4ttTT (5EEii or 5EEEE) (6CCii or 6CCCC) 7DDDS 8kQQQ 0RRRd

	# section 2
	# 922YY
	# 1HHHH 2HHHH 3HHHH 4ttTT (5EEii or 5EEEE) (6CCii or 6CCCC) 7DDDS 8kQQQ 0RRRd
	
	# section 3
	# 933TT
	# 1HHHH 2HHHH 3HHHH 4kQQQ 5kQQQ 6kQQQ 7YYGG
	
	# section 4
	# 944YY
	# 1HHHH 2HHHH 3HHHH 4HHHH 5HHHH 6HHHH 7kVVV 8kVVV
	
	# section 5
	# 955YY
	# 1kQQQ 2kQQQ 3kQQQ 4kQQQ 5kQQQ 6kQQQ 7kQQQ
	
	# section 6
	# 966MM
	# 1HHHH 2kQQQ 3kFFF 4hhhh 5YYGG 6ddff 7dHHC 8YYGG
	
	# section 7
	# 97701
	# 1HHHH 2HHHK (5EEii or 5EEEE) (6CCii or 6CCCC)
	
	# 97702
	# 1HHHH 2HHHK (5EEii or 5EEEE) (6CCii or 6CCCC)
	
	# 97703
	# (5EEii or 5EEEE)
	
	# 97704
	# 8kQQQ	
	
	# 97705
	# 0RRRd
	
	# 97706
	# 
	
	# 97707
	# 

/***********END HYDRO STRUCTURE ******************************************************/

class Hydro
{
	/*
	 * Array of decoded result, by default all parameters is null.
	*/
	private $result = array
	(
		'raw'                      => NULL,
		'dayr'                     => NULL,
		'hour_obs'                 => NULL,
		'section_state'            => NULL,
		'station_id'               => NULL,
		'water_level'              => NULL,
		'water_level_diff'         => NULL,
		'water_level_last_20h'     => NULL,
		'water_temp'               => NULL,
		'air_temp'                 => NULL,
		'ice_phenomena'            => NULL,
		'ice_phenomena_2'          => NULL,
		'ice_p_intensity'          => NULL,
		'ice_phenomena_1_2'        => NULL,  // additional property for section 1
		'ice_phenomena_1_2_2'      => NULL,  // additional property for section 1
		'ice_p_intensity_1_2'      => NULL,  // additional property for section 1
		'condition_river'          => NULL,
		'condition_river_2'        => NULL,
		'cond_river_intensity'     => NULL,
		'condition_river_1_2'      => NULL,  // additional property for section 1
		'condition_river_1_2_2'    => NULL,  // additional property for section 1
		'cond_river_intensity_1_2' => NULL,  // additional property for section 1
		'ice_thickness'            => NULL,
		'snow_depth_on_ice'        => NULL,
		'w_consumption'            => NULL,
		'precip'                   => NULL,
		'precip_duration'          => NULL,
		
		### section 2_1 - variables ###
		'monthdayr__section_2_1'                => NULL,		
		'water_level__section_2_1'              => NULL,
		'water_level_diff__section_2_1'         => NULL,
		'water_level_last_20h__section_2_1'     => NULL,
		'water_temp__section_2_1'               => NULL,
		'air_temp__section_2_1'                 => NULL,
		'ice_phenomena__section_2_1'            => NULL,
		'ice_phenomena_2__section_2_1'          => NULL,
		'ice_p_intensity__section_2_1'          => NULL,
		'ice_phenomena__section_2_1_2'          => NULL,  // additional property for section 2
		'ice_phenomena_2__section_2_1_2'        => NULL,  // additional property for section 2
		'ice_p_intensity__section_2_1_2'        => NULL,  // additional property for section 2
		'condition_river__section_2_1'          => NULL,
		'condition_river_2__section_2_1'        => NULL,
		'cond_river_intensity__section_2_1'     => NULL,
		'condition_river__section_2_1_2'        => NULL,  // additional property for section 2
		'condition_river_2__section_2_1_2'      => NULL,  // additional property for section 2
		'cond_river_intensity__section_2_1_2'   => NULL,  // additional property for section 2
		'ice_thickness__section_2_1'            => NULL,
		'snow_depth_on_ice__section_2_1'        => NULL,
		'w_consumption__section_2_1'            => NULL,
		'precip__section_2_1'                   => NULL,
		'precip_duration__section_2_1'          => NULL,
		### section 2_2 - variables ###
		'monthdayr__section_2_2'                 => NULL,		
		'water_level__section_2_2'              => NULL,
		'water_level_diff__section_2_2'         => NULL,
		'water_level_last_20h__section_2_2'     => NULL,
		'water_temp__section_2_2'               => NULL,
		'air_temp__section_2_2'                 => NULL,
		'ice_phenomena__section_2_2'            => NULL,
		'ice_phenomena_2__section_2_2'          => NULL,
		'ice_p_intensity__section_2_2'          => NULL,
		'ice_phenomena__section_2_2_2'          => NULL,  // additional property for section 2
		'ice_phenomena_2__section_2_2_2'        => NULL,  // additional property for section 2
		'ice_p_intensity__section_2_2_2'        => NULL,  // additional property for section 2
		'condition_river__section_2_2'          => NULL,
		'condition_river_2__section_2_2'        => NULL,
		'cond_river_intensity__section_2_2'     => NULL,
		'condition_river__section_2_2_2'        => NULL,  // additional property for section 2
		'condition_river_2__section_2_2_2'      => NULL,  // additional property for section 2
		'cond_river_intensity__section_2_2_2'   => NULL,  // additional property for section 2
		'ice_thickness__section_2_2'            => NULL,
		'snow_depth_on_ice__section_2_2'        => NULL,
		'w_consumption__section_2_2'            => NULL,
		'precip__section_2_2'                   => NULL,
		'precip_duration__section_2_2'          => NULL,
		
		### section 3_1 - variables ###
		'period_avg_extreme_3_1'                => NULL,
		'water_level_avg__section_3_1'          => NULL,
		'water_level_highest__section_3_1'      => NULL,
		'water_level_lowest__section_3_1'       => NULL,
		'w_consumption_avg__section_3_1'        => NULL,
		'w_consumption_highest__section_3_1'    => NULL,
		'w_consumption_lowest__section_3_1'     => NULL,
		'monthdayr_water_level_highest__section_3_1'         => NULL,
		'hourr_water_level_highest__section_3_1'             => NULL,
		### section 3_2 - variables ###
		'period_avg_extreme_3_2'                => NULL,
		'water_level_avg__section_3_2'          => NULL,
		'water_level_highest__section_3_2'      => NULL,
		'water_level_lowest__section_3_2'       => NULL,
		'w_consumption_avg__section_3_2'        => NULL,
		'w_consumption_highest__section_3_2'    => NULL,
		'w_consumption_lowest__section_3_2'     => NULL,
		'monthdayr_water_level_highest__section_3_2'         => NULL,
		'hourr_water_level_highest__section_3_2'             => NULL,
		
		### section 4_1 - variables ###
		'monthdayr__section_4_1'                             => NULL,
		'water_level_upper_pool__section_4_1'                => NULL,
		'water_level_avg_current__section_4_1'               => NULL,
		'water_level_avg_post__section_4_1'                  => NULL,
		'water_level_lower_pool_current__section_4_1'        => NULL,
		'water_level_lower_pool_hilevel_post__section_4_1'   => NULL,
		'water_level_lower_pool_lolevel_post__section_4_1'   => NULL,
		'w_volume_avg_current__section_4_1'                  => NULL,
		'w_volume_avg_post__section_4_1'                     => NULL,
		### section 4_2 - variables ###
		'monthdayr__section_4_2'                             => NULL,
		'water_level_upper_pool__section_4_2'                => NULL,
		'water_level_avg_current__section_4_2'               => NULL,
		'water_level_avg_post__section_4_2'                  => NULL,
		'water_level_lower_pool_current__section_4_2'        => NULL,
		'water_level_lower_pool_hilevel_post__section_4_2'   => NULL,
		'water_level_lower_pool_lolevel_post__section_4_2'   => NULL,
		'w_volume_avg_current__section_4_2'                  => NULL,
		'w_volume_avg_post__section_4_2'                     => NULL,
		
		### section 5_1 - variables ###
		'monthdayr__section_5_1'                             => NULL,
		'w_inflow_total_current__section_5_1'                => NULL,
		'w_inflow_lateral_current__section_5_1'              => NULL,
		'w_inflow_area_current__section_5_1'                 => NULL,
		'w_inflow_total_post__section_5_1'                   => NULL,
		'w_inflow_lateral_post__section_5_1'                 => NULL,
		'w_inflow_area_post__section_5_1'                    => NULL,
		'w_inflow_discharge_post__section_5_1'               => NULL,
		### section 5_2 - variables ###
		'monthdayr__section_5_2'                             => NULL,
		'w_inflow_total_current__section_5_2'                => NULL,
		'w_inflow_lateral_current__section_5_2'              => NULL,
		'w_inflow_area_current__section_5_2'                 => NULL,
		'w_inflow_total_post__section_5_2'                   => NULL,
		'w_inflow_lateral_post__section_5_2'                 => NULL,
		'w_inflow_area_post__section_5_2'                    => NULL,
		'w_inflow_discharge_post__section_5_2'               => NULL,
		
		### section 6_1 - variables ###
		'month_flow_6_1'                        => NULL,
		'water_level_avg__section_6_1'          => NULL,
		'w_consumption_avg__section_6_1'        => NULL,
		'river_area__section_6_1'               => NULL,
		'max_depth__section_6_1'                => NULL,
		'monthdayr_water_flow__section_6_1'     => NULL,
		'hourr_water_flow__section_6_1'         => NULL,
		'month_surface_6_1'                     => NULL,
		'wind_direction__section_6_1'           => NULL,
		'wind_speed__section_6_1'               => NULL,
		'wind_direction_waves__section_6_1'     => NULL,
		'wave_height__section_6_1'              => NULL,
		'state_surface__section_6_1'            => NULL,
		'monthdayr_water_waves__section_6_1'    => NULL,
		'hourr_water_waves__section_6_1'        => NULL,
		### section 6_2 - variables ###
		'month_flow_6_2'                        => NULL,
		'water_level_avg__section_6_2'          => NULL,
		'w_consumption_avg__section_6_2'        => NULL,
		'river_area__section_6_2'               => NULL,
		'max_depth__section_6_2'                => NULL,
		'monthdayr_water_flow__section_6_2'     => NULL,
		'hourr_water_flow__section_6_2'         => NULL,
		'month_surface_6_2'                     => NULL,
		'wind_direction__section_6_2'           => NULL,
		'wind_speed__section_6_2'               => NULL,
		'wind_direction_waves__section_6_2'     => NULL,
		'wave_height__section_6_2'              => NULL,
		'state_surface__section_6_2'            => NULL,
		'monthdayr_water_waves__section_6_2'    => NULL,
		'hourr_water_waves__section_6_2'        => NULL,

		### section 7_1 - variables ###
		'about_dangerous__section_7_1'          => NULL,
		'water_level__section_7_1'              => NULL,
		'water_level_diff__section_7_1'         => NULL,
		'ice_phenomena__section_7_1'            => NULL,
		'ice_phenomena_2__section_7_1'          => NULL,
		'ice_p_intensity__section_7_1'          => NULL,
		'condition_river__section_7_1'          => NULL,
		'condition_river_2__section_7_1'        => NULL,
		'cond_river_intensity__section_7_1'     => NULL,
		### section 7_2 - variables ###
		'about_dangerous__section_7_2'          => NULL,
		'water_level__section_7_2'              => NULL,
		'water_level_diff__section_7_2'         => NULL,
		'ice_phenomena__section_7_2'            => NULL,
		'ice_phenomena_2__section_7_2'          => NULL,
		'ice_p_intensity__section_7_2'          => NULL,
		'condition_river__section_7_2'          => NULL,
		'condition_river_2__section_7_2'        => NULL,
		'cond_river_intensity__section_7_2'     => NULL,
		### section 7_3 - variables ###
		'about_dangerous__section_7_3'          => NULL,
		'ice_phenomena__section_7_3'            => NULL,
		'ice_phenomena_2__section_7_3'          => NULL,
		'ice_p_intensity__section_7_3'          => NULL,
		### section 7_4 - variables ###
		'about_dangerous__section_7_4'          => NULL,
		'w_consumption__section_7_4'            => NULL,
		### section 7_5 - variables ###
		'about_dangerous__section_7_5'          => NULL,
		'precip__section_7_5'                   => NULL,
		'precip_duration__section_7_5'          => NULL,
		### section 7_6 - variables ###
		'about_dangerous__section_7_6'          => NULL,
		### section 7_7 - variables ###
		'about_dangerous__section_7_7'          => NULL
	);

	/*
	 * Methods used for parsing in the order of data
	*/
	private $method_names = array
	(
		'station_id',
		'time',
		'water_level',
		'water_level_diff',
		'water_level_last_20h',
		'temperature',
		'ice_phenomena',
		'ice_phenomena_1_2',				// additional group for section 1
		'condition_river',
		'condition_river_1_2',				// additional group for section 1
		'ice_thickness',
		'w_consumption',
		'precipitation',
		
		### section 2_1 - methods ###
		'section_2_1',
		'water_level__section_2_1',
		'water_level_diff__section_2_1',
		'water_level_last_20h__section_2_1',
		'temperature__section_2_1',
		'ice_phenomena__section_2_1',
		'ice_phenomena__section_2_1_2',		// additional group for section 2
		'condition_river__section_2_1',
		'condition_river__section_2_1_2',	// additional group for section 2
		'ice_thickness__section_2_1',
		'w_consumption__section_2_1',
		'precipitation__section_2_1',
		### section 2_2 - methods ###
		'section_2_2',
		'water_level__section_2_2',
		'water_level_diff__section_2_2',
		'water_level_last_20h__section_2_2',
		'temperature__section_2_2',
		'ice_phenomena__section_2_2',
		'ice_phenomena__section_2_2_2',		// additional group for section 2
		'condition_river__section_2_2',
		'condition_river__section_2_2_2',	// additional group for section 2
		'ice_thickness__section_2_2',
		'w_consumption__section_2_2',
		'precipitation__section_2_2',
		
		### section 3_1 - methods ###
		'section_3_1',
		'water_level_avg__section_3_1',
		'water_level_highest__section_3_1',
		'water_level_lowest__section_3_1',
		'w_consumption_avg__section_3_1',  
		'w_consumption_highest__section_3_1',  
		'w_consumption_lowest__section_3_1',
		'time_water_level_highest__section_3_1',
		### section 3_2 - methods ###
		'section_3_2',
		'water_level_avg__section_3_2',
		'water_level_highest__section_3_2',
		'water_level_lowest__section_3_2',
		'w_consumption_avg__section_3_2',  
		'w_consumption_highest__section_3_2',  
		'w_consumption_lowest__section_3_2',
		'time_water_level_highest__section_3_2',
		
		### section 4_1 - methods ###
		'section_4_1',
		'water_level_upper_pool__section_4_1',
		'water_level_avg_current__section_4_1',
		'water_level_avg_post__section_4_1',
		'water_level_lower_pool_current__section_4_1',  
		'water_level_lower_pool_hilevel_post__section_4_1',  
		'water_level_lower_pool_lolevel_post__section_4_1',
		'w_volume_avg_current__section_4_1',
		'w_volume_avg_post__section_4_1',
		### section 4_2 - methods ###
		'section_4_2',
		'water_level_upper_pool__section_4_2',
		'water_level_avg_current__section_4_2',
		'water_level_avg_post__section_4_2',
		'water_level_lower_pool_current__section_4_2',  
		'water_level_lower_pool_hilevel_post__section_4_2',  
		'water_level_lower_pool_lolevel_post__section_4_2',
		'w_volume_avg_current__section_4_2',
		'w_volume_avg_post__section_4_2',
		
		### section 5_1 - methods ###
		'section_5_1',
		'w_inflow_total_current__section_5_1',
		'w_inflow_lateral_current__section_5_1',
		'w_inflow_area_current__section_5_1',
		'w_inflow_total_post__section_5_1',  
		'w_inflow_lateral_post__section_5_1',  
		'w_inflow_area_post__section_5_1',
		'w_inflow_discharge_post__section_5_1',
		### section 5_2 - methods ###
		'section_5_2',
		'w_inflow_total_current__section_5_2',
		'w_inflow_lateral_current__section_5_2',
		'w_inflow_area_current__section_5_2',
		'w_inflow_total_post__section_5_2',  
		'w_inflow_lateral_post__section_5_2',  
		'w_inflow_area_post__section_5_2',
		'w_inflow_discharge_post__section_5_2',
		
		### section 6_1 - methods ###
		'section_flow_6_1',
		'water_level_avg__section_6_1',
		'w_consumption_avg__section_6_1',
		'river_area__section_6_1',
		'max_depth__section_6_1',  
		'date_water_flow__section_6_1',  
		'section_surface_6_1',  
		'wind__section_6_1',
		'wind_waves_surface__section_6_1',
		'date_water_waves__section_6_1',
		### section 6_2 - methods ###
		'section_flow_6_2',
		'water_level_avg__section_6_2',
		'w_consumption_avg__section_6_2',
		'river_area__section_6_2',
		'max_depth__section_6_2',  
		'date_water_flow__section_6_2',  
		'section_surface_6_2',  
		'wind__section_6_2',
		'wind_waves_surface__section_6_2',
		'date_water_waves__section_6_2',
		
		### section 7_1 - methods ###
		'section_7_1',
		'water_level__section_7_1',
		'water_level_diff__section_7_1',
		'ice_phenomena__section_7_1',
		'condition_river__section_7_1',
		### section 7_2 - methods ###
		'section_7_2',
		'water_level__section_7_2',
		'water_level_diff__section_7_2',
		'ice_phenomena__section_7_2',
		'condition_river__section_7_2',
		### section 7_3 - methods ###
		'section_7_3',
		'ice_phenomena__section_7_3',
		### section 7_4 - methods ###
		'section_7_4',
		'w_consumption__section_7_4',
		### section 7_5 - methods ###
		'section_7_5',
		'precipitation__section_7_5',
		### section 7_6 - methods ###
		'section_7_6',
		### section 7_7 - methods ###
		'section_7_7'
	);

	private $WIND_UNIT_CODE = array
	(
		"0" => "meters per second estimate",
		"1" => "meters per second measured",
		"3" => "knots estimate",
		"4" => "knots measured"
	);

/***********BEGIN HYDRO OPTIONS ******************************************************/
	#section 1 or 2 or 7 - 5EEii or 5EEEE group
	private $ICE_PHENOMENA_CODE = array
	(
		'00' => null,													//
		'11' => 'cало',													// fat
		'12' => 'cнежура',                                              // snowshoe
		'13' => 'забереги',                                             // take away
		'14' => 'припай шириной более 100 м (для озер водохранилищ)',   // fast ice more than 100 m wide (for lakes and reservoirs)
		'15' => 'забереги нависшие',                                    // take away the overhanging
		'16' => 'ледоход',                                              // ice drift
		'17' => 'ледоход',                                              // ice drift
		'18' => 'ледоход поверх лед. покрова',                          // ice drift over the ice. cover
		'19' => 'шугоход',                                              // drywall
		'20' => 'внутриводный лед',                                     // intrawater ice
		'21' => 'пятры',                                                // five
		'22' => 'осевший лед',                                          // settled ice
		'23' => 'навалы льда на берегах',                               // piles of ice on the banks
		'24' => 'ледяная перемычка',                                    // ice bridge
		'25' => 'ледяная перемычка в/п',                                // ice cofferdam above the hydropost
		'26' => 'ледяная перемычка н/п',                                // ice cofferdam below the hydropost
		'30' => 'затор в/п',                                            // congestion above the hydropost
		'31' => 'затор н/п',                                            // congestion below the hydropost
		'32' => 'затор льда искуственно разрушается',                   // ice jam is artificially destroyed
		'34' => 'зажор в/п',                                            // jam above the hydropost
		'35' => 'зажор н/п',                                            // jam below the hydropost
		'36' => 'зажор льда искуственно разрушается',                   // ice jam is artificially destroyed
		'37' => 'вода на льду',                                         // water on ice
		'38' => 'вода течет поверх льда',                               // water flows over ice
		'39' => 'закраины',                                             // flanges
		'40' => 'лед потемнел',                                         // ice darkened
		'41' => 'снежница',                                             // puddle
		'42' => 'лед подняло',                                          // ice lifted
		'43' => 'подвижка льда',                                        // ice movement
		'44' => 'разводья',                                             // divorce
		'45' => 'лед тает на месте',                                    // ice melts in place
		'46' => 'забереги остаточные',                                  // take away the residual
		'47' => 'наслуд',                                               // forgiveness
		'48' => 'битый лед',                                            // broken ice
		'49' => 'блинчатый лед',                                        // pancake ice
		'50' => 'ледяные поля (для озер водохранилищ устьев рек)',      // ice fields (for lakes and reservoirs of river mouths)
		'51' => 'ледяная каша',                                         // ice porridge
		'52' => 'стамуха (для озер водохранилищ устьев рек)',           // stamukha (for lakes and reservoirs of river mouths)
		'53' => 'лед относит от берега',                                // ice carries away from the shore
		'54' => 'лед прижимает к берегу',                               // the ice is pressing to the shore
		'63' => 'ледостав неполный',                                    // incomplete freeze-up
		'64' => 'ледостав с полыньями',                                 // freeze-up with openings
		'65' => 'ледостав',                                             // freeze-up
		'66' => 'ледостав с торосами',                                  // freeze-up with hummocks
		'67' => 'ледяной покров с грядами торосов',                     // ice cover with ridges of hummocks
		'68' => 'шуговая дорожка',                                      // sludge track
		'69' => 'подо льдом шуга',                                      // under the ice sludge
		'70' => 'трещины в ледяном покрове',                            // cracks in the ice sheet
		'71' => 'неледь',                                               // not ice
		'72' => 'лед нависший',                                         // overhanging ice
		'73' => 'лед ярусный',                                          // longline ice
		'74' => 'лед на дне',                                           // ice at the bottom
		'75' => 'река промерзла',                                       // the river is frozen
		'76' => 'лед искусственно разрушен',                            // the ice is artificially destroyed
		'77' => 'наледная вода'                                         // ice water
	);

	#section 1 or 2 or 7 - 6CCii or 6CCCC group
	private $CONDITION_RIVER_CODE = array
	(
		'00' => 'чисто',															// purely
		'11' => 'лесосплав',                                                        // timber rafting
		'14' => 'залом леса в/п',                                                   // forest hall above the hydropost
		'15' => 'залом леса н/п',                                                   // forest hall below the hydropost
		'22' => 'растительность у берега',                                          // coastal vegetation
		'23' => 'растительность по всему сечению потока',                           // vegetation across the entire stream
		'24' => 'растительность по сечению потока пятнами',                         // spotted vegetation along the stream
		'25' => 'растительность стелится по дну',                                   // vegetation spreads along the bottom
		'26' => 'растительность у гидроствора выкошена',                            // the vegetation at the hydropower is mown
		'27' => 'растительность легла на дно',                                      // vegetation fell to the bottom
		'28' => 'растительность занесена илом',                                     // vegetation covered with silt
		'29' => 'растительность погибла',                                           // vegetation died
		'35' => 'обвал берега',                                                     // landfall
		'36' => 'обвал берега в/п',                                                 // collapse of the coast above the hydropost
		'37' => 'обвал берега н/п',                                                 // collapse of the coast below the hydropost
		'38' => 'дноуглубительные работы в русле',                                  // in-bed dredging
		'39' => 'намывные работы в русле',                                          // in-bed reclamation
		'40' => 'проведена расчистка русла',                                        // the channel was cleared
		'41' => 'русло реки сужено на гидростворе',                                 // the river bed is narrowed at the hydroelectric station
		'42' => 'образовалась коса',                                                // a scythe formed
		'43' => 'коса',                                                             // scythe
		'44' => 'образовался осередок',                                             // a mid-point was formed
		'45' => 'осередок',                                                         // midsection
		'46' => 'образовался остров',                                               // an island was formed
		'47' => 'остров',                                                           // Island
		'48' => 'смещение русла в плане',                                           // channel displacement in plan
		'52' => 'снежный завал',                                                    // snow block
		'53' => 'снежный завал в/п',                                                // snow block above the hydropost
		'54' => 'снежный завал н/п',                                                // snow block below the hydropost
		'55' => 'прорыв снежного завала',                                           // snow block breakthrough
		'56' => 'прохождение селя',                                                 // mudflow passage
		'57' => 'течение реки изменилось',                                          // the course of the river has changed
		'58' => 'сгон воды',                                                        // drainage of water
		'59' => 'нагон воды',                                                       // water surge
		'60' => 'река пересохла',                                                   // the river is dry
		'61' => 'волнение слабое',                                                  // weak excitement
		'62' => 'волнение умеренное',                                               // moderate excitement
		'63' => 'волнение сильное',                                                 // strong excitement
		'64' => 'стоячая вода',                                                     // Still water
		'65' => 'стоячая вода подо льдом',                                          // standing water under the ice
		'66' => 'прекратилась лодочная переправа',                                  // the boat crossing stopped
		'67' => 'прекратилось пешее сообщение по льду',                             // pedestrian traffic on ice stopped
		'68' => 'началось пешее сообщение по льду',                                 // pedestrian traffic on ice began
		'69' => 'началось движение транспорта по льду',                             // traffic began on the ice
		'70' => 'прекратилось движение транспорта по льду',                         // traffic on the ice has stopped
		'71' => 'началась лодочная переправа',                                      // boat crossing began
		'72' => 'подпор (от засорения русла мостовых переправ ледообразования)',    // backwater (from clogging of the channel of bridge crossings of ice formation)
		'73' => 'начало навигации',                                                 // start navigation
		'74' => 'конец навигации',                                                  // end of navigation
		'77' => 'забор воды в/п',                                                   // water intake above the hydropost
		'78' => 'забор воды н/п',                                                   // water intake below the hydropost
		'79' => 'забор воды прекратился в/п',                                       // water intake stopped above the hydropost
		'80' => 'забор воды прекратился н/п',                                       // water intake has stopped below the hydropost
		'81' => 'сброс воды в/п',                                                   // discharge of water above the hydropost
		'82' => 'сброс воды н/п',                                                   // discharge of water below the hydropost
		'83' => 'сброс воды прекратился в/п',                                       // water discharge stopped above the hydropost
		'84' => 'сброс воды прекратился н/п',                                       // water discharge stopped below the hydropost
		'85' => 'плотина в/п',                                                      // dam above the hydropost
		'86' => 'плотина н/п',                                                      // dam below the hydropost
		'87' => 'разрушена платина в/п',                                            // platinum destroyed above the hydropost
		'88' => 'разрушена платина н/п',                                            // destroyed platinum below the hydropost
		'89' => 'подпор от засорения русла',                                        // backwater from blockage of the channel
		'90' => 'подпор от мостовых переправ',                                      // bridge support
		'91' => 'попуски воды'                                                      // water releases
	);
	
	#section 1 or 2 - 7DDDS group
	private $SNOW_DEPTH_ICE_CODE = array
	(
		"0" => "0",		//0
		"1" => "<5",	//<5
		"2" => "10",	//5-10
		"3" => "15",	//11-15
		"4" => "20",	//16-20
		"5" => "25",	//21-25
		"6" => "35",	//26-35
		"7" => "50",	//36-50
		"8" => "70",	//51-70
		"9" => ">70"	//>70
	);
	
	#section 1 or 2 or 7 - 0RRRd group
	private $PRECIP_DURATION_CODE = array
	(
		"0" => "<1",	//<1
		"1" => "3",		//1-3
		"2" => "6",		//3-6
		"3" => "12",	//6-12
		"4" => ">12"	//>12
	);
	
	#section 3 - 933TT group
	private $PERIOD_AVG_EXTREME_VALUES = array
	(
		"01" => "over the past day",										//за прошедшие сутки
		"04" => "for rain flood",											//за дождевой паводок
		"05" => "for the flood",											//за половодье		
		"11" => "for the first decade",										//за первую декаду
		"20" => "for 20 days, from 1 to 20",								//за 20 дней, с 1 по 20 число
		"22" => "for the second decade",									//за вторую декаду
		"25" => "for 25 days, from 1st to 25th day",						//за 25 дней, с 1 по 25 число
		"30" => "per month, regardless of the length of the month in days",	//за месяц, независимо от продолжительности месяца в днях
		"33" => "over the third decade"										//за третью декаду
	);
	
	#section 6 - 6ddff group
	private $WIND_DIR_COMPASS = array
	(
		"00" => 'calm wind',
		"01" => 'NE',
		"02" => 'E',
		"03" => 'SE',
		"04" => 'S',
		"05" => 'SW',
		"06" => 'W',
		"07" => 'NW',
		"08" => 'N',
		"09" => 'VRB',  //wind_direction_varies
		"//" => NULL	
	);
	
	#section 6 - 7dHHC group
	private $WIND_DIR_COMPASS_WAVES = array
	(
		"0" => 'calm wind',
		"1" => 'NE',
		"2" => 'E',
		"3" => 'SE',
		"4" => 'S',
		"5" => 'SW',
		"6" => 'W',
		"7" => 'NW',
		"8" => 'N',
		"9" => 'VRB',  //wind_direction_varies
		"//" => NULL	
	);
	
	#section 6 - 7dHHC group
	private $POINTS_STATE_SURFACE = array
	(
		"0" => 'smooth surface',												//зеркально-гладкая поверхность
		"1" => 'ripples',														//рябь, появляются небольшие гребни волн
		"2" => 'small wave crests',												//небольшие гребни волн начинают опрокидываться, но пена не белая, а стекловидная
		"3" => 'small waves',													//хорошо заметные небольшие волны, гребни некоторых из них опрокидываются, образуя местами белую клубящуюся пену - "барашки"
		"4" => 'well-defined waves',											//волны принимают хорошо выраженную форму, повсюду образуются "барашки"
		"5" => 'high ridges',													//появляются гребни большой высоты, их пенящиеся вершины занимают большие площади, ветер начинает срывать пену с гребней волн
		"6" => 'the surface of the water begins to foam',						//гребни очерчивают длинные волны ветровых волн; пена, срываемая с гребней ветром, начинает вытягиваться полосами по склонам волн
		"7" => 'the surface of the water is covered with long stripes of foam',	//длинные полосы пены, срываемые ветром, покрывают склоны волн, а местами, сливаясь, достигают их подошв
		"8" => 'the surface of the water is covered with merging foam stripes',	//пена широкими, плотными, сливающимися полосами покрывает склоны волн, отчего вся поверхность становится белой; только местами, видны свободные от пены участки
		"9" => 'the water surface is covered with a dense layer of foam'		//поверхность воды покрыта плотным слоем пены, воздух наполнен водяной пылью и брызгами, видимость значительно уменьшена
	);
	
	#section 7 - 977nn group
	private $HYDRO_DANGEROUS_CODE = array
	(
		"01" => 'high water level',
		"02" => 'low water level',
		"03" => 'early freeze-up and ice formation',
		"04" => 'very high or low water consumption',
		"05" => 'heavy rain',
		"06" => 'dirty water flow',
		"07" => 'avalanche'
	);
	
/***********END HYDRO OPTIONS ******************************************************/

	/*
	 * Debug and parse errors information.
	*/
	private $errors = NULL;
	private $debug  = NULL;
	private $debug_enabled;

	/*
	 * Other variables.
	*/
	private $raw;
	private $raw_parts = array();
	private $method    = 0;
	private $part      = 0;

	/**
	 * This method provides HYDRO information, you want to parse.
	 *
	 * Examples of raw HYDRO for test:
	 * 79121 09081 10244 20021 30243 458// 60000=
	 * 73115 09081 10542 20122 30547 424// 53703 62201 70454 83926 00512 93305 20560 70708=
	 * 79093 09081 15154 20142 30158 60000 90043 00043=
	*/
	public function __construct($raw, $debug = FALSE)
	{
		$this->debug_enabled = $debug;

		if (empty($raw))
		{
			throw new Exception('The HYDRO information is not presented.');
		}

		$raw_lines = explode("\n", $raw, 2);

		if (isset($raw_lines[1]))
		{
			$raw = trim($raw_lines[1]);
		}
		else
		{
			$raw = trim($raw_lines[0]);
		}

		$this->raw = rtrim(trim(preg_replace('/[\s\t]+/s', ' ', $raw)), '=');

		$this->set_debug('Infromation presented as HYDRO.');

		$this->set_result_value('raw', $this->raw);
	}

	/**
	 * Gets the value from result array as class property.
	*/
	public function __get($parameter)
	{
		if (isset($this->result[$parameter]))
		{
			return $this->result[$parameter];
		}

		return NULL;
	}

	/**
	 * Parses the HYDRO information and returns result array.
	*/
	public function parse()
	{
		$this->raw_parts = explode(' ', $this->raw);

		$current_method = 0;

		// See parts
		while ($this->part < sizeof($this->raw_parts))
		{
			$this->method = $current_method;

			// See methods
			while ($this->method < sizeof($this->method_names))
			{
				$method = 'get_'.$this->method_names[$this->method];
				$token  = $this->raw_parts[$this->part];

				if ($this->$method($token) === TRUE)
				{
					$this->set_debug('Token "'.$token.'" is parsed by method: '.$method.', '.
						($this->method - $current_method).' previous methods skipped.');

					$current_method = $this->method;

					$this->method++;

					break;
				}

				$this->method++;
			}

			if ($current_method != $this->method - 1)
			{
				$this->set_error('Unknown token: '.$this->raw_parts[$this->part]);
				$this->set_debug('Token "'.$this->raw_parts[$this->part].'" is NOT PARSED, '.
						($this->method - $current_method).' methods attempted.');
			}

			$this->part++;
		}

		return $this->result;
	}

	/**
	 * Returns array with debug information.
	*/
	public function debug()
	{
		return $this->debug;
	}

	/**
	 * Returns array with parse errors.
	*/
	public function errors()
	{
		return $this->errors;
	}

	/**
	 * This method formats observation date and time in the local time zone of server, 
	 * the current local time on server, and time difference since observation. $time_utc is a
	 * UNIX timestamp for Universal Coordinated Time (Greenwich Mean Time or Zulu Time).
	*/
	private function set_observed_date($time_utc)
	{
		$local = $time_utc + date('Z');
		$now   = time();

		$this->set_result_value('observed_date', date('r', $local)); // or "D M j, H:i T"

		$time_diff = floor(($now - $local) / 60);

		if ($time_diff < 91)
		{
			$this->set_result_value('observed_age', $time_diff.' min. ago');
		}
		else
		{
			$this->set_result_value('observed_age', floor($time_diff / 60).':'.sprintf("%02d", $time_diff % 60).' hr. ago');
		}
	}

	/**
	 * Sets the new value to parameter in result array.
	*/
	private function set_result_value($parameter, $value, $only_is_null = FALSE)
	{
		if ($only_is_null)
		{
			if (is_null($this->result[$parameter]))
			{
				$this->result[$parameter] = $value;

				$this->set_debug('Set value "'.$value.'" ('.gettype($value).') for null parameter: '.$parameter);
			}
		}
		else
		{
			$this->result[$parameter] = $value;

			$this->set_debug('Set value "'.$value.'" ('.gettype($value).') for parameter: '.$parameter);
		}
	}

	/**
	 * Sets the data group to parameter in result array.
	*/
	private function set_result_group($parameter, $group)
	{
		if (is_null($this->result[$parameter]))
		{
			$this->result[$parameter] = array();
		}

		array_push($this->result[$parameter], $group);

		$this->set_debug('Add new group value ('.gettype($group).') for parameter: '.$parameter);
	}

	/**
	 * Sets the report text to parameter in result array.
	*/
	private function set_result_report($parameter, $report, $separator = ';')
	{
		$this->result[$parameter] .= $separator.' '.$report;

		if (!is_null($this->result[$parameter]))
		{
			$this->result[$parameter] = ucfirst(ltrim($this->result[$parameter], ' '.$separator));
		}

		$this->set_debug('Add group report value "'.$report.'" for parameter: '.$parameter);
	}

	/**
	 * Adds the debug text to debug information array.
	*/
	private function set_debug($text)
	{
		if ($this->debug_enabled)
		{
			if (is_null($this->debug))
			{
				$this->debug = array();
			}

			array_push($this->debug, $text);
		}
	}

	/**
	 * Adds the error text to parse errors array.
	*/
	private function set_error($text)
	{
		if (is_null($this->errors))
		{
			$this->errors = array();
		}

		array_push($this->errors, $text);
	}

	// --------------------------------------------------------------------
	// Methods for parsing raw parts
	// --------------------------------------------------------------------

	/**
	 * Decodes station id.
	 * section 0 - BBiii group
	 
	 * Parameters
	 * ----------
	 * BB: 
	 * iii: 
	*/
	private function get_station_id($part)
	{
		if (!preg_match('@^([0-9]{5})$@', $part, $found))
		{
			return FALSE;
		}

		$this->set_result_value('station_id', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes .
	 * section 0 - YYGGGGi group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 * GGGG: 
	 * i: 
	*/
	private function get_time($part)
	{
		//if (!preg_match('@^([0-9]{2})([0-9]{4})([0-9]{1})$@', $part, $found))  // for WMO standart
		if (!preg_match('@^([0-9]{2})([0-9]{2})([0-9]{1})$@', $part, $found))  // for Belarus standart
		{
			return FALSE;
		}

		$this->set_result_value('dayr', $found[1]);
		$this->set_result_value('hour_obs', $found[2]);
		$this->set_result_value('section_state', $found[3]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level in cm.
	 * section 1 - 1HHHH 2HHHH 3HHHH group
	 
	 * Parameters
	 * ----------
	 * code : str
	 * 	Water level in cm

	 * Returns
	 * -------
	 * int
	 * 	Water level in cm
	*/
	private function set_HHHH($code) {
        if ($code == "") {
            return NULL;
		}
        else {
			$value = intval($code);

			// if code "0218", water level equal 218 cm
			// if code "5223", water level equal -223 cm
            if (strlen($code) == 4 && substr($code, 0, 1) == 5) {
				$value = ($value - 5000) * -1;
			}

            return $value;
		}
	}
	
	/**
	 * Decode water level in cm.
	 * section 1 - 1HHHH group
	*/
	private function get_water_level($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level difference for the 8-hour observation period.
	 * section 1 - 2HHHK group
	 
	 * Parameters
	 * ----------
	 * HHH: water level in cm
	 * K: sign of the difference water level indicator
	*/
	private function get_water_level_diff($part)
	{
		if (!preg_match('@^2([0-9]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		if ($found[2] == 1) {
			$level_trend = 1;  //increasing
		}
		elseif ($found[2] == 2) {
			$level_trend = -1;  //decreasing
		}
		else {
			$level_trend = 0;  //no changes
		}
		$level = $this->set_HHHH($found[1]);
		$water_level_diff = $level_trend * $level;

		$this->set_result_value('water_level_diff', $water_level_diff);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level for the 20-hour observation period.
	 * section 1 - 3HHHH group
	 
	 * Parameters
	 * ----------
	 * HHH: water level in cm
	*/
	private function get_water_level_last_20h($part)
	{
		if (!preg_match('@^3([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_last_20h', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes water temperature information.
	 * section 1 - 4ttTT group
	 
	 * Parameters
	 * ----------
	 * code : str
	 * 	Temperature with first charater defining the sign or
	 * 	type of unit (°C or relative humidity in % for dewpoint)

	 * Returns
	 * -------
	 * float
	 * 	Temperature in degree Celsius
	*/
	private function set_water_temp($code) {
        if ($code == "" || (strpos($code, "/") !== false)) {
            return NULL;
		}
        else {
            $value = intval($code);
			$value = $value * 0.1;

            return $value;
		}
	}
	
	/**
	 * Decodes air temperature information.
	 * section 1 - 4ttTT group
	 
	 * Parameters
	 * ----------
	 * code : str
	 * 	Temperature with first charater defining the sign or
	 * 	type of unit (°C or relative humidity in % for dewpoint)

	 * Returns
	 * -------
	 * float
	 * 	Temperature in degree Celsius
	*/
	private function set_air_temp($code) {
        if ($code == "" || $code == 99 || (strpos($code, "/") !== false)) {
            return NULL;
		}
        else {
            $value = intval($code);

            if ($value > 50) {
                $value = ($value - 50) * -1;
			}
			
            return $value;
		}
	}
	
	/**
	 * Decodes temperature of water and air information.
	 * section 1 - 4ttTT group
	 
	 * Parameters
	 * ----------
	 * tt: water temperature
	 * TT: air temperature
	 
	 * Returns
	 * -------
	 * float type
	 * 	Temperature in degree Celsius
	*/
	private function get_temperature($part)
	{
		if (!preg_match('@^4([0-9/]{2})([0-9/]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		if ($found[2] == 99) {
			$water_temp = $found[1];
		} else {
			$water_temp = $this->set_water_temp($found[1]);
		}
		$air_temp = $this->set_air_temp($found[2]);

		$this->set_result_value('water_temp', $water_temp);
		$this->set_result_value('air_temp', $air_temp);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information. May be repeated up to 5 times.
	 * section 1 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena', $ice_phenomena);
		$this->set_result_value('ice_phenomena_2', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information. Additional group for section 1.
	 * section 1 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena_1_2($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena_1_2', $ice_phenomena);
		$this->set_result_value('ice_phenomena_1_2_2', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity_1_2', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes condition of the river information. May be repeated up to 5 times.
	 * section 1 - 6CCii or 6CCCC group
	 
	 * Parameters
	 * ----------
	 * CC: condition of the river
	 * CCCC: 
	 * ii: intensity condition of the river ????
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_condition_river($part)
	{
		if (!preg_match('@^6([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$condition_river_2 = NULL;
		$cond_river_intensity = NULL;
		// list of condition river containing intensity 
		$list_r_intensity = array(11, 22, 23, 24);
		
		$condition_river = $this->CONDITION_RIVER_CODE[$found[1]];
		if (in_array($found[1], $list_r_intensity)) {
			$cond_river_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->CONDITION_RIVER_CODE)) {
			$condition_river_2 = $this->CONDITION_RIVER_CODE[$found[2]];
		}

		$this->set_result_value('condition_river', $condition_river);
		$this->set_result_value('condition_river_2', $condition_river_2);
		$this->set_result_value('cond_river_intensity', $cond_river_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes condition of the river information. Additional group for section 1.
	 * section 1 - 6CCii or 6CCCC group
	 
	 * Parameters
	 * ----------
	 * CC: condition of the river
	 * CCCC: 
	 * ii: intensity condition of the river ????
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_condition_river_1_2($part)
	{
		if (!preg_match('@^6([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$condition_river_2 = NULL;
		$cond_river_intensity = NULL;
		// list of condition river containing intensity 
		$list_r_intensity = array(11, 22, 23, 24);
		
		$condition_river = $this->CONDITION_RIVER_CODE[$found[1]];
		if (in_array($found[1], $list_r_intensity)) {
			$cond_river_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->CONDITION_RIVER_CODE)) {
			$condition_river_2 = $this->CONDITION_RIVER_CODE[$found[2]];
		}

		$this->set_result_value('condition_river_1_2', $condition_river);
		$this->set_result_value('condition_river_1_2_2', $condition_river_2);
		$this->set_result_value('cond_river_intensity_1_2', $cond_river_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice thickness information.
	 * section 1 - 7DDDS group
	 
	 * Parameters
	 * ----------
	 * DDD: ice thickness in cm
	 * S: snow depth on ice in cm
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_thickness($part)
	{
		if (!preg_match('@^7([0-9]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_thickness = intval($found[1]);
		$snow_depth_on_ice = $this->SNOW_DEPTH_ICE_CODE[$found[2]];

		$this->set_result_value('ice_thickness', $ice_thickness);
		$this->set_result_value('snow_depth_on_ice', $snow_depth_on_ice);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes daily water consumption relative to level 1HHHH.
	 * section 1 - 8kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption($part)
	{
		if (!preg_match('@^8([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes amount and duration of precipitation per day.
	 * section 1 - 0RRRd group
	 
	 * Parameters
	 * ----------
	 * RRR: precipitation amount in mm
	 * d: duration of precipitation in hours
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_precipitation($part)
	{
		if (!preg_match('@^0([0-9/]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
        if ($found[1] == "///") {
            $precip = NULL;
		}
        else {
			$precip = intval($found[1]);
			if ($precip >= 990 && $precip <= 999) {
				$precip = ($precip - 990) * 0.1;
				if ($precip == 0) {
					//only traces of precipitation not measurable < 0.05
					$precip = 0;  //0.05
				}
			}
		}
		$precip_duration = $this->PRECIP_DURATION_CODE[$found[2]];

		$this->set_result_value('precip', $precip);
		$this->set_result_value('precip_duration', $precip_duration);

		$this->method++;

		return TRUE;
	}
	
	################## BEGIN SECTION 2_1 ###################
	/**
	 * Decodes section 2.
	 * section 2 - 922YY group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_2_1($part)
	{
		if (!preg_match('@^922([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('monthdayr__section_2_1', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level in cm.
	 * section 2 - 1HHHH group
	*/
	private function get_water_level__section_2_1($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level__section_2_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level difference for the 8-hour observation period.
	 * section 2 - 2HHHK group
	 
	 * Parameters
	 * ----------
	 * HHH: water level in cm
	 * K: sign of the difference water level indicator
	*/
	private function get_water_level_diff__section_2_1($part)
	{
		if (!preg_match('@^2([0-9]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		if ($found[2] == 1) {
			$level_trend = 1;  //increasing
		}
		elseif ($found[2] == 2) {
			$level_trend = -1;  //decreasing
		}
		else {
			$level_trend = 0;  //no changes
		}
		$level = $this->set_HHHH($found[1]);
		$water_level_diff = $level_trend * $level;

		$this->set_result_value('water_level_diff__section_2_1', $water_level_diff);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level for the 20-hour observation period.
	 * section 2 - 3HHHH group
	 
	 * Parameters
	 * ----------
	 * HHH: water level in cm
	*/
	private function get_water_level_last_20h__section_2_1($part)
	{
		if (!preg_match('@^3([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_last_20h__section_2_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes temperature of water and air information.
	 * section 2 - 4ttTT group
	 
	 * Parameters
	 * ----------
	 * tt: water temperature
	 * TT: air temperature
	 
	 * Returns
	 * -------
	 * float type
	 * 	Temperature in degree Celsius
	*/
	private function get_temperature__section_2_1($part)
	{
		if (!preg_match('@^4([0-9/]{2})([0-9/]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		if ($found[2] == 99) {
			$water_temp = $found[1];
		} else {
			$water_temp = $this->set_water_temp($found[1]);
		}
		$air_temp = $this->set_air_temp($found[2]);

		$this->set_result_value('water_temp__section_2_1', $water_temp);
		$this->set_result_value('air_temp__section_2_1', $air_temp);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information. May be repeated up to 5 times.
	 * section 2 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena__section_2_1($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena__section_2_1', $ice_phenomena);
		$this->set_result_value('ice_phenomena_2__section_2_1', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity__section_2_1', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information. Additional group for section 2.
	 * section 2 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena__section_2_1_2($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena__section_2_1_2', $ice_phenomena);
		$this->set_result_value('ice_phenomena_2__section_2_1_2', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity__section_2_1_2', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes condition of the river information. May be repeated up to 5 times.
	 * section 2 - 6CCii or 6CCCC group
	 
	 * Parameters
	 * ----------
	 * CC: condition of the river
	 * CCCC: 
	 * ii: intensity condition of the river ????
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_condition_river__section_2_1($part)
	{
		if (!preg_match('@^6([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$condition_river_2 = NULL;
		$cond_river_intensity = NULL;
		// list of condition river containing intensity 
		$list_r_intensity = array(11, 22, 23, 24);
		
		$condition_river = $this->CONDITION_RIVER_CODE[$found[1]];
		if (in_array($found[1], $list_r_intensity)) {
			$cond_river_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->CONDITION_RIVER_CODE)) {
			$condition_river_2 = $this->CONDITION_RIVER_CODE[$found[2]];
		}

		$this->set_result_value('condition_river__section_2_1', $condition_river);
		$this->set_result_value('condition_river_2__section_2_1', $condition_river_2);
		$this->set_result_value('cond_river_intensity__section_2_1', $cond_river_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes condition of the river information. Additional group for section 2.
	 * section 2 - 6CCii or 6CCCC group
	 
	 * Parameters
	 * ----------
	 * CC: condition of the river
	 * CCCC: 
	 * ii: intensity condition of the river ????
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_condition_river__section_2_1_2($part)
	{
		if (!preg_match('@^6([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$condition_river_2 = NULL;
		$cond_river_intensity = NULL;
		// list of condition river containing intensity 
		$list_r_intensity = array(11, 22, 23, 24);
		
		$condition_river = $this->CONDITION_RIVER_CODE[$found[1]];
		if (in_array($found[1], $list_r_intensity)) {
			$cond_river_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->CONDITION_RIVER_CODE)) {
			$condition_river_2 = $this->CONDITION_RIVER_CODE[$found[2]];
		}

		$this->set_result_value('condition_river__section_2_1_2', $condition_river);
		$this->set_result_value('condition_river_2__section_2_1_2', $condition_river_2);
		$this->set_result_value('cond_river_intensity__section_2_1_2', $cond_river_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice thickness information.
	 * section 2 - 7DDDS group
	 
	 * Parameters
	 * ----------
	 * DDD: ice thickness in cm
	 * S: snow depth on ice in cm
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_thickness__section_2_1($part)
	{
		if (!preg_match('@^7([0-9]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_thickness = intval($found[1]);
		$snow_depth_on_ice = $this->SNOW_DEPTH_ICE_CODE[$found[2]];

		$this->set_result_value('ice_thickness__section_2_1', $ice_thickness);
		$this->set_result_value('snow_depth_on_ice__section_2_1', $snow_depth_on_ice);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes daily water consumption relative to level 1HHHH.
	 * section 2 - 8kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption__section_2_1($part)
	{
		if (!preg_match('@^8([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption__section_2_1', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes amount and duration of precipitation per day.
	 * section 2 - 0RRRd group
	 
	 * Parameters
	 * ----------
	 * RRR: precipitation amount in mm
	 * d: duration of precipitation in hours
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_precipitation__section_2_1($part)
	{
		if (!preg_match('@^0([0-9/]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
        if ($found[1] == "///") {
            $precip = NULL;
		}
        else {
			$precip = intval($found[1]);
			if ($precip >= 990 && $precip <= 999) {
				$precip = ($precip - 990) * 0.1;
				if ($precip == 0) {
					//only traces of precipitation not measurable < 0.05
					$precip = 0;  //0.05
				}
			}
		}
		$precip_duration = $this->PRECIP_DURATION_CODE[$found[2]];

		$this->set_result_value('precip__section_2_1', $precip);
		$this->set_result_value('precip_duration__section_2_1', $precip_duration);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 2_1 ###################
	
	################## BEGIN SECTION 2_2 ###################
	
	/**
	 * Decodes section 2.
	 * section 2 - 922YY group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_2_2($part)
	{
		if (!preg_match('@^922([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('monthdayr__section_2_2', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level in cm.
	 * section 2 - 1HHHH group
	*/
	private function get_water_level__section_2_2($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level__section_2_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level difference for the 8-hour observation period.
	 * section 2 - 2HHHK group
	 
	 * Parameters
	 * ----------
	 * HHH: water level in cm
	 * K: sign of the difference water level indicator
	*/
	private function get_water_level_diff__section_2_2($part)
	{
		if (!preg_match('@^2([0-9]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		if ($found[2] == 1) {
			$level_trend = 1;  //increasing
		}
		elseif ($found[2] == 2) {
			$level_trend = -1;  //decreasing
		}
		else {
			$level_trend = 0;  //no changes
		}
		$level = $this->set_HHHH($found[1]);
		$water_level_diff = $level_trend * $level;

		$this->set_result_value('water_level_diff__section_2_2', $water_level_diff);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level for the 20-hour observation period.
	 * section 2 - 3HHHH group
	 
	 * Parameters
	 * ----------
	 * HHH: water level in cm
	*/
	private function get_water_level_last_20h__section_2_2($part)
	{
		if (!preg_match('@^3([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_last_20h__section_2_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes temperature of water and air information.
	 * section 2 - 4ttTT group
	 
	 * Parameters
	 * ----------
	 * tt: water temperature
	 * TT: air temperature
	 
	 * Returns
	 * -------
	 * float type
	 * 	Temperature in degree Celsius
	*/
	private function get_temperature__section_2_2($part)
	{
		if (!preg_match('@^4([0-9/]{2})([0-9/]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		if ($found[2] == 99) {
			$water_temp = $found[1];
		} else {
			$water_temp = $this->set_water_temp($found[1]);
		}
		$air_temp = $this->set_air_temp($found[2]);

		$this->set_result_value('water_temp__section_2_2', $water_temp);
		$this->set_result_value('air_temp__section_2_2', $air_temp);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information. May be repeated up to 5 times.
	 * section 2 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena__section_2_2($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena__section_2_2', $ice_phenomena);
		$this->set_result_value('ice_phenomena_2__section_2_2', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity__section_2_2', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information. Additional group for section 2.
	 * section 2 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena__section_2_2_2($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena__section_2_2_2', $ice_phenomena);
		$this->set_result_value('ice_phenomena_2__section_2_2_2', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity__section_2_2_2', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes condition of the river information. May be repeated up to 5 times.
	 * section 2 - 6CCii or 6CCCC group
	 
	 * Parameters
	 * ----------
	 * CC: condition of the river
	 * CCCC: 
	 * ii: intensity condition of the river ????
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_condition_river__section_2_2($part)
	{
		if (!preg_match('@^6([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$condition_river_2 = NULL;
		$cond_river_intensity = NULL;
		// list of condition river containing intensity 
		$list_r_intensity = array(11, 22, 23, 24);
		
		$condition_river = $this->CONDITION_RIVER_CODE[$found[1]];
		if (in_array($found[1], $list_r_intensity)) {
			$cond_river_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->CONDITION_RIVER_CODE)) {
			$condition_river_2 = $this->CONDITION_RIVER_CODE[$found[2]];
		}

		$this->set_result_value('condition_river__section_2_2', $condition_river);
		$this->set_result_value('condition_river_2__section_2_2', $condition_river_2);
		$this->set_result_value('cond_river_intensity__section_2_2', $cond_river_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes condition of the river information. Additional group for section 2.
	 * section 2 - 6CCii or 6CCCC group
	 
	 * Parameters
	 * ----------
	 * CC: condition of the river
	 * CCCC: 
	 * ii: intensity condition of the river ????
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_condition_river__section_2_2_2($part)
	{
		if (!preg_match('@^6([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$condition_river_2 = NULL;
		$cond_river_intensity = NULL;
		// list of condition river containing intensity 
		$list_r_intensity = array(11, 22, 23, 24);
		
		$condition_river = $this->CONDITION_RIVER_CODE[$found[1]];
		if (in_array($found[1], $list_r_intensity)) {
			$cond_river_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->CONDITION_RIVER_CODE)) {
			$condition_river_2 = $this->CONDITION_RIVER_CODE[$found[2]];
		}

		$this->set_result_value('condition_river__section_2_2_2', $condition_river);
		$this->set_result_value('condition_river_2__section_2_2_2', $condition_river_2);
		$this->set_result_value('cond_river_intensity__section_2_2_2', $cond_river_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice thickness information.
	 * section 2 - 7DDDS group
	 
	 * Parameters
	 * ----------
	 * DDD: ice thickness in cm
	 * S: snow depth on ice in cm
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_thickness__section_2_2($part)
	{
		if (!preg_match('@^7([0-9]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_thickness = intval($found[1]);
		$snow_depth_on_ice = $this->SNOW_DEPTH_ICE_CODE[$found[2]];

		$this->set_result_value('ice_thickness__section_2_2', $ice_thickness);
		$this->set_result_value('snow_depth_on_ice__section_2_2', $snow_depth_on_ice);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes daily water consumption relative to level 1HHHH.
	 * section 2 - 8kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption__section_2_2($part)
	{
		if (!preg_match('@^8([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption__section_2_2', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	
	/**
	 * Decodes amount and duration of precipitation per day.
	 * section 2 - 0RRRd group
	 
	 * Parameters
	 * ----------
	 * RRR: precipitation amount in mm
	 * d: duration of precipitation in hours
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_precipitation__section_2_2($part)
	{
		if (!preg_match('@^0([0-9/]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
        if ($found[1] == "///") {
            $precip = NULL;
		}
        else {
			$precip = intval($found[1]);
			if ($precip >= 990 && $precip <= 999) {
				$precip = ($precip - 990) * 0.1;
				if ($precip == 0) {
					//only traces of precipitation not measurable < 0.05
					$precip = 0;  //0.05
				}
			}
		}
		$precip_duration = $this->PRECIP_DURATION_CODE[$found[2]];

		$this->set_result_value('precip__section_2_2', $precip);
		$this->set_result_value('precip_duration__section_2_2', $precip_duration);

		$this->method++;

		return TRUE;
	}
	################## END SECTION 2_2 ###################
	
	################## BEGIN SECTION 3_1 ###################
	/**
	 * Decodes section 3.
	 * section 3 - 933TT group
	 
	 * Parameters
	 * ----------
	 * TT: 
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_3_1($part)
	{
		if (!preg_match('@^933([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$period_avg_extreme = $this->PERIOD_AVG_EXTREME_VALUES[$found[1]];
		
		$this->set_result_value('period_avg_extreme_3_1', $period_avg_extreme);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode average water level in cm.
	 * section 3 - 1HHHH group
	*/
	private function get_water_level_avg__section_3_1($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_avg__section_3_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode highest water level in cm.
	 * section 3 - 2HHHH group
	*/
	private function get_water_level_highest__section_3_1($part)
	{
		if (!preg_match('@^2([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_highest__section_3_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode highest water level in cm.
	 * section 3 - 3HHHH group
	*/
	private function get_water_level_lowest__section_3_1($part)
	{
		if (!preg_match('@^3([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_lowest__section_3_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average consumption (inflow) of water for the period.
	 * section 3 - 4kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption_avg__section_3_1($part)
	{
		if (!preg_match('@^4([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption_avg__section_3_1', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average consumption (inflow) of water for the period.
	 * section 3 - 5kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption_highest__section_3_1($part)
	{
		if (!preg_match('@^5([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption_highest__section_3_1', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average consumption (inflow) of water for the period.
	 * section 3 - 6kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption_lowest__section_3_1($part)
	{
		if (!preg_match('@^6([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption_lowest__section_3_1', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes time of passage of the highest water level (flow rate).
	 * section 3 - 7YYGG group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 * GG: 
	*/
	private function get_time_water_level_highest__section_3_1($part)
	{
		if (!preg_match('@^7([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}

		$this->set_result_value('monthdayr_water_level_highest__section_3_1', $found[1]);
		$this->set_result_value('hourr_water_level_highest__section_3_1', $found[2]);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 3_1 ###################

	################## BEGIN SECTION 3_2 ###################
	/**
	 * Decodes section 3.
	 * section 3 - 933TT group
	 
	 * Parameters
	 * ----------
	 * TT: 
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_3_2($part)
	{
		if (!preg_match('@^933([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$period_avg_extreme = $this->PERIOD_AVG_EXTREME_VALUES[$found[1]];
		
		$this->set_result_value('period_avg_extreme_3_2', $period_avg_extreme);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode average water level in cm.
	 * section 3 - 1HHHH group
	*/
	private function get_water_level_avg__section_3_2($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_avg__section_3_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode highest water level in cm.
	 * section 3 - 2HHHH group
	*/
	private function get_water_level_highest__section_3_2($part)
	{
		if (!preg_match('@^2([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_highest__section_3_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode highest water level in cm.
	 * section 3 - 3HHHH group
	*/
	private function get_water_level_lowest__section_3_2($part)
	{
		if (!preg_match('@^3([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_lowest__section_3_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average consumption (inflow) of water for the period.
	 * section 3 - 4kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption_avg__section_3_2($part)
	{
		if (!preg_match('@^4([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption_avg__section_3_2', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average consumption (inflow) of water for the period.
	 * section 3 - 5kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption_highest__section_3_2($part)
	{
		if (!preg_match('@^5([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption_highest__section_3_2', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average consumption (inflow) of water for the period.
	 * section 3 - 6kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption_lowest__section_3_2($part)
	{
		if (!preg_match('@^6([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption_lowest__section_3_2', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes time of passage of the highest water level (flow rate).
	 * section 3 - 7YYGG group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 * GG: 
	*/
	private function get_time_water_level_highest__section_3_2($part)
	{
		if (!preg_match('@^7([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}

		$this->set_result_value('monthdayr_water_level_highest__section_3_2', $found[1]);
		$this->set_result_value('hourr_water_level_highest__section_3_2', $found[2]);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 3_2 ###################

	################## BEGIN SECTION 4_1 ###################
	/**
	 * Decodes section 4.
	 * section 4 - 944YY group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_4_1($part)
	{
		if (!preg_match('@^944([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('monthdayr__section_4_1', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level of the upper pool of the hydropost in cm.
	 * section 4 - 1HHHH group
	*/
	private function get_water_level_upper_pool__section_4_1($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_upper_pool__section_4_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode average water level of the pool of the hydropost for current observation period in cm.
	 * section 4 - 2HHHH group
	*/
	private function get_water_level_avg_current__section_4_1($part)
	{
		if (!preg_match('@^2([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_avg_current__section_4_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode average water level of the pool of the hydropost for post observation period in cm.
	 * section 4 - 3HHHH group
	*/
	private function get_water_level_avg_post__section_4_1($part)
	{
		if (!preg_match('@^3([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_avg_post__section_4_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level of the lower pool of the hydropost for current observation period in cm.
	 * section 4 - 4HHHH group
	*/
	private function get_water_level_lower_pool_current__section_4_1($part)
	{
		if (!preg_match('@^4([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_lower_pool_current__section_4_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode highest water level of the lower pool of the hydropost for post observation period in cm.
	 * section 4 - 5HHHH group
	*/
	private function get_water_level_lower_pool_hilevel_post__section_4_1($part)
	{
		if (!preg_match('@^5([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_lower_pool_hilevel_post__section_4_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode lowest water level of the lower pool of the hydropost for post observation period in cm.
	 * section 4 - 6HHHH group
	*/
	private function get_water_level_lower_pool_lolevel_post__section_4_1($part)
	{
		if (!preg_match('@^6([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_lower_pool_lolevel_post__section_4_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average volume of water for current observation period.
	 * section 4 - 7kVVV group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water volume
	 * VVV: water volume in 10^6 m^3
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_volume_avg_current__section_4_1($part)
	{
		if (!preg_match('@^7([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_volume = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_volume_avg_current__section_4_1', $w_volume);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average volume of water for post observation period.
	 * section 4 - 8kVVV group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water volume
	 * VVV: water volume in 10^6 m^3
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_volume_avg_post__section_4_1($part)
	{
		if (!preg_match('@^8([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_volume = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_volume_avg_post__section_4_1', $w_volume);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 4_1 ###################

	################## BEGIN SECTION 4_2 ###################
	/**
	 * Decodes section 4.
	 * section 4 - 944YY group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_4_2($part)
	{
		if (!preg_match('@^944([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('monthdayr__section_4_2', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level of the upper pool of the hydropost in cm.
	 * section 4 - 1HHHH group
	*/
	private function get_water_level_upper_pool__section_4_2($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_upper_pool__section_4_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode average water level of the pool of the hydropost for current observation period in cm.
	 * section 4 - 2HHHH group
	*/
	private function get_water_level_avg_current__section_4_2($part)
	{
		if (!preg_match('@^2([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_avg_current__section_4_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode average water level of the pool of the hydropost for post observation period in cm.
	 * section 4 - 3HHHH group
	*/
	private function get_water_level_avg_post__section_4_2($part)
	{
		if (!preg_match('@^3([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_avg_post__section_4_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level of the lower pool of the hydropost for current observation period in cm.
	 * section 4 - 4HHHH group
	*/
	private function get_water_level_lower_pool_current__section_4_2($part)
	{
		if (!preg_match('@^4([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_lower_pool_current__section_4_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode highest water level of the lower pool of the hydropost for post observation period in cm.
	 * section 4 - 5HHHH group
	*/
	private function get_water_level_lower_pool_hilevel_post__section_4_2($part)
	{
		if (!preg_match('@^5([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_lower_pool_hilevel_post__section_4_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode lowest water level of the lower pool of the hydropost for post observation period in cm.
	 * section 4 - 6HHHH group
	*/
	private function get_water_level_lower_pool_lolevel_post__section_4_2($part)
	{
		if (!preg_match('@^6([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_lower_pool_lolevel_post__section_4_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average volume of water for current observation period.
	 * section 4 - 7kVVV group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water volume
	 * VVV: water volume in 10^6 m^3
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_volume_avg_current__section_4_2($part)
	{
		if (!preg_match('@^7([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_volume = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_volume_avg_current__section_4_2', $w_volume);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average volume of water for post observation period.
	 * section 4 - 8kVVV group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water volume
	 * VVV: water volume in 10^6 m^3
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_volume_avg_post__section_4_2($part)
	{
		if (!preg_match('@^8([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_volume = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_volume_avg_post__section_4_2', $w_volume);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 4_2 ###################
	
	################## BEGIN SECTION 5_1 ###################
	/**
	 * Decodes section 5.
	 * section 5 - 944YY group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_5_1($part)
	{
		if (!preg_match('@^955([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('monthdayr__section_5_1', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes total water inflow for current observation period.
	 * section 5 - 1kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_total_current__section_5_1($part)
	{
		if (!preg_match('@^1([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_total_current__section_5_1', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes lateral water inflow for current observation period.
	 * section 5 - 2kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_lateral_current__section_5_1($part)
	{
		if (!preg_match('@^2([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_lateral_current__section_5_1', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes inflow of water to the water area of the reservoir for current observation period.
	 * section 5 - 3kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_area_current__section_5_1($part)
	{
		if (!preg_match('@^3([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_area_current__section_5_1', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes total water inflow for post observation period.
	 * section 5 - 4kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_total_post__section_5_1($part)
	{
		if (!preg_match('@^4([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_total_post__section_5_1', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes lateral water inflow for post observation period.
	 * section 5 - 5kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_lateral_post__section_5_1($part)
	{
		if (!preg_match('@^5([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_lateral_post__section_5_1', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes inflow of water to the water area of the reservoir for post observation period.
	 * section 5 - 6kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_area_post__section_5_1($part)
	{
		if (!preg_match('@^6([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_area_post__section_5_1', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes water discharge through the hydropost for post observation period.
	 * section 5 - 7kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_discharge_post__section_5_1($part)
	{
		if (!preg_match('@^7([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_discharge_post__section_5_1', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 5_1 ###################
	
	################## BEGIN SECTION 5_2 ###################
	/**
	 * Decodes section 5.
	 * section 5 - 944YY group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_5_2($part)
	{
		if (!preg_match('@^955([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('monthdayr__section_5_2', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes total water inflow for current observation period.
	 * section 5 - 1kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_total_current__section_5_2($part)
	{
		if (!preg_match('@^1([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_total_current__section_5_2', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes lateral water inflow for current observation period.
	 * section 5 - 2kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_lateral_current__section_5_2($part)
	{
		if (!preg_match('@^2([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_lateral_current__section_5_2', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes inflow of water to the water area of the reservoir for current observation period.
	 * section 5 - 3kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_area_current__section_5_2($part)
	{
		if (!preg_match('@^3([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_area_current__section_5_2', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes total water inflow for post observation period.
	 * section 5 - 4kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_total_post__section_5_2($part)
	{
		if (!preg_match('@^4([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_total_post__section_5_2', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes lateral water inflow for post observation period.
	 * section 5 - 5kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_lateral_post__section_5_2($part)
	{
		if (!preg_match('@^5([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_lateral_post__section_5_2', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes inflow of water to the water area of the reservoir for post observation period.
	 * section 5 - 6kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_area_post__section_5_2($part)
	{
		if (!preg_match('@^6([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_area_post__section_5_2', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes water discharge through the hydropost for post observation period.
	 * section 5 - 7kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water inflow
	 * QQQ: water inflow in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_inflow_discharge_post__section_5_2($part)
	{
		if (!preg_match('@^7([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_inflow = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_inflow_discharge_post__section_5_2', $w_inflow);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 5_2 ###################
	
	################## BEGIN SECTION 6_1 ###################
	/**
	 * Decodes section 6.
	 * section 6 - 966MM group
	 
	 * Parameters
	 * ----------
	 * MM: month of water flow measurements
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_flow_6_1($part)
	{
		if (!preg_match('@^966([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('month_flow_6_1', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode average water level in cm.
	 * section 6 - 1HHHH group
	*/
	private function get_water_level_avg__section_6_1($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_avg__section_6_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average consumption (inflow) of water for the period.
	 * section 6 - 2kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption_avg__section_6_1($part)
	{
		if (!preg_match('@^2([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption_avg__section_6_1', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes river flow area.
	 * section 6 - 3kFFF group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for river flow area
	 * FFF: river flow area in m^2
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_river_area__section_6_1($part)
	{
		if (!preg_match('@^3([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('river_area__section_6_1', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode maximum measuring depth in cm.
	 * section 6 - 4hhhh group
	 
	 * Parameters
	 * ----------
	 * hhhh: maximum measuring depth in cm
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_max_depth__section_6_1($part)
	{
		if (!preg_match('@^4([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = intval($found[1]);

		$this->set_result_value('max_depth__section_6_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes date of water flow measurement.
	 * section 6 - 5YYGG group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 * GG: 
	*/
	private function get_date_water_flow__section_6_1($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}

		$this->set_result_value('monthdayr_water_flow__section_6_1', $found[1]);
		$this->set_result_value('hourr_water_flow__section_6_1', $found[2]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes section 6.
	 * section 6 - 966MM group
	 
	 * Parameters
	 * ----------
	 * MM: month of observations of the state of the surface of the lake or reservoir
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_surface_6_1($part)
	{
		if (!preg_match('@^966([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('month_surface_6_1', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes wind direction and speed on a lake or reservoir.
	 * section 6 - 6ddff group
	 
	 * Parameters
	 * ----------
	 * dd: wind direction in dekadegree (10 minute mean)
	 * ff: wind speed (10 minute mean) in m/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_wind__section_6_1($part)
	{
		if (!preg_match('@^6([0-9/]{2})([0-9/]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$wind_dir = $this->WIND_DIR_COMPASS[$found[1]];  // in rhumb

		$wind_speed_code = $found[2];
        if ($wind_speed_code == "" || $wind_speed_code == "//") {
            $wind_speed = NULL;  //not observed
		}
        else {
			$wind_speed = intval($wind_speed_code);  // in mps
		}

		$this->set_result_value('wind_direction__section_6_1', $wind_dir);
		$this->set_result_value('wind_speed__section_6_1', $wind_speed);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes waves on a lake or reservoir.
	 * section 6 - 7dHHC group
	 
	 * Parameters
	 * ----------
	 * d: wind direction in dekadegree (where is the wave coming from)
	 * HH: height of wind waves in decimeters
	 * C: characteristic of the state of the surface on reservoir in points
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_wind_waves_surface__section_6_1($part)
	{
		if (!preg_match('@^7([0-9/]{1})([0-9]{2})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		$wind_dir = $this->WIND_DIR_COMPASS_WAVES[$found[1]];  // in rhumb		
		
		$wave_height = intval($found[2]);
		
		$state_surface = $this->POINTS_STATE_SURFACE[$found[3]];		

		$this->set_result_value('wind_direction_waves__section_6_1', $wind_dir);
		$this->set_result_value('wave_height__section_6_1', $wave_height);
		$this->set_result_value('state_surface__section_6_1', $state_surface);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes time of observation of wind and waves of water .
	 * section 6 - 8YYGG group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 * GG: hour of observation of the wind and waves of the water according to local time
	*/
	private function get_date_water_waves__section_6_1($part)
	{
		if (!preg_match('@^8([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}

		$this->set_result_value('monthdayr_water_waves__section_6_1', $found[1]);
		$this->set_result_value('hourr_water_waves__section_6_1', $found[2]);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 6_1 ###################
	
	################## BEGIN SECTION 6_2 ###################
	/**
	 * Decodes section 6.
	 * section 6 - 966MM group
	 
	 * Parameters
	 * ----------
	 * MM: month of water flow measurements
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_flow_6_2($part)
	{
		if (!preg_match('@^966([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('month_flow_6_2', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode average water level in cm.
	 * section 6 - 1HHHH group
	*/
	private function get_water_level_avg__section_6_2($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level_avg__section_6_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes average consumption (inflow) of water for the period.
	 * section 6 - 2kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption_avg__section_6_2($part)
	{
		if (!preg_match('@^2([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption_avg__section_6_2', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes river flow area.
	 * section 6 - 3kFFF group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for river flow area
	 * FFF: river flow area in m^2
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_river_area__section_6_2($part)
	{
		if (!preg_match('@^3([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('river_area__section_6_2', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode maximum measuring depth in cm.
	 * section 6 - 4hhhh group
	 
	 * Parameters
	 * ----------
	 * hhhh: maximum measuring depth in cm
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_max_depth__section_6_2($part)
	{
		if (!preg_match('@^4([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = intval($found[1]);

		$this->set_result_value('max_depth__section_6_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes date of water flow measurement.
	 * section 6 - 5YYGG group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 * GG: 
	*/
	private function get_date_water_flow__section_6_2($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}

		$this->set_result_value('monthdayr_water_flow__section_6_2', $found[1]);
		$this->set_result_value('hourr_water_flow__section_6_2', $found[2]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes section 6.
	 * section 6 - 966MM group
	 
	 * Parameters
	 * ----------
	 * MM: month of observations of the state of the surface of the lake or reservoir
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_section_surface_6_2($part)
	{
		if (!preg_match('@^966([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$this->set_result_value('month_surface_6_2', $found[1]);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes wind direction and speed on a lake or reservoir.
	 * section 6 - 6ddff group
	 
	 * Parameters
	 * ----------
	 * dd: wind direction in dekadegree (10 minute mean)
	 * ff: wind speed (10 minute mean) in m/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_wind__section_6_2($part)
	{
		if (!preg_match('@^6([0-9/]{2})([0-9/]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$wind_dir = $this->WIND_DIR_COMPASS[$found[1]];  // in rhumb

		$wind_speed_code = $found[2];
        if ($wind_speed_code == "" || $wind_speed_code == "//") {
            $wind_speed = NULL;  //not observed
		}
        else {
			$wind_speed = intval($wind_speed_code);  // in mps
		}

		$this->set_result_value('wind_direction__section_6_2', $wind_dir);
		$this->set_result_value('wind_speed__section_6_2', $wind_speed);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes waves on a lake or reservoir.
	 * section 6 - 7dHHC group
	 
	 * Parameters
	 * ----------
	 * d: wind direction in dekadegree (where is the wave coming from)
	 * HH: height of wind waves in decimeters
	 * C: characteristic of the state of the surface on reservoir in points
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_wind_waves_surface__section_6_2($part)
	{
		if (!preg_match('@^7([0-9/]{1})([0-9]{2})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		$wind_dir = $this->WIND_DIR_COMPASS_WAVES[$found[1]];  // in rhumb		
		
		$wave_height = intval($found[2]);
		
		$state_surface = $this->POINTS_STATE_SURFACE[$found[3]];		

		$this->set_result_value('wind_direction_waves__section_6_2', $wind_dir);
		$this->set_result_value('wave_height__section_6_2', $wave_height);
		$this->set_result_value('state_surface__section_6_2', $state_surface);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes time of observation of wind and waves of water .
	 * section 6 - 8YYGG group
	 
	 * Parameters
	 * ----------
	 * YY: 
	 * GG: hour of observation of the wind and waves of the water according to local time
	*/
	private function get_date_water_waves__section_6_2($part)
	{
		if (!preg_match('@^8([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}

		$this->set_result_value('monthdayr_water_waves__section_6_2', $found[1]);
		$this->set_result_value('hourr_water_waves__section_6_2', $found[2]);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 6_2 ###################
	
	################## BEGIN SECTION 7_1 ###################
	/**
	 * Decodes section 7.
	 * section 7 - 977nn group
	 
	 * Parameters
	 * ----------
	 * nn: information about a spontaneous (especially dangerous) high water level
	 
	 * Returns
	 * -------
	 * nn = 01
	 * 	
	*/
	private function get_section_7_1($part)
	{
		if (!preg_match('@^97701$@', $part, $found))
		{
			return FALSE;
		}
		
		$hydro_dangerous = $this->HYDRO_DANGEROUS_CODE["01"];
		
		$this->set_result_value('about_dangerous__section_7_1', $hydro_dangerous);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level in cm.
	 * section 7 - 1HHHH group
	*/
	private function get_water_level__section_7_1($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level__section_7_1', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level difference for the 8-hour observation period.
	 * section 7 - 2HHHK group
	 
	 * Parameters
	 * ----------
	 * HHH: water level in cm
	 * K: sign of the difference water level indicator
	*/
	private function get_water_level_diff__section_7_1($part)
	{
		if (!preg_match('@^2([0-9]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		if ($found[2] == 1) {
			$level_trend = 1;  //increasing
		}
		elseif ($found[2] == 2) {
			$level_trend = -1;  //decreasing
		}
		else {
			$level_trend = 0;  //no changes
		}
		$level = $this->set_HHHH($found[1]);
		$water_level_diff = $level_trend * $level;

		$this->set_result_value('water_level_diff__section_7_1', $water_level_diff);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information.
	 * section 7 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena__section_7_1($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena__section_7_1', $ice_phenomena);
		$this->set_result_value('ice_phenomena_2__section_7_1', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity__section_7_1', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes condition of the river information.
	 * section 7 - 6CCii or 6CCCC group
	 
	 * Parameters
	 * ----------
	 * CC: condition of the river
	 * CCCC: 
	 * ii: intensity condition of the river ????
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_condition_river__section_7_1($part)
	{
		if (!preg_match('@^6([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$condition_river_2 = NULL;
		$cond_river_intensity = NULL;
		// list of condition river containing intensity 
		$list_r_intensity = array(11, 22, 23, 24);
		
		$condition_river = $this->CONDITION_RIVER_CODE[$found[1]];
		if (in_array($found[1], $list_r_intensity)) {
			$cond_river_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->CONDITION_RIVER_CODE)) {
			$condition_river_2 = $this->CONDITION_RIVER_CODE[$found[2]];
		}

		$this->set_result_value('condition_river__section_7_1', $condition_river);
		$this->set_result_value('condition_river_2__section_7_1', $condition_river_2);
		$this->set_result_value('cond_river_intensity__section_7_1', $cond_river_intensity);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 7_1 ###################
	
	################## BEGIN SECTION 7_2 ###################
	/**
	 * Decodes section 7.
	 * section 7 - 977nn group
	 
	 * Parameters
	 * ----------
	 * nn: information about a spontaneous (especially dangerous) low water level
	 
	 * Returns
	 * -------
	 * nn = 02
	 * 	
	*/
	private function get_section_7_2($part)
	{
		if (!preg_match('@^97702$@', $part, $found))
		{
			return FALSE;
		}
		
		$hydro_dangerous = $this->HYDRO_DANGEROUS_CODE["02"];
		
		$this->set_result_value('about_dangerous__section_7_2', $hydro_dangerous);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level in cm.
	 * section 7 - 1HHHH group
	*/
	private function get_water_level__section_7_2($part)
	{
		if (!preg_match('@^1([0-9]{4})$@', $part, $found))
		{
			return FALSE;
		}
		
		$level = $this->set_HHHH($found[1]);

		$this->set_result_value('water_level__section_7_2', $level);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decode water level difference for the 8-hour observation period.
	 * section 7 - 2HHHK group
	 
	 * Parameters
	 * ----------
	 * HHH: water level in cm
	 * K: sign of the difference water level indicator
	*/
	private function get_water_level_diff__section_7_2($part)
	{
		if (!preg_match('@^2([0-9]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
		if ($found[2] == 1) {
			$level_trend = 1;  //increasing
		}
		elseif ($found[2] == 2) {
			$level_trend = -1;  //decreasing
		}
		else {
			$level_trend = 0;  //no changes
		}
		$level = $this->set_HHHH($found[1]);
		$water_level_diff = $level_trend * $level;

		$this->set_result_value('water_level_diff__section_7_2', $water_level_diff);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information.
	 * section 7 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena__section_7_2($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena__section_7_2', $ice_phenomena);
		$this->set_result_value('ice_phenomena_2__section_7_2', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity__section_7_2', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes condition of the river information.
	 * section 7 - 6CCii or 6CCCC group
	 
	 * Parameters
	 * ----------
	 * CC: condition of the river
	 * CCCC: 
	 * ii: intensity condition of the river ????
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_condition_river__section_7_2($part)
	{
		if (!preg_match('@^6([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$condition_river_2 = NULL;
		$cond_river_intensity = NULL;
		// list of condition river containing intensity 
		$list_r_intensity = array(11, 22, 23, 24);
		
		$condition_river = $this->CONDITION_RIVER_CODE[$found[1]];
		if (in_array($found[1], $list_r_intensity)) {
			$cond_river_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->CONDITION_RIVER_CODE)) {
			$condition_river_2 = $this->CONDITION_RIVER_CODE[$found[2]];
		}

		$this->set_result_value('condition_river__section_7_2', $condition_river);
		$this->set_result_value('condition_river_2__section_7_2', $condition_river_2);
		$this->set_result_value('cond_river_intensity__section_7_2', $cond_river_intensity);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 7_2 ###################
	
	################## BEGIN SECTION 7_3 ###################
	/**
	 * Decodes section 7.
	 * section 7 - 977nn group
	 
	 * Parameters
	 * ----------
	 * nn: information about a spontaneous (especially dangerous) early freeze-up and ice formation
	 
	 * Returns
	 * -------
	 * nn = 03
	 * 	
	*/
	private function get_section_7_3($part)
	{
		if (!preg_match('@^97703$@', $part, $found))
		{
			return FALSE;
		}
		
		$hydro_dangerous = $this->HYDRO_DANGEROUS_CODE["03"];
		
		$this->set_result_value('about_dangerous__section_7_3', $hydro_dangerous);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes ice phenomena information.
	 * section 7 - 5EEii or 5EEEE group
	 
	 * Parameters
	 * ----------
	 * EE: ice phenomena
	 * EEEE: 
	 * ii: intensity ice phenomena
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_ice_phenomena__section_7_3($part)
	{
		if (!preg_match('@^5([0-9]{2})([0-9]{2})$@', $part, $found))
		{
			return FALSE;
		}
		
		$ice_phenomena_2 = NULL;
		$ice_p_intensity = NULL;
		// list of phenomena containing intensity 
		$list_p_intensity = array(12, 13, 16, 17, 18, 19, 39, 46, 48, 49, 50, 51, 63, 64);
		
		$ice_phenomena = $this->ICE_PHENOMENA_CODE[$found[1]];
		if (in_array($found[1], $list_p_intensity)) {
			$ice_p_intensity = intval($found[2]) * 10;
		}
		elseif (array_key_exists(intval($found[2]), $this->ICE_PHENOMENA_CODE)) {
			$ice_phenomena_2 = $this->ICE_PHENOMENA_CODE[$found[2]];
		}

		$this->set_result_value('ice_phenomena__section_7_3', $ice_phenomena);
		$this->set_result_value('ice_phenomena_2__section_7_3', $ice_phenomena_2);
		$this->set_result_value('ice_p_intensity__section_7_3', $ice_p_intensity);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 7_3 ###################
	
	################## BEGIN SECTION 7_4 ###################
	/**
	 * Decodes section 7.
	 * section 7 - 977nn group
	 
	 * Parameters
	 * ----------
	 * nn: information about a spontaneous (especially dangerous) very high or low water consumption
	 
	 * Returns
	 * -------
	 * nn = 04
	 * 	
	*/
	private function get_section_7_4($part)
	{
		if (!preg_match('@^97704$@', $part, $found))
		{
			return FALSE;
		}
		
		$hydro_dangerous = $this->HYDRO_DANGEROUS_CODE["04"];
		
		$this->set_result_value('about_dangerous__section_7_4', $hydro_dangerous);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes water consumption.
	 * section 7 - 8kQQQ group
	 
	 * Parameters
	 * ----------
	 * k: number of digits for water consumption
	 * QQQ: water consumption in m^3/s
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_w_consumption__section_7_4($part)
	{
		if (!preg_match('@^8([0-9]{1})([0-9]{3})$@', $part, $found))
		{
			return FALSE;
		}
		
		$value = intval($found[2]);
		$exp = $found[1];
		$w_consumption = $value * 0.001 * pow(10, $exp);

		$this->set_result_value('w_consumption__section_7_4', $w_consumption);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 7_4 ###################
	
	################## BEGIN SECTION 7_5 ###################
	/**
	 * Decodes section 7.
	 * section 7 - 977nn group
	 
	 * Parameters
	 * ----------
	 * nn: information about a spontaneous (especially dangerous) heavy rain
	 
	 * Returns
	 * -------
	 * nn = 05
	 * if the amount of precipitation > 30 mm in 12 hours	
	*/
	private function get_section_7_5($part)
	{
		if (!preg_match('@^97705$@', $part, $found))
		{
			return FALSE;
		}
		
		$hydro_dangerous = $this->HYDRO_DANGEROUS_CODE["05"];
		
		$this->set_result_value('about_dangerous__section_7_5', $hydro_dangerous);

		$this->method++;

		return TRUE;
	}
	
	/**
	 * Decodes amount and duration of precipitation per day.
	 * section 7 - 0RRRd group
	 
	 * Parameters
	 * ----------
	 * RRR: precipitation amount in mm
	 * d: duration of precipitation in hours
	 
	 * Returns
	 * -------
	 * 
	 * 	
	*/
	private function get_precipitation__section_7_5($part)
	{
		if (!preg_match('@^0([0-9/]{3})([0-9]{1})$@', $part, $found))
		{
			return FALSE;
		}
		
        if ($found[1] == "///") {
            $precip = NULL;
		}
        else {
			$precip = intval($found[1]);
			if ($precip >= 990 && $precip <= 999) {
				$precip = ($precip - 990) * 0.1;
				if ($precip == 0) {
					//only traces of precipitation not measurable < 0.05
					$precip = 0;  //0.05
				}
			}
		}
		$precip_duration = $this->PRECIP_DURATION_CODE[$found[2]];

		$this->set_result_value('precip__section_7_5', $precip);
		$this->set_result_value('precip_duration__section_7_5', $precip_duration);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 7_5 ###################
	
	################## BEGIN SECTION 7_6 ###################
	/**
	 * Decodes section 7.
	 * section 7 - 977nn group
	 
	 * Parameters
	 * ----------
	 * nn: information about a spontaneous (especially dangerous) dirty water flow
	 
	 * Returns
	 * -------
	 * nn = 06
	 * 	
	*/
	private function get_section_7_6($part)
	{
		if (!preg_match('@^97706$@', $part, $found))
		{
			return FALSE;
		}
		
		$hydro_dangerous = $this->HYDRO_DANGEROUS_CODE["06"];
		
		$this->set_result_value('about_dangerous__section_7_6', $hydro_dangerous);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 7_6 ###################
	
	################## BEGIN SECTION 7_7 ###################
	/**
	 * Decodes section 7.
	 * section 7 - 977nn group
	 
	 * Parameters
	 * ----------
	 * nn: information about a spontaneous (especially dangerous) avalanche
	 
	 * Returns
	 * -------
	 * nn = 07
	 * 	
	*/
	private function get_section_7_7($part)
	{
		if (!preg_match('@^97707$@', $part, $found))
		{
			return FALSE;
		}
		
		$hydro_dangerous = $this->HYDRO_DANGEROUS_CODE["07"];
		
		$this->set_result_value('about_dangerous__section_7_7', $hydro_dangerous);

		$this->method++;

		return TRUE;
	}
	
	################## END SECTION 7_7 ###################
	
/***************** END HYDRO *********************************************/
}

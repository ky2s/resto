<?php
/*
Flow:
- cek day index hari ini 
- cek if day index ada di array harislot 
-- if ada maka get the next array lalu join lalu get date
-- if tidak maka get the next array only lalu get date
-- if current array is the last array then back to first array and get the date
*/
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');
if ( function_exists( 'ot_get_option' ) ) {
  $hari_timeslot = ot_get_option( 'hari_timeslot' );
}
date_default_timezone_set('GMT');
$curTime = strtotime('+' . get_option('gmt_offset') . ' hours');
$date = date('Y-m-d', $curTime);
$weekOfdays = array();
$weekOfdays[] = date('l d/m', strtotime($date));
for($i =1; $i <= 6; $i++){
    $weekOfdays[] = date('l d/m', strtotime("+$i day", strtotime($date)));
}
function harijam($hari) {
	if ( function_exists( 'ot_get_option' ) ) {
	  $jam_timeslot = ot_get_option( 'jam_timeslot', array() );
	  foreach ($jam_timeslot as $key => $subArr) { 
		unset($jam_timeslot[$key]['title']);      
	  }  
	}
	foreach($jam_timeslot as $jam) {
		echo $hari . ' (' . $jam['slot_mulai'] . ' - ' . $jam['slot_hingga'] . ')<br>';
	}
}
$seminggu = array();
foreach($weekOfdays as $days) {
	if(strpos($days, 'Monday') !== false) $seminggu[1] = str_replace('Monday', 'Senin', $days);
	if(strpos($days, 'Tuesday') !== false) $seminggu[2] = str_replace('Tuesday', 'Selasa', $days);
	if(strpos($days, 'Wednesday') !== false) $seminggu[3] = str_replace('Wednesday', 'Rabu', $days);
	if(strpos($days, 'Thursday') !== false) $seminggu[4] = str_replace('Thursday', 'Kamis', $days);
	if(strpos($days, 'Friday') !== false) $seminggu[5] = str_replace('Friday', 'Jumat', $days);
	if(strpos($days, 'Saturday') !== false) $seminggu[6] = str_replace('Saturday', 'Sabtu', $days);
	if(strpos($days, 'Sunday') !== false) $seminggu[7] = str_replace('Sunday', 'Minggu', $days);
}
$todayIndex = date('N', $curTime);
if(count($hari_timeslot) === 1) {
	if(in_array($todayIndex,$hari_timeslot)) {
		harijam($seminggu[$todayIndex]);
	} else {
		harijam($seminggu[reset($hari_timeslot)]);
	}
} else {
	if(in_array($todayIndex,$hari_timeslot)) {
		$keys = array_keys($hari_timeslot);
		$size = count($keys);
		$foundKey = array_search($todayIndex,$hari_timeslot);
		if($foundKey !== false) {
			$nextKey = array_search($foundKey,$keys)+1;
			if($nextKey < $size) {
				harijam($seminggu[$todayIndex]);
				harijam($seminggu[$hari_timeslot[$keys[$nextKey]]]);
			} else {
				harijam($seminggu[$todayIndex]);
				harijam($seminggu[reset($hari_timeslot)]);
			}
		}
	} else {
		$nearestIndex = array_filter($hari_timeslot, function($n) use($todayIndex){ return $n > $todayIndex; });
		harijam($seminggu[reset($nearestIndex)]);
	}
}
?>
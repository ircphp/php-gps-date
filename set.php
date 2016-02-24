<?php

define('NMEA_DEV', '/dev/ttyS2'); // GPS device with NMEA messages
define('NMEA_LATENCY', 2);        // Empirical GPS latency in seconds

//date_default_timezone_set('Europe/Kiev'); // Timezone, may already set in env

if (date('Y') < 2016 && $gps_src = fopen(NMEA_DEV, 'r'))
  while (!feof($gps_src) && false !== ($nmea_line = fgets($gps_src)))
    if (preg_match('/^\$GPRMC\,(\d{6}).*\,(\d{6})\,/', $nmea_line, $mline)) {
      fclose($gps_src);

      $oDate = DateTime::createFromFormat('Hisdmy', $mline[1].$mline[2]);
      $oDate->add(new DateInterval('PT'.(date('Z') + NMEA_LATENCY).'S'));
      $dttm = $oDate->format('j M Y H:i:s');

      `date -s '$dttm'`;
      exit();
    }

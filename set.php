<?php

if (date('Y') < 2016) {
  $nmea_src = '/dev/ttyS2';

  if ($gps_src = fopen($nmea_src, 'r'))
    while (!feof($gps_src) && false !== ($gps_res = fgets($gps_src)))
      if (preg_match('/^\$GPRMC\,(\d{6}).*\,(\d{6})\,/', $gps_res, $gps_msg)) {
        fclose($gps_src);

        $date = DateTime::createFromFormat('Hisdmy', $gps_msg[1].$gps_msg[2]);
        $date->add(new DateInterval('PT2H2S'));
        $dttm = $date->format('j M Y H:i:s');

        `date -s '$dttm'`;
        exit();
      }
}

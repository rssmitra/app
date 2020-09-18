<?php

/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final class Tanggal {

    public  function replacebulan($bulan) {
        $bulan-=1;
        $array = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $intBulan = intVal($bulan);
        $result = $array[$intBulan];
        return $result;
    }

    public  function formatDate($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d', $y, $m, $d);
            $bulan = tanggal::getBulan($m);
            $tanggal = $d . " " . $bulan . " " . $y;
        }

        return $tanggal;
    }

    public  function formatDateShort($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d', $y, $m, $d);
            $bulan = tanggal::getBln($m);
            $tanggal = $d . " " . $bulan . " " . $y;
        }

        return $tanggal;
    }

    public  function formatDateStrip($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d', $m, $d, $y);
            $bulan = tanggal::getBulan($m);
            $tanggal = $d . " " . $bulan . " " . $y;
        }

        return $tanggal;
    }

    public  function formatDateMdy($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d', $m, $d, $y);
            $bulan = tanggal::getBln($m);
            $tanggal = $d . " " . $bulan . " " . $y;
        }

        return $tanggal;
    }

    public  function getHariFromDate($input) {
        if (empty($input)) {
            $hari = "-";
        } else {
            sscanf($input, '%d-%d-%d', $m, $d, $y);
            $tanggal = $y."-".$m."-".$d;
            $timestamp = strtotime($tanggal);
            $day = date('D', $timestamp);
            $hari = tanggal::getHari($day);
        }

        return $hari;
    }

    public  function formatDateForm($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d', $y, $m, $d);
            $tanggal = $m . "/" . $d . "/" . $y;
        }

        return $tanggal;
    }

    public  function formatDatedmY($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d', $y, $m, $d);
            $tanggal = $d . "/" . $m . "/" . $y;
        }

        return $tanggal;
    }


    public  function formatDateTime($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, "%d-%d-%d %d:%d:%d", $y, $m, $d, $h, $i, $s);
            $bulan = tanggal::getBln($m);
            
            $h = tanggal::normalDigit($h);
            $i = tanggal::normalDigit($i);
            $s = tanggal::normalDigit($s);
            
            $tanggal = $d . " " . $bulan . " " . $y. " - ".$h.":".$i.":".$s."";
        }

        return $tanggal;
    }

    public  function formatDateTimeToSqlDate($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, "%d-%d-%d %d:%d:%d", $y, $m, $d, $h, $i, $s);
            $bulan = tanggal::getBln($m);
            
            $h = tanggal::normalDigit($h);
            $i = tanggal::normalDigit($i);
            $s = tanggal::normalDigit($s);
            
            $tanggal = $y . "-" . $m . "-" . $d;
        }

        return $tanggal;
    }
    
    public  function formatDateTimeForm($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d %d:%d:%d', $y, $m, $d, $h, $i, $s);
            $h = tanggal::normalDigit($h);
            $i = tanggal::normalDigit($i);
            $s = tanggal::normalDigit($s);
            
            $tanggal = $m . "/" . $d . "/" . $y. " ".$h.":".$i.":".$s."";
        }

        return $tanggal;
    }

    public  function formatDateTimeFormDmy($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d %d:%d:%d', $y, $m, $d, $h, $i, $s);
            $h = tanggal::normalDigit($h);
            $i = tanggal::normalDigit($i);
            $s = tanggal::normalDigit($s);
            
            $tanggal = $d . "/" . $m . "/" . $y. " ".$h.":".$i.":".$s."";
        }

        return $tanggal;
    }

    public  function formatTime($input) {
        if (empty($input)) {
            $tanggal = "-"; 
        } else {
            sscanf($input, '%d:%d:%d', $h, $i, $s);
            
            $h = tanggal::normalDigit($h);
            $i = tanggal::normalDigit($i);
            
            //$tanggal = $h.":".$i."";
            $tanggal = "$h:$i";
        }

        return $tanggal;
    }

    public  function formatFullTime($input) {
        if (empty($input)) {
            $tanggal = "-"; 
        } else {
            sscanf($input, '%d:%d:%d', $h, $i, $s);
            
            $h = tanggal::normalDigit($h);
            $i = tanggal::normalDigit($i);
            $s = tanggal::normalDigit($s);
            
            //$tanggal = $h.":".$i."";
            $tanggal = "$h:$i:$s";
        }

        return $tanggal;
    }

    public  function formatDateTimeToTime($input) {
        //print_r($input);die;
        if (empty($input)) {
            $time = "-"; 
        } else {
            sscanf($input, '%d-%d-%d %d:%d', $y, $m, $d, $h, $i);
            
            $h = tanggal::normalDigit($h);
            $i = tanggal::normalDigit($i);
            
            $time = "$h:$i";
        }

        return $time;
    }

    public function fieldDate($input) {
        sscanf($input, '%d-%d-%d', $y, $m, $d);
        $tanggal = $d . "-" . $m . "-" . $y;
        return $tanggal;
    }

    public  function sqlDate($input) {
        sscanf($input, '%d-%d-%d', $d, $m, $y);
        $tanggal = $y . "-" . $m . "-" . $d;
        return $tanggal;
    }

    public  function sqlDateForm($input) {
        sscanf($input, '%d/%d/%d', $m, $d, $y);
        $tanggal = $y . "-" . $m . "-" . $d;
        return $tanggal;
    }

    public  function sqlDateFormStrip($input) {
        sscanf($input, '%d-%d-%d', $m, $d, $y);
        $tanggal = $y . "-" . $m . "-" . $d;
        return $tanggal;
    }

    public  function sqlDateMdy($input) {
        sscanf($input, '%d-%d-%d', $m, $d, $y);
        $tanggal = $y . "-" . $m . "-" . $d;
        return $tanggal;
    }

    public  function sqlDateDot($input) {
        sscanf($input, '%d/%d/%d', $d, $m, $y);
        $tanggal = $y . "-" . $m . "-" . $d;
        return $tanggal;
    }

    public  function sqlDateTime($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d/%d/%d %d:%d:%d', $m, $d, $y, $h, $i, $s);
            
            $h = tanggal::normalDigit($h);
            $i = tanggal::normalDigit($i);
            $s = tanggal::normalDigit($s);
            
            $tanggal = $y . "-" . $m . "-" . $d. " ".$h.":".$i.":".$s."";
        }

        return $tanggal;
    }

    public  function sqlDateTimeToDate($input) {
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d %d:%d:%d', $y, $m, $d, $h, $i, $s);
                        
            $tanggal = $y . "-" . $m . "-" . $d;
        }

        return $tanggal;
    }
    
    public  function tgl_indo($tgl) {
        $tanggal = substr($tgl, 8, 2);
        $bulan = tanggal::getBln(substr($tgl, 5, 2));
        $tahun = substr($tgl, 0, 4);
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }

    public function selisih($date, $selisih){
        $tanggal = date('Y-m-d', strtotime(''.$selisih.' days', strtotime( $date )));
        return $tanggal;
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        // $d = DateTime::createFromFormat($format, $date);
        // // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        // return $d && $d->format($format) === $date;

        $tempDate = explode('-', $date);
        // checkdate(month, day, year)
        return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
        
    }

    public function selisih_waktu($time, $selisih){
        if(function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d').' '.$time); //print_r($date);die;
        date_add($date, date_interval_create_from_date_string(''.$selisih.' hours'));
        $format = date_format($date, 'Y-m-d H:i');
        $time_format = $this->formatDateTimeToTime( $format );
        return $time_format;
    }

    public function sqlDatetoDateTime($input)
    {
        # code...
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d-%d-%d', $y, $m, $d);
            
            $hour = date('h:i:s');
                        
            $tanggal = $y . "-" . $m . "-" . $d. " ".$hour."";
        }

        return $tanggal;
    }

    public function sqlDateFormtoDateTime($input)
    {
        # code...
        if (empty($input)) {
            $tanggal = "-";
        } else {
            sscanf($input, '%d/%d/%d', $m, $d, $y);
            
            $hour = date('h:i:s');
                        
            $tanggal = $y . "-" . $m . "-" . $d. " ".$hour."";
        }

        return $tanggal;
    }
    

    public  function getBulan($bln) {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }

    public  function getBulanRomawi($bln) {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public  function getBln($bln) {
        switch ($bln) {
            case 1:
                return "Jan";
                break;
            case 2:
                return "Feb";
                break;
            case 3:
                return "Mar";
                break;
            case 4:
                return "Apr";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Jun";
                break;
            case 7:
                return "Jul";
                break;
            case 8:
                return "Agust";
                break;
            case 9:
                return "Sept";
                break;
            case 10:
                return "Okt";
                break;
            case 11:
                return "Nov";
                break;
            case 12:
                return "Des";
                break;
        }        
}

   public  function normalDigit($d) {
        switch ($d) {
            case 0:
                return "00";
                break;
            case 1:
                return "01";
                break;
            case 2:
                return "02";
                break;
            case 3:
                return "03";
                break;
            case 4:
                return "04";
                break;
            case 5:
                return "05";
                break;
            case 6:
                return "06";
                break;
            case 7:
                return "07";
                break;
            case 8:
                return "08";
                break;
            case 9:
                return "09";
                break;
            default :
                return $d;
        }
        
    }
    
    
    public  function getHari($h) {
        switch ($h) {
            case 'Mon':
                return "Senin";
                break;
            
            case 'Tue':
                return "Selasa";
                break;
            
            case 'Wed':
                return "Rabu";
                break;
            
            case 'Thu':
                return "Kamis";
                break;
            
            case 'Fri':
                return "Jumat";
                break;
            
            case 'Sat':
                return "Sabtu";
                break;
            
            case 'Sun':
                return "Minggu";
                break;
            
           default :
                return $h;
        }
    }

    public  function getHariTranslate($h) {
        switch ($h) {
            case 'Senin':
                return "Monday";
                break;
            
            case 'Selasa':
                return "Tuesday";
                break;
            
            case 'Rabu':
                return "Wednesday";
                break;
            
            case 'Kamis':
                return "Thursday";
                break;
            
            case 'Jumat':
                return "Friday";
                break;
            
            case 'Sabtu':
                return "Saturday";
                break;
            
            case 'Minggu':
                return "Sunday";
                break;
            
           default :
                return $h;
        }
    }

    public  function getDayByNum($num) {
        switch ($num) {
            case '1':
                return "Senin";
                break;
            
            case '2':
                return "Selasa";
                break;
            
            case '3':
                return "Rabu";
                break;
            
            case '4':
                return "Kamis";
                break;
            
            case '5':
                return "Jumat";
                break;
            
            case '6':
                return "Sabtu";
                break;
            
            case '7':
                return "Minggu";
                break;
            
           default :
                return $num;
        }
    }
    
    /*function umur($tgl_lahir,$delimiter='-') {
    
        list($hari,$bulan,$tahun) = explode($delimiter, $tgl_lahir);
        
        $selisih_hari = date('d') - $hari;
        $selisih_bulan = date('m') - $bulan;
        $selisih_tahun = date('Y') - $tahun;
        
        if ($selisih_hari < 0 || $selisih_bulan < 0) {
            $selisih_tahun--;
        }
        
        return $selisih_tahun;
    }*/

    function UmurV2($tgl_lhr,$kondisi=0){

        $interval = date_diff(date_create(), date_create($tgl_lhr));
        $val_thn=$interval->format("%Y");
        if(trim($kondisi)==0){
            if($val_thn >=1){ //>=1 thn tampil tahun & bulan
                $hasil=$interval->format("%Y<sup>th</sup>, %M<sup>bl</sup>");
            } else { //< 1thn tampil bulan & hari
                $hasil=$interval->format("%M<sup>bl</sup>, %d<sup>hr</sup>");
            }
        } else {
            $hasil=$interval->format("You are  %Y Year, %M Months, %d Days, %H Hours, %i Minutes, %s Seconds Old");
        }

    return $hasil;
    }


    function AgeWithYearMonthDay($tgl_lahir) {
        
        $formatDate = $this->formatDateForm($tgl_lahir);
        $hours_in_day   = 24;
        $minutes_in_hour= 60;
        $seconds_in_mins= 60;

        $birth_date     = new DateTime($formatDate);
        $current_date   = new DateTime();

        $diff           = $birth_date->diff($current_date);

        $mth = ($diff->y * 12) + $diff->m;

        $years     = $diff->y . " <sup>th</sup> " . $diff->m . " <sup>bln</sup> ";
        $months    = ($diff->y * 12) + $diff->m . " months " . $diff->d . " day(s)";
        $weeks     = floor($diff->days/7) . " weeks " . $diff->d%7 . " day(s)";
        $days      = $diff->days;
        $hours     = $diff->h + ($diff->days * $hours_in_day) . " hours";
        $mins      = $diff->h + ($diff->days * $hours_in_day * $minutes_in_hour) . " minutest";
        $seconds   = $diff->h + ($diff->days * $hours_in_day * $minutes_in_hour * $seconds_in_mins) . " seconds";

        if($diff->y > 1){
            $age = $diff->y . " <sup>th</sup> " . $diff->m . " <sup>bln</sup> " . $diff->d . " <sup>hr</sup>";;
        }elseif ($mth > 1) {
            $age = ($diff->y * 12) + $diff->m . " <sup>bln</sup> " . $diff->d . " <sup>hr</sup>";
        }elseif ( $days > 1) {
            $age = floor($diff->days/7) . " <sup>mgu</sup> " . $diff->d%7 . " <sup>hr</sup> ";
        }else{
            $age = $diff->days . "<sup>hr</sup>";
        }

        return $age;

    }

    function AgeWithYearMonth($tgl_lahir) {
        
        $formatDate = $this->formatDateForm($tgl_lahir);
        $hours_in_day   = 24;
        $minutes_in_hour= 60;
        $seconds_in_mins= 60;

        $birth_date     = new DateTime($formatDate);
        $current_date   = new DateTime();

        $diff           = $birth_date->diff($current_date);

        $mth = ($diff->y * 12) + $diff->m;

        $years     = $diff->y . " <sup>th</sup> " . $diff->m . " <sup>bln</sup> ";
        $months    = ($diff->y * 12) + $diff->m . " months " . $diff->d . " day(s)";
        $weeks     = floor($diff->days/7) . " weeks " . $diff->d%7 . " day(s)";
        $days      = $diff->days;
        $hours     = $diff->h + ($diff->days * $hours_in_day) . " hours";
        $mins      = $diff->h + ($diff->days * $hours_in_day * $minutes_in_hour) . " minutest";
        $seconds   = $diff->h + ($diff->days * $hours_in_day * $minutes_in_hour * $seconds_in_mins) . " seconds";

        if($diff->y > 1){
            $age = $diff->y . " <sup>th</sup> " . $diff->m . " <sup>bln</sup> ";
        }elseif ($mth > 1) {
            $age = ($diff->y * 12) + $diff->m . " <sup>bln</sup> ";
        }

        return $age;

    }


}

?>

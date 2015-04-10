<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once('generate_csv.php');

$univ_id = @$_GET['univ_id'];
$user_id = @$_GET['user_id'];
$year    = @$_GET['y'];
$month   = @$_GET['m'];
$room    = @$_GET['room_id'];
$teacher = @$_GET['teacher_id'];
if (!$univ_id || !$user_id || !$year || !$room || !$teacher || !isset($month)) {
    header("location: ./?e");
    exit();
}

$tmp_dir = './tmp/';

if ($month != 0) {
    $days = $_GET['day'];
    $csv = generate_csv($univ_id, $user_id, $year, $month, $room, substr($room, 0, 3), $teacher, $days);
    $filename = "demand_{$year}_{$month}_{$user_id}.CSV";
    header('Cache-Control: public');
    header('Pragma: public');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'. $filename); 
    echo $csv;
} else {
    // hoge_2014
    $dir_name = "demand_{$univ_id}_{$year}";
    // ./tmp/hoge_2014
    $tmp_zip_dir = $tmp_dir . $dir_name . "/";
    @mkdir($tmp_zip_dir);
    // zip module
    $zip = new ZipArchive();
    $zipname = $dir_name . '.zip';
    $zip_path = $tmp_zip_dir . $zipname;
    $filenames = array();
    if (!file_exists($zip_path)) {
        if ($zip->open($zip_path, ZipArchive::CREATE) !==TRUE) {
            die('cannot open zipobj');
        }
        $filenames = array();
        $start = 1;
        if ($year == date('Y')) {
            $start = date('m');
        }
        foreach (range($start, 12) as $m) {
            $csv = generate_csv($univ_id, $user_id, $year, $m, $room, substr($room, 0, 3), $teacher);
            $filename = "{$dir_name}/demand_{$year}_{$m}_{$user_id}.CSV";
            $tmpfilename = $tmp_zip_dir . $m . '.CSV';
            $filenames[] = $tmpfilename;
            file_put_contents($tmpfilename, $csv);
            $zip->addFile($tmpfilename, $filename);
            //    $zip->addFile($tmpfilename);
            //    unlink($tmpfilename);
        }
        $zip->close();
        //foreach ($filenames as $f) {
        //    unlink($f);
        //}
    }
    header('Pragma: public');
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $zipname);
    readfile($zip_path);
}
//@rmdir($tmp_zip_dir);

<?php

function getAllConfigFiles() {
    $validFiles = array();
    $confFiles = scandir('.');
    foreach ($confFiles as $confFile) {
        if(substr($confFile, -4, 5) == 'conf') {
            array_push($validFiles, $confFile);
        }
    }
    return $validFiles;
}

class UI{
    static function TableHeading(){
        /*echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Type</th>';
        echo '<th>Name</th>';
        echo '<th>Value</th>';
        echo '</tr>';
        echo '</thead>';*/
        echo '
        <table class="u-full-width">
            <thead>
                <tr>
                    <th>Type</th> <th>Name</th> <th>Value</th> <th>&nbsp;</th>
                </tr>
            </thead>
        ';
    }
    static function TableRow($type, $name, $value){
        echo '<tr>';
        echo '<td>'.$type.'</td>';
        echo '<td>'.$name.'</td>';
        echo '<td>'.$value.'</td>';
        echo '<td>
                <button class="button-primary"></button>
                <button class="button-primary"></button>
        </td>';
        echo '<tr>';
    }
}

class utils{
    static function CheckIP($ip){
        $probIPtype = null;
        $test = array();
        if(count(explode('.', $ip))==4) $probIPtype=4;
        if(count(explode(':', $ip))==8) $probIPtype=6;
        if($probIPtype==4) {
            foreach(explode('.', $ip) as $v) {
                if($v >= 0 && $v <= 255) array_push($test, "ok");
            }
        }
        if($probIPtype==null) return 0;
        if($probIPtype==4 && count($test)==4) return 4;

    }

}

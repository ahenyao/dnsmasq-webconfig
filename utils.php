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
                <button style="width: inherit;" class="button-primary">Edit </button>
                <button style="width: inherit;" class="button-primary">Delete </button>
        </td>';
        echo '<tr>';
    }
}

class utils{
    public static function CheckIP($ip) {
        //$ip = "c9eb:4b45:f5ff:ebd9:3736:8aef:60d4:f643";
        $probIPtype = null;
        $test = array();
        if (count(explode('.', $ip)) == 4) $probIPtype = 4;
        if (count(explode(':', $ip)) == 8) $probIPtype = 6;
        if ($probIPtype == 4) {
            foreach (explode('.', $ip) as $v) {
                if ($v >= 0 && $v <= 255) array_push($test, "ok");
            }
        }
        if ($probIPtype == 6) {
            foreach (explode(':', $ip) as $v) {
                if (strlen($v) > 4) {
                    break;
                }
                if (preg_replace("/[0-9a-fA-F]/", "", $v) == "") array_push($test, "ok");
            }
        }
        if ($probIPtype == null) return "-1";
        if ($probIPtype == 4 && count($test) == 4) return "4";
        if ($probIPtype == 6 && count($test) == 8) return "6";
        return -1;
    }
}

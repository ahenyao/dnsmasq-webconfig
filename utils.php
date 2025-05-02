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
    static $counter=1;
    static function TableHeading(){
        echo '
            <tr>
                <th style="width: 5em">Type</th> <th>Name</th> <th>Value</th> <th style="width: min-content">&nbsp;</th>
            </tr>
        ';
    }
    static function TableRow($type, $name, $value){
        $id = 'dns-record'.self::$counter;
        echo '<tr class="dns-record" id="dns-record'.self::$counter.'">';
        echo '<td id="'.$id.'A">'.$type.'</td>';
        echo '<td id="'.$id.'B">'.$name.'</td>';
        echo '<td id="'.$id.'C">'.$value.'</td>';
        echo '<td id="'.$id.'D">
                <button style="width: 0; padding:0 1em; font-size: 2rem" class="button-primary">A</button>
                <button style="width: 0; padding:0 1em; font-size: 2rem" class="button-primary deleteButton" onclick="toggleDelete(\'' . $id . '\')">A</button>
        </td>';
        echo '<tr>';
        self::$counter++;
    }

    static function ScanFile($fileName){
        $file = explode(PHP_EOL, file_get_contents($fileName));
        foreach ($file as $line) {
            $line = trim($line);

            if(substr($line, 0, 9) == 'address=/') {
                $entry = explode("/", $line);
                $ipType = utils::CheckIP($entry[2]);
                if($ipType == -1) $type="X";
                if($ipType == 4) $type="A";
                if($ipType == 6) $type="AAAA";
                UI::TableRow($type, $entry[1], $entry[2]);
            }

            if(substr($line, 0, 6) == 'cname=') {
                $entry = explode(",", substr($line, 6));
                UI::TableRow("CNAME", $entry[0], $entry[1]);
            }
            if(substr($line, 0, 8) == 'mx-host=') {
                $entry = explode(",", substr($line, 8));
                UI::TableRow("MX", $entry[0], $entry[1]."&emsp;".$entry[2]);
            }
            if(substr($line, 0, 11) == 'txt-record=') {
                $entry = explode(",", substr($line, 11));
                $record = substr(join(",", $entry), strlen($entry[0])+1);
                $record = '<span class="tableRecord" title='.$record.'>'.$record.'</span>';
                UI::TableRow("TXT", $entry[0], $record);
            }
            if(substr($line, 0, 9) == 'srv-host=') {
                $entry = explode(",", substr($line, 9));
                $record = substr(join("&emsp;", $entry), strlen($entry[0]));
                $record = '<span class="tableRecord" title='.$record.'>'.$record.'</span>';
                UI::TableRow("SRV", $entry[0], $record);
            }
        }
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

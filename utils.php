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
            <tbody><tr id="table-header">
                <th style="width: 5em">Type</th> <th>Name</th> <th>Value</th> <th style="width: min-content">&nbsp;</th>
            </tr></tbody>
        ';
    }
    static function TableRow($type, $name, $value){
        $id = 'dns-record'.self::$counter;
        echo '<tr class="dns-record" id="'.$id.'">';
        echo '<td id="'.$id.'A">'.$type.'</td>';
        echo '<td id="'.$id.'B">'.$name.'</td>';
        echo '<td id="'.$id.'C">'.$value.'</td>';
        echo '<td id="'.$id.'D">
                <button id="'.$id.'edit" style="width: min-content; padding:0 0.6em; font-size: 2rem" class="button-primary" onclick="openEditor(this)"><span class="icon">edit</span></button>
                <button id="'.$id.'delete" style="width: min-content; padding:0 0.6em; font-size: 2rem" class="button-primary deleteButton" onclick="toggleDelete(\'' . $id . '\')"><span class="icon">delete</span></button>
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
                $record = '<span class="tableRecord" title='.$entry[2].'>'.$entry[2].'</span>';
                $name = '<span class="tableRecord" title='.$entry[1].'>'.$entry[1].'</span>';
                UI::TableRow($type, $name, $record);
            }

            if(substr($line, 0, 6) == 'cname=') {
                $entry = explode(",", substr($line, 6));
                $record = $entry[1];
                $record = '<span class="tableRecord" title='.$record.'>'.$record.'</span>';
                $name = '<span class="tableRecord" title='.$entry[0].'>'.$entry[0].'</span>';
                UI::TableRow("CNAME", $name, $record);
            }
            if(substr($line, 0, 8) == 'mx-host=') {
                $entry = explode(",", substr($line, 8));
                $record = $entry[1]."&emsp;".$entry[2];
                $record = '<span class="tableRecord" title='.$record.'>'.$record.'</span>';
                $name = '<span class="tableRecord" title='.$entry[0].'>'.$entry[0].'</span>';
                UI::TableRow("MX", $name, $record);
            }
            if(substr($line, 0, 11) == 'txt-record=') {
                $entry = explode(",", substr($line, 11));
                $record = substr(join(",", $entry), strlen($entry[0])+1);
                $record = '<span class="tableRecord" title='.$record.'>'.$record.'</span>';
                $name = '<span class="tableRecord" title='.$entry[0].'>'.$entry[0].'</span>';
                UI::TableRow("TXT", $name, $record);
            }
            if(substr($line, 0, 9) == 'srv-host=') {
                $entry = explode(",", substr($line, 9));
                $record = substr(join("&emsp;", $entry), strlen($entry[0])+6);
                $record = '<span class="tableRecord" title='.$record.'>'.$record.'</span>';
                $name = '<span class="tableRecord" title='.$entry[0].'>'.$entry[0].'</span>';
                UI::TableRow("SRV", $name, $record);
            }
        }
    }
}

class utils{
    public static function CheckIP($ip) {
        $ip = trim($ip);
        $probIPtype = null;
        $test = array();
        if (count(explode('.', $ip)) == 4) $probIPtype = 4;
        if (count(explode(':', $ip)) == 8) $probIPtype = 6;
        if ($probIPtype == 4) {
            foreach (explode('.', $ip) as $v) {
                if ($v >= 0 && $v <= 255) {
                    if(preg_replace("/[0-9]/", "", $v) == "") {
                        array_push($test, "ok");
                    }
                }
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

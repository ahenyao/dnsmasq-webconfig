<?php $title=""; require('header.php'); ?>
<h1><?= $title; ?></h1>

<?php

if(empty($_POST["data"])) {
    header("Location: ..");
    exit;
}

$data = json_decode(base64_decode($_POST["data"]));

$A = array();
$AAAA = array();
$CNAME = array();
$MX = array();
$TXT = array();
$SRV = array();

$currentType="";

foreach ($data as $line) {
    //echo $line; echo "<br>";

    if(substr($line,0,5)=="[=== " && substr($line, strlen($line) - 19)==" RECORDS START ===]"){
        $currentType=str_replace("[=== ","",str_replace(" RECORDS START ===]","",$line));
        continue;
    }

    if(substr($line,0,5)=="[=== " && substr($line, strlen($line) - 17)==" RECORDS END ===]"){
        $currentType = "";
        continue;
    }

    if($currentType != "") {
        if($currentType == "A")     array_push($A, $line);
        if($currentType == "AAAA")  array_push($AAAA, $line);
        if($currentType == "CNAME") array_push($CNAME, $line);
        if($currentType == "MX")    array_push($MX, $line);
        if($currentType == "TXT")   array_push($TXT, $line);
        if($currentType == "SRV")   array_push($SRV, $line);
    }
}

$configs = [$A, $AAAA, $CNAME, $MX, $SRV, $TXT];

if(d){
    foreach ($configs as $arr) {
        print_r($arr);
        echo "<br>";
    }

    foreach ($configs as $arr) {
        foreach ($arr as $line) {
            echo $line;
            echo "<br>";
        }
    }
}
function backupConfig($maxRetryCount, $array=null){
    $backupDir = "backups/backup-".date("Y-m-d_H-i-s");
    mkdir($backupDir);
    $succ = array();
    foreach(getAllConfigFiles() as $file) {
        if(!copy($file, $backupDir.'/'.$file)) array_push($succ, $file);
    }
    if (!empty($succ) && !($maxRetryCount <=1)) { backupConfig($maxRetryCount-1, $succ); }
    return $succ;
}

if(empty(backupConfig(5))){
    if(d) echo "Backup was successful. Writing config now.";
    for($i=0; $i<count($configs); $i++){
        file_put_contents(dnsFileNames[$i].'.tmp', implode(PHP_EOL, $configs[$i]));
    }
    foreach(dnsFileNames as $file) {
        rename($file.'.tmp', $file);
    }
}

for($br=0; $br<5;$br++) echo "<br>";

$out = shell_exec('dnsmasq --test 2>&1');
if(trim($out)=='dnsmasq: syntax check OK.'){

    $osname = shell_exec('cat /etc/os-release | grep -E "^NAME="');
    $osname = substr($osname, 6);
    $osname = substr($osname, 0, strlen($osname) - 2);
    $osname = strtolower($osname);

    if($osname == "openwrt") {
        echo "<pre>" . shell_exec('/etc/init.d/dnsmasq restart 2>&1') . "</pre>";
    } else {
        echo "<pre>" . shell_exec('sudo systemctl restart dnsmasq 2>&1') . "</pre>";
    }
    header("Location: ..");
    exit;
} else {
    echo "<pre><code>".$out."</code></pre>";
    echo "Fix all errors!<br>Dnsmasq won't restart to apply new config!<br>Use browser's back button to go back to editor and then reload";
}

?>

<?php require('footer.php'); ?>
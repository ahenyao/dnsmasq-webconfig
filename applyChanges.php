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

print_r($A);
echo "<br>";
print_r($AAAA);
echo "<br>";
print_r($CNAME);
echo "<br>";
print_r($MX);
echo "<br>";
print_r($TXT);
echo "<br>";
print_r($SRV);
echo "<br>";echo "<br>";

$configs = [$A, $AAAA, $CNAME, $MX, $SRV, $TXT];

foreach ($configs as $arr) {
    foreach ($arr as $line) {
        echo $line;
        echo "<br>";
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
    echo "Backup was successful. Writing config now.";
    for($i=0; $i<count($configs); $i++){
        file_put_contents(dnsFileNames[$i].'.tmp', implode(PHP_EOL, $configs[$i]));
    }
    foreach(dnsFileNames as $file) {
        rename($file.'.tmp', $file);
    }
}

header("Location: ..");
exit;

?>

<?php require('footer.php'); ?>
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>

<?php
define("dnsFileNames", ["a.conf",  "aaaa.conf",  "cname.conf", "mx.conf",  "srv.conf",  "txt.conf"]);
define("confFileNames", array_merge(dnsFileNames, ["dhcp.conf", "pxe.conf", "extra.conf"]));

    if(chdir('/etc/dnsmasq.webconfig')){
        if(!is_dir("backups")) mkdir("backups");
        foreach(confFileNames as $confFileName) {
            if (!file_exists($confFileName)) {
                touch($confFileName);
            }
        }
    }
?>

<?php require_once 'utils.php'; ?>

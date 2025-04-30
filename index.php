<?php $title=""; require('header.php'); ?>
<h1><?= $title; ?></h1>

<button class="button-primary theme-switch" id="themeSwitcher"></button>

    <div class="container">
        <div class="row">
            <div class="one-half column" style="margin-top: 25%">
                <h4>Basic Page</h4>
                <p>This index.html page is a placeholder with the CSS, font and favicon. It's just waiting for you to add some content! If you need some help hit up the <a href="http://www.getskeleton.com">Skeleton documentation</a>.</p>
            </div>
            <div class="twelve column">
                <h5>A      address=/yourdomain.com/192.0.2.1</h5>
                <h5>AAAA   address=/yourdomain.com/2001:db8::1</h5>
                <h5>CNAME  cname=alias.yourdomain.com,target.yourdomain.com</h5>
                <h5>MX     mx-host=yourdomain.com,mail.yourdomain.com,10</h5>
                <h5>TXT    txt-record=yourdomain.com,"v=spf1 include:_spf.google.com ~all"</h5>
                <h5>SRV    srv-host=_sip._tcp.yourdomain.com,sipserver.yourdomain.com,5060,10,60</h5>
                <h6>sudo -u http sh -c "touch /etc/dnsmasq.webconf/{a,aaaa,cname,mx,txt,srv,extra}.conf"</h6>
            </div>
        </div>
        <div class="row">
            <details>
                <summary>Show all config files</summary>
            <div class="twelve column">
                <?php
                foreach (getAllConfigFiles() as $confFile) {
                    echo '<h1>' . $confFile . '</h1>';
                    $content = file_get_contents($confFile);
                    echo '<pre>';
                    echo empty($content) ? 'No content' : $content;
                    echo '</pre>';
                }
                ?>
            </div>
            </details>
        </div>

        <div class="row">
            <?php UI::TableHeading(); ?>
            <?php
                $file = explode(PHP_EOL, file_get_contents('../dnsmasq.conf'));
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
                        UI::TableRow("MX", $entry[0], $entry[1]."&emsp;[".$entry[2]."]");
                    }
                    if(substr($line, 0, 11) == 'txt-record=') {
                        $entry = explode(",", substr($line, 11));
                        $record = substr(join(",", $entry), strlen($entry[0])+1);
                        $record = '<span class="tableRecord" title='.$record.'>'.$record.'</span>';
                        UI::TableRow("TXT", $entry[0], $record);
                    }
                    if(substr($line, 0, 9) == 'srv-host=') {
                        $entry = explode(",", substr($line, 9));
                        $record = join("&emsp;", $entry);
                        $record = '<span class="tableRecord" title='.$record.'>'.$record.'</span>';
                        UI::TableRow("SRV", $entry[0], $record);
                    }
                }
            ?>
        </div>


    </div>

<?php require('footer.php'); ?>
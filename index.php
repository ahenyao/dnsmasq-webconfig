<?php $title=""; require('header.php'); ?>

<button class="button-primary theme-switch" id="themeSwitcher"></button>

    <div class="container">
        <div class="row">
            <div class="one-half column" style="margin-top: 25%">
                <h4>Basic Page</h4>
                <p>This index.html page is a placeholder with the CSS, font and favicon. It's just waiting for you to add some content! If you need some help hit up the <a href="http://www.getskeleton.com">Skeleton documentation</a>.</p>
            </div>
            <div class="twelve column">
                <pre>
                    <code>
                A      address=/yourdomain.com/192.0.2.1
                AAAA   address=/yourdomain.com/2001:db8::1
                CNAME  cname=alias.yourdomain.com,target.yourdomain.com
                MX     mx-host=yourdomain.com,mail.yourdomain.com,10
                TXT    txt-record=yourdomain.com,"v=spf1 include:_spf.google.com ~all"
                SRV    srv-host=_sip._tcp.yourdomain.com,sipserver.yourdomain.com,5060,10,60
                sudo -u http sh -c "touch /etc/dnsmasq.webconf/{a,aaaa,cname,mx,txt,srv,extra}.conf"
                    </code>
                </pre>
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
            <button style="font-size: 2rem" class="button-primary u-pull-right" onclick="saveConfig();">Save changes</button>
            <table style="table-layout:fixed;" class="u-full-width">
            <?php UI::TableHeading(); ?>
            <?php foreach (getAllConfigFiles() as $confFile) UI::ScanFile($confFile); ?>
            <?php UI::TableHeading(); ?>
            </table>

        </div>


    </div>
<dialog id="editor">
    <h3>Editing DNS record</h3>
    <form method="dialog">
        <select name="record" id="record">
            <option value="A">A</option>
            <option value="AAAA">AAAA</option>
            <option value="CNAME">CNAME</option>
            <option value="MX">MX</option>
            <option value="TXT">TXT</option>
            <option value="SRV">SRV</option>
        </select>
        <script>
            document.getElementById("record").addEventListener("change", () => { alert('ok'); });
        </script>
    </form>
</dialog>
<?php require('footer.php'); ?>
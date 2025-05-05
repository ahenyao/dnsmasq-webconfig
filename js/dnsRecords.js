function saveConfig(){
    if(confirm("Are you sure you want to save changes?")){
        genConf();
    }
}

function genConf(meow) {
    var A = [];
    var AAAA = [];
    var CNAME = [];
    var MX = [];
    var TXT = [];
    var SRV = [];

    const records = ["A", "AAAA", "CNAME", "MX", "TXT", "SRV"];
    const dnsRecords = document.getElementsByClassName("dns-record");
    records.forEach((record) => {
        for (var i = 1; i<dnsRecords.length+1; i++) {
            var type = document.getElementById(`dns-record${i}A`);
            var name = document.getElementById(`dns-record${i}B`);
            var value = document.getElementById(`dns-record${i}C`);
            var content = null;
            if (!dnsRecords[i - 1].classList.contains("deletion")) {
                switch (record) {
                    case 'A':
                        if (type.textContent === 'A') {
                            content = `address=/${name.innerText}/${value.innerText}`;
                            A.push(content);
                        }
                        break;
                    case 'AAAA':
                        if (type.textContent === 'AAAA') {
                            content = `address=/${name.innerText}/${value.innerText}`;
                            AAAA.push(content);
                        }
                        break;
                    case 'CNAME':
                        if (type.textContent === 'CNAME') {
                            content = `cname=${name.innerText},${value.innerText}`;
                            CNAME.push(content);
                        }
                        break;
                    case 'MX':
                        if (type.textContent === 'MX') {
                            content = `mx-host=${name.innerText},${value.innerText.replaceAll(" ", ",")}`;
                            MX.push(content);
                        }
                        break;
                    case 'TXT':
                        if (type.textContent === 'TXT') {
                            content = `txt-record=${name.innerText},${value.innerText}`;
                            TXT.push(content);
                        }
                        break;
                    case 'SRV':
                        if (type.textContent === 'SRV') {
                            content = `srv-host=${name.innerText},${value.innerText.replaceAll(" ", ",")}`;
                            SRV.push(content);
                        }
                        break;
                }
            }
        }
    });
    var result = [];

    records.forEach((record) => {
        result.push(`[=== ${record} RECORDS START ===]`);
        var list;
        if(record==='A') list = A;
        if(record==='AAAA') list = AAAA;
        if(record==='CNAME') list = CNAME;
        if(record==='MX') list = MX;
        if(record==='TXT') list = TXT;
        if(record==='SRV') list = SRV;
        result = result.concat(list);
        result.push(`[=== ${record} RECORDS END ===]`);
    });
    var data = JSON.stringify(result);
    document.body.innerHTML+=`<form id="applyChanges" action="applyChanges.php" method="POST">
    <input type="hidden" value="${btoa(data)}" name="data">
    </form>`;
    document.forms["applyChanges"].submit();
}

function toggleDelete(record){
    record = document.getElementById(record);
    if(record.classList.contains("deletion")){
        record.classList.remove("deletion")
    } else {
        record.classList.add("deletion")
    }
}

// 1:1 rewrite from utils.php/UI::CheckIP
function CheckIP(ip) {
    var probIPtype = null;
    var test = [];
    if( ip.split(".").length === 4 ) probIPtype = 4;
    if( ip.split(":").length === 8 ) probIPtype = 6;
    if(probIPtype===4){
        for(var v of  ip.split(".")){
            if(v >= 0 && v<= 255) test.push("ok");
        }
    }
    if(probIPtype===6){
        for(var v of  ip.split(":")){
            if(v.length > 4) {
                break;
            }
            if(v.replace(/[0-9a-fA-F]/g, "") === "") test.push("ok");
        }
    }
    if(probIPtype === null) return "-1";
    if(probIPtype === 4 && test.length === 4) return "4";
    if(probIPtype === 6 && test.length === 8) return "6";
}
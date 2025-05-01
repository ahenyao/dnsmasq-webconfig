function genConf(record) {
    const dnsRecords = document.getElementsByClassName("dns-record");
    for (var i = 1; i<dnsRecords.length+1; i++) {
        var type = document.getElementById(`dns-record${i}A`);
        var name = document.getElementById(`dns-record${i}B`);
        var value = document.getElementById(`dns-record${i}C`);
        if(!dnsRecords[i-1].classList.contains("deletion")) {
            switch (record) {
                case 'A':
                    if (type.textContent === 'A') {
                        console.log(`address=/${name.innerText}/${value.innerText}`);
                    }
                    break;
                case 'AAAA':
                    if (type.textContent === 'AAAA') {
                        console.log(`address=/${name.innerText}/${value.innerText}`);
                    }
                    break;
                case 'CNAME':
                    if (type.textContent === 'CNAME') {
                        console.log(`CNAME=${name.innerText},${value.innerText}`);
                    }
                    break;
                case 'MX':
                    if (type.textContent === 'MX') {
                        console.log(`mx-host=${name.innerText},${value.innerText.replaceAll(" ", ",")}`);
                    }
                    break;
                case 'TXT':
                    if (type.textContent === 'TXT') {
                        console.log(`txt-record=${name.innerText},${value.innerText}`);
                    }
                    break;
                case 'SRV':
                    if (type.textContent === 'SRV') {
                        console.log(`srv-host=${name.innerText}${value.innerText.replaceAll(" ", ",")}`);
                    }
                    break;
            }
        }
    }
}

function toggleDelete(record){
    record = document.getElementById(record);
    if(record.classList.contains("deletion")){
        record.classList.remove("deletion")
    } else {
        record.classList.add("deletion")
    }
}
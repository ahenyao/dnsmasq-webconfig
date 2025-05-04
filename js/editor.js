const editForm = document.getElementById("editForm");
const edits = document.getElementById("editForm-args");
const editor = document.getElementById("editor");
const record = document.getElementById("record");

const closeEditor = document.getElementById("closeEditor");
const applyEditor = document.getElementById("applyEditor");

editor.addEventListener('cancel', (event) => { event.preventDefault(); });
record.addEventListener("change", () => { changeProperties(); });

closeEditor.addEventListener('click', () => { editor.close(); });
applyEditor.addEventListener('click', () => { if(editForm.reportValidity()) { editForm.submit(); editValues(dnsEntry, "w"); } });

changeProperties();

var dnsEntry;

function openEditor(dns, r){
    dnsEntry = dns;
    if(dns!==undefined && r===undefined) {
        r = document.getElementById(`${dns.parentElement.parentElement.id}A`).innerText;
    } else { dns = undefined; }
    record.removeAttribute('disabled');
    record.value=r;
    changeProperties();
    editValues(dns,"r");
    editor.showModal();
}
function changeProperties(){
    switch (record.value){
        case 'A':
            edits.innerHTML=`<label for="domain">Domain</label>
                <input type="text" id="domain" required>
    
                <label for="ip">IPv4 address</label>
                <input type="text" id="ip" required>`;
            break;
        case 'AAAA':
            edits.innerHTML=`<label for="domain">Domain</label>
                <input type="text" id="domain" required>
    
                <label for="ip">IPv6 address</label>
                <input type="text" id="ip" required>`;
            break;
        case 'CNAME':
            edits.innerHTML=`<label for="domain">Alias</label>
                <input type="text" id="domain" required>
    
                <label for="target">Target</label>
                <input type="text" id="target" required>`;
            break;

        case 'MX':
            edits.innerHTML=`<label for="domain">Domain</label>
                <input type="text" id="domain" required>
    
                <label for="target">Mail server</label>
                <input type="text" id="target" required>
    
                <label for="priority">Priority (0-65535)</label>
                <input type="number" id="priority" min="0" max="65535" required>`;
            break;
        case 'TXT':
            edits.innerHTML=`<label for="domain">Domain</label>
                <input type="text" id="domain" required>
    
                <label for="txtdata">Content</label>
                <input type="text" id="txtdata" required>`;
            break;
        case 'SRV':
            edits.innerHTML=`<label for="domain">Domain</label>
                <input type="text" id="domain" required>
    
                <label for="target">Target</label>
                <input type="text" id="target" required>
    
                <label for="port">Port (0-65535)</label>
                <input type="number" id="port" min="0" max="65535" required>
    
                <label for="priority">Priority (0-65535)</label>
                <input type="number" id="priority" min="0" max="65535" required>
    
                <label for="weight">Weight (0-65535)</label>
                <input type="number" id="weight" min="0" max="65535" required>`;
            break;
        default:
            edits.innerHTML=``;
            break;
    }
}

function editValues(dns, mode){
    if(dns===undefined) return;
    dns = dns.parentElement.parentElement.id;

    var domain = document.getElementById("domain");
    var ip = document.getElementById("ip");
    var target = document.getElementById("target");
    var priority = document.getElementById("priority");
    var txtdata = document.getElementById("txtdata");
    var port = document.getElementById("port");
    var weight = document.getElementById("weight");



    var _A = document.getElementById(`${dns}A`);
    var _B = document.getElementById(`${dns}B`);
    var _C = document.getElementById(`${dns}C`);
    var _D = document.getElementById(`${dns}D`);

    if(mode==="r") {
        var _args = _C.innerText.split(" ");
        record.setAttribute('disabled','');
        switch (_A.innerText) {
            case 'A':
                domain.value = _B.innerText;
                ip.value = _C.innerText;
                break;
            case 'AAAA':
                domain.value = _B.innerText;
                ip.value = _C.innerText;
                break;
            case 'CNAME':
                domain.value = _B.innerText;
                target.value = _C.innerText;
                break;
            case 'MX':
                domain.value = _B.innerText;
                target.value = _args[0];
                priority.value = _args[1];
                break;
            case 'TXT':
                domain.value = _B.innerText;
                txtdata.value = _C.innerText;
                break;
            case 'SRV':
                domain.value = _B.innerText;
                target.value = _args[0];
                port.value = _args[1];
                priority.value = _args[2];
                weight.value = _args[3];
                break;
            default:
                break;
        }
    }

    if(mode==="w"){
        switch (_A.innerText) {
            case 'A':
                _B.innerText = domain.value;
                _C.innerText = `${ip.value}`;
                break;
            case 'AAAA':
                _B.innerText = domain.value;
                _C.innerText = `${ip.value}`;
                break;
            case 'CNAME':
                _B.innerText = domain.value;
                _C.innerText = `${target.value}`;
                break;
            case 'MX':
                _B.innerText = domain.value;
                _C.innerText = `${target.value} ${priority.value}`;
                break;
            case 'TXT':
                _B.innerText = domain.value;
                _C.innerText = `${txtdata.value}`;
                break;
            case 'SRV':
                _B.innerText = domain.value;
                _C.innerText = `${target.value} ${port.value} ${priority.value} ${weight.value}`;
                break;
            default:
                break;
        }
    }

}
const themeSwitcher = document.getElementById("themeSwitcher");
let darkMode = localStorage.getItem('darkMode');
function enableDarkMode(){
    localStorage.setItem('darkMode', 'enabled');
    document.body.classList.add("darkTheme");
    themeSwitcher.innerHTML='<span class="icon">light_mode</span>';
}

function disableDarkMode(){
    localStorage.setItem('darkMode', 'disabled');
    document.body.classList.remove("darkTheme");
    themeSwitcher.innerHTML='<span class="icon">dark_mode</span>';
}

if(darkMode==="enabled")  enableDarkMode();
if(darkMode==="disabled") disableDarkMode();

themeSwitcher.addEventListener("click", () =>{
    darkMode !== "enabled" ? enableDarkMode() : disableDarkMode();
    darkMode = localStorage.getItem('darkMode');
});
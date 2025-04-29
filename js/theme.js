const themeSwitcher = document.getElementById("themeSwitcher");
let darkMode = localStorage.getItem('darkMode');
function enableDarkMode(){
    localStorage.setItem('darkMode', 'enabled');
    document.body.classList.add("darkTheme");
}

function disableDarkMode(){
    localStorage.setItem('darkMode', 'disabled');
    document.body.classList.remove("darkTheme");
}

if(darkMode==="enabled") enableDarkMode();

themeSwitcher.addEventListener("click", () =>{
    darkMode !== "enabled" ? enableDarkMode() : disableDarkMode();
    darkMode = localStorage.getItem('darkMode');
});
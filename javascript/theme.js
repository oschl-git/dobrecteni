let currentTheme = localStorage.getItem('theme');

//Check if there's any existence of theme in LocalStorage
if (localStorage.getItem('theme') == null) {
    localStorage.setItem('theme', 'light');
}

applyTheme();

function applyTheme() {
    document.body.className = '';
    document.body.classList.add(currentTheme);
}

function changeTheme(checkbox) {
    if (checkbox.checked) localStorage.setItem('theme', 'dark');
    else localStorage.setItem('theme', 'light');

    currentTheme = localStorage.getItem('theme');
    applyTheme();
}
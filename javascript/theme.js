//Loads theme from storage
const theme = localStorage.getItem('theme');

//Applies theme
document.body.classList.add(theme);


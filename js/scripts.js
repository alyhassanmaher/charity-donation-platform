document.addEventListener('submit', (event) => {
    const form = event.target;
    const username = form.username?.value.trim();
    const password = form.password?.value.trim();

    if (!username || !password) {
        alert('Please fill in both the username and password.');
        event.preventDefault();
    }
});
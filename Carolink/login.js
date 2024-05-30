document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);

    fetch('login.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Log the response for debugging
        if (data.success) {
            alert('Login successful!');
            window.location.href = data.redirect;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while logging in.');
    });
});

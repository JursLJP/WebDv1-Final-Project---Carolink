document.getElementById('signupForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('create_account.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});

document.getElementById('change-picture-button').addEventListener('click', function() {
    document.getElementById('profile-picture-input').click();
});

document.getElementById('profile-picture-input').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('profile_picture', file);

        fetch('update_profile_picture.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('profile-picture').src = data.file_path;
                alert('Profile picture updated successfully!');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while uploading the profile picture.');
        });
    }
});

document.getElementById('edit-button').addEventListener('click', function() {
    document.getElementById('user-name').classList.add('hidden');
    document.getElementById('name-inputs').classList.remove('hidden');
    document.getElementById('edit-button').classList.add('hidden');
    document.getElementById('save-button').classList.remove('hidden');
});

document.getElementById('save-button').addEventListener('click', function() {
    const firstName = document.getElementById('first-name-input').value;
    const lastName = document.getElementById('last-name-input').value;
    const password = document.getElementById('password-input').value;
    
    const formData = new FormData();
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('password', password);

    fetch('update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('user-name').textContent = firstName + ' ' + lastName;
            document.getElementById('user-name').classList.remove('hidden');
            document.getElementById('name-inputs').classList.add('hidden');
            document.getElementById('edit-button').classList.remove('hidden');
            document.getElementById('save-button').classList.add('hidden');
            alert('Profile updated successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the profile.');
    });
});

document.getElementById('logout-button').addEventListener('click', function() {
    window.location.href = 'logout.php';
});

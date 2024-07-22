document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const deleteButton = document.querySelector('button[type="button"]');
    
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        
        const formData = new FormData(form);
        const url = form.getAttribute('action');
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
    
    deleteButton.addEventListener('click', function () {
        const csrfToken = deleteButton.querySelector('input[name="_token"]').value;
        const url = deleteButton.getAttribute('data-url');
        
        fetch(url, {
            method: 'POST',
            body: JSON.stringify({ _token: csrfToken }),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

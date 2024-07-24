document.querySelectorAll('[data-modal-toggle]').forEach(button => {
    button.addEventListener('click', () => {
        const modalId = button.getAttribute('data-modal-target');
        document.getElementById(modalId).classList.remove('hidden');
    });
});

// Hide the modal
document.querySelectorAll('[data-modal-hide]').forEach(button => {
    button.addEventListener('click', () => {
        const modalId = button.getAttribute('data-modal-hide');
        document.getElementById(modalId).classList.add('hidden');
    });
});

function addComment(event) {
    event.preventDefault();

    const form = event.target;
    const menuId = form.getAttribute('data-menu-id');
    const formData = new FormData(form);
    const messageDiv = document.getElementById(`commentMessage${menuId}`);

    fetch(`{{ path('comment_add', {'menuId': '__MENU_ID__'}) }}`.replace('__MENU_ID__', menuId), {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update comments list
                const commentsList = document.getElementById(`commentsList${menuId}`);
                const newComment = document.createElement('li');
                newComment.id = `comment-${data.comment.id}`;
                newComment.innerHTML = `<strong>${data.comment.customerName} :</strong> ${data.comment.comment} <button class="btn btn-danger btn-sm mt-2" data-comment-id="${data.comment.id}" data-menu-id="${menuId}" onclick="removeComment(this)">Supprimer</button>`;
                commentsList.appendChild(newComment);

                // Reset the form and show success message
                form.reset();
                messageDiv.textContent = 'Commentaire ajouté avec succès !';
                messageDiv.classList.remove('text-red-500');
                messageDiv.classList.add('text-green-500');
                setTimeout(() => {
                    messageDiv.textContent = '';
                }, 3000);
            } else {
                messageDiv.textContent = data.message || 'Erreur lors de l\'ajout du commentaire.';
                messageDiv.classList.remove('text-green-500');
                messageDiv.classList.add('text-red-500');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.textContent = 'Erreur lors de l\'ajout du commentaire.';
            messageDiv.classList.remove('text-green-500');
            messageDiv.classList.add('text-red-500');
        });
}

// Function to handle comment removal via AJAX
function removeComment(button) {
    const commentId = button.getAttribute('data-comment-id');
    const menuId = button.getAttribute('data-menu-id');

    fetch(`{{ path('comment_remove', {'commentId': '__COMMENT_ID__'}) }}`.replace('__COMMENT_ID__', commentId), {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the comment from the list
                const commentElement = document.getElementById(`comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
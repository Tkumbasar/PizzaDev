
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.view-comments');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const menuId = this.getAttribute('data-menu-id');
            const commentsUrl = this.getAttribute('data-comments-url'); // Récupérer l'URL depuis l'attribut data-comments-url
            const modal = document.getElementById('commentsModal');

            // Requête AJAX pour charger les commentaires du menu
            fetch(commentsUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ menuId: menuId })
            })
            .then(response => response.text())
            .then(data => {
                modal.style.display = 'block'; // Afficher la modal
                document.getElementById('commentsContent').innerHTML = data; // Injecter le contenu dans la modal
            })
            .catch(error => console.error('Erreur lors du chargement des commentaires:', error));
        });
    });

    // Fermer la modal si l'utilisateur clique sur la croix
    const closeButtons = document.querySelectorAll('.close');
    
    closeButtons.forEach(closeButton => {
        closeButton.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
        });
    });
});

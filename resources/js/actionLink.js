    document.addEventListener("DOMContentLoaded", () => {
        
        let resizeTimeout;
        const setupDeleteButtons = () => {
            document.querySelectorAll(".delete-button").forEach(button => {
                button.addEventListener("click", () => {
                    const itemName = button.dataset.itemName;
                    const deleteRoute = button.dataset.deleteRoute;
                    const formDelete = document.getElementById("deleteForm");
                    const message = document.getElementById("textDeleteItem");
                    
                    if (formDelete) {
                        formDelete.setAttribute("action", deleteRoute);
                    }

                    if (message) {
                        message.textContent = `Êtes-vous sûr de vouloir supprimer "${itemName}" ?`;
                    }
                });
            });
        };

        // Exécuter la configuration des boutons de suppression au chargement
        setupDeleteButtons();

        // Événement resize avec debounce
        window.addEventListener("resize", () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                
                if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                    const dataTable = new simpleDatatables.DataTable("#search-table", {
                        searchable: true,
                        paging: true,
                        perPage: 15,
                        perPageSelect: [5, 10, 15, 20, 25, 50,100, 200, 300, 400, 500],
                        sortable: true
                    });
                }
                // Ici, vous pouvez ajouter d'autres logiques de redimensionnement si nécessaire
            }, 200);
        });
    });
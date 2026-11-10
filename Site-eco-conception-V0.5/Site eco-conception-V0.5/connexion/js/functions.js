/******************************
 * FONCTION envoie de données de formulaire
 */
function envoiFormulaire(form) {
    form.requestSubmit();
}

async function updateStock(button) {
    const stockCard = button.closest('.stock-card');
    const quantity = stockCard.querySelector('.stock-quantity');
    const status = stockCard.querySelector('.stock-update-status');
    const buttons = stockCard.querySelectorAll('[data-quantity-change]');

    buttons.forEach((stockButton) => {
        stockButton.disabled = true;
    });
    status.textContent = '';

    try {
        const response = await fetch('./php/modifierstock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                'Accept': 'application/json',
            },
            body: new URLSearchParams({
                id: stockCard.querySelector('[data-stock-id]').dataset.stockId,
                action: button.dataset.quantityChange === '1' ? 'plus' : 'moins',
                csrf_token: stockCard.querySelector('[data-stock-id]').dataset.csrfToken,
            }),
        });
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || 'Échec de la mise à jour du stock.');
        }

        quantity.textContent = String(result.stock);
    } catch (error) {
        status.textContent = error.message || 'Le stock n’a pas pu être enregistré. Réessayez.';
    } finally {
        const currentStock = Number(quantity.textContent);
        buttons.forEach((stockButton) => {
            stockButton.disabled = stockButton.dataset.quantityChange === '-1' && currentStock === 0;
        });
    }
}

document.addEventListener('click', function (event) {
    const quantityButton = event.target.closest('[data-quantity-change]');

    if (quantityButton) {
        void updateStock(quantityButton);
        return;
    }

    const editButton = event.target.closest('[data-edit-miel]');
    if (editButton) {
        document.querySelector('#editMielId').value = editButton.dataset.mielId;
        document.querySelector('#editMielType').value = editButton.dataset.mielType;
        document.querySelector('#editMielPhoto').value = editButton.dataset.mielPhoto;
        document.querySelector('#editMielDialog').showModal();
        return;
    }

    const deleteButton = event.target.closest('[data-delete-miel]');
    if (deleteButton) {
        document.querySelector('#deleteMielId').value = deleteButton.dataset.mielId;
        document.querySelector('#deleteMielName').textContent = deleteButton.dataset.mielType;
        document.querySelector('#deleteMielDialog').showModal();
        return;
    }

    const confirmEditButton = event.target.closest('[data-confirm-edit]');
    if (confirmEditButton) {
        const editForm = document.querySelector('#editMielForm');
        editForm.dataset.confirmed = 'true';
        document.querySelector('#confirmEditDialog').close();
        editForm.requestSubmit();
        return;
    }

    const closeButton = event.target.closest('[data-close-dialog]');
    if (closeButton) {
        closeButton.closest('dialog').close();
    }
});

document.addEventListener('submit', function (event) {
    if (event.target.id !== 'editMielForm' || event.target.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();
    document.querySelector('#confirmEditDialog').showModal();
});

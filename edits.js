function showEditFields(selectElement) {
    if (!selectElement.value) return;

    const item = JSON.parse(selectElement.value);
    
    // Hide selection UI, show Form UI
    document.getElementById('edit-selection-ui').style.display = 'none';
    const form = document.getElementById('actual-edit-form');
    form.style.display = 'block';

    // Populate Fields
    document.getElementById('edit-original-name').value = item.name;
    document.getElementById('edit-name').value = item.name;
    document.getElementById('edit-type').value = item.type;
    document.getElementById('edit-description').value = item.description;
    document.getElementById('edit-price').value = item.price;
    document.getElementById('edit-ingredients').value = item.ingredients.join(', ');

    // Handle Allergens Checkboxes
    const checkBoxes = document.querySelectorAll('.allergen-check');
    checkBoxes.forEach(cb => {
        cb.checked = item.allergens.includes(cb.value);
    });
}

function resetEditModal() {
    document.getElementById('edit-selection-ui').style.display = 'block';
    document.getElementById('actual-edit-form').style.display = 'none';
    document.getElementById('item-to-edit').value = "";
}
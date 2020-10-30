
window.addEventListener('load', () => {
    
    const updateLabelField = (value) => {
        if (value === 'enabled') {
            document.querySelector('.give_dtd_label_field').classList.remove('give-hidden');
        } else {
            document.querySelector('.give_dtd_label_field').classList.add('give-hidden');
        }
    }

    // Query field used to enable/disable double the donation
    const enabledField = document.querySelector('.dtd_enable_disable_field');

    // On load, update label field based on enabled field value
    enabledField && updateLabelField(enabledField.querySelector('input:checked').value);

    // When the enabled field value changes, update label field based on enabled field value
    enabledField && enabledField.addEventListener('change', (event) => {
        updateLabelField(event.target.value);
    });

})
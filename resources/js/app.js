import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function updateReservationModal(dateStr) {
    let dtDate = new Date(dateStr);
    let containerReservationDate = document.getElementById('reservationDate');

    containerReservationDate.innerText = dtDate.toDateString();
}

window.updateReservationModal = updateReservationModal;

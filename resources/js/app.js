import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function updateReservationModal(locationId, reservationDate) {
    let dtReservationDate = new Date(reservationDate);    
    let token = document.querySelector('meta[name="csrf-token"]').content;
    let containerReservationDate = document.getElementById('reservationDate');
    let containerReservationList = document.getElementById('reservationList');

    containerReservationDate.innerText = '';
    containerReservationList.innerText = '';

    const xhttp = new XMLHttpRequest();    
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            containerReservationDate.innerText = dtReservationDate.toDateString();
            containerReservationList.innerText = this.responseText;
        }
    };
    xhttp.open("POST", "/reservations/list");
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.setRequestHeader("X-CSRF-TOKEN", token);
    xhttp.send("location_id=" + locationId + "&reservation_date=" + reservationDate);
}

window.updateReservationModal = updateReservationModal;

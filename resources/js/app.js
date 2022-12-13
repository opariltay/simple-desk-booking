import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function updateReservationModal(locationId, reservationDate) {
    let dtReservationDate = new Date(reservationDate);    
    let token = document.querySelector('meta[name="csrf-token"]').content;
    let containerReservationDate = document.getElementById('reservationDate');
    let containerReservationList = document.getElementById('reservationList');
    let inputLocationId = document.getElementById('location_id');
    let inputReservationDate = document.getElementById('reservation_date');

    containerReservationDate.innerText = '';
    containerReservationList.innerHTML = '';
    inputLocationId.value = '';
    inputReservationDate.value = '';

    const xhttp = new XMLHttpRequest();    
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            containerReservationDate.innerText = dtReservationDate.toDateString();
            containerReservationList.innerHTML = this.responseText;
            inputLocationId.value = locationId;
            inputReservationDate.value = reservationDate;
        }
    };
    xhttp.open("POST", "/reservation/list");
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.setRequestHeader("X-CSRF-TOKEN", token);
    xhttp.send("location_id=" + locationId + "&reservation_date=" + reservationDate);
}

window.updateReservationModal = updateReservationModal;

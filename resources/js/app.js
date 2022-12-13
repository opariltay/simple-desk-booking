import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function updateReservationModal(locationId, reservationDate) {
    let dtReservationDate = new Date(reservationDate);    
    let token = document.querySelector('meta[name="csrf-token"]').content;
    let containerReservationDate = document.getElementById('reservationDate');
    let containerReservationList = document.getElementById('reservationList');
    let inputsLocationId = document.getElementsByName('location_id');
    let inputsReservationDate = document.getElementsByName('reservation_date');

    containerReservationDate.innerText = '';
    containerReservationList.innerHTML = '';
    updateElementsValue(inputsLocationId, '');
    updateElementsValue(inputsReservationDate, '');
    
    const xhttp = new XMLHttpRequest();    
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            containerReservationDate.innerText = dtReservationDate.toDateString();
            containerReservationList.innerHTML = this.responseText;
            updateElementsValue(inputsLocationId, locationId);
            updateElementsValue(inputsReservationDate, reservationDate);
        }
    };
    xhttp.open("POST", "/reservation/list");
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.setRequestHeader("X-CSRF-TOKEN", token);
    xhttp.send("location_id=" + locationId + "&reservation_date=" + reservationDate);
}

function updateElementsValue(elements, val) {
    for (let i = 0; i < elements.length; i++) {
        elements[i].value = val;
    }
}

window.updateReservationModal = updateReservationModal;

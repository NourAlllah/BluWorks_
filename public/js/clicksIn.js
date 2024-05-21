
document.addEventListener('DOMContentLoaded', function() {
            
    document.getElementById('clock_in_form').addEventListener('submit', function(event) {
        handle_clickin_request('{{ $worker->id }}');
        
    });

    function handle_clickin_request(id) {

        event.preventDefault();

        document.querySelector('#clock_in_form button[type="submit"]').disabled = true;

        const timestamp = Date.now();

        navigator.geolocation.getCurrentPosition(
            function(position) {
            
                const latitude = position.coords.latitude; /* 30.048143 */
                const longitude = position.coords.longitude;  /* 31.236892 */

                fetch('/api/worker/clock-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        worker_id: id,
                        timestamp: timestamp,
                        latitude: latitude,
                        longitude: longitude,
                    })  
                })
                .then(response => {


                    return response.json();

                })
                .then(data => {
                   if (data.error) {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        const message = data.message;
                        Swal.fire({
                            title: 'Success!',
                            text: message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }


                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text:'There was a problem with the fetch operation:'+ error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
                document.querySelector('#clock_in_form button[type="submit"]').disabled = false;

            },
            function(error) {
                Swal.fire({
                        title: 'Error!',
                        text:'Error getting location:'+ error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                console.error('Error getting location:', error);
            },
            {
                enableHighAccuracy: true,
                timeout: 5000
            }
        );
    }

    document.getElementById('worker_clock_ins_history').addEventListener('submit', function(event) {
        
        get_worker_clickIns('{{ $worker->id }}');

    });

    function get_worker_clickIns(id) {

        event.preventDefault(); 

        document.querySelector('#worker_clock_ins_history button[type="submit"]').disabled = true;

        // Make a GET request using fetch
        fetch('/api/worker/clock-ins?worker_id='+id, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {

            const clockIns = data;

            //setting up table before insirting data 
            const tbody = document.querySelector('#clockInsTable tbody');
            tbody.innerHTML = '';

            const table = document.getElementById('clockInsTable');
                table.style.display = 'table';

            // Insert rows for each clock-in entry
            clockIns.forEach(clockIn => {
                const row = document.createElement('tr');
                
                const timestamp = new Date(clockIn.timestamp);
                const options = { timeZone: 'Africa/Cairo', hour12: true }; 

                const date = timestamp.toLocaleDateString('en-EG', { ...options });
                const day = timestamp.toLocaleDateString('en-EG', { weekday: 'long', ...options });
                const hour = timestamp.toLocaleTimeString('en-EG', { ...options });

                row.innerHTML = `
                    <td>${date}</td>
                    <td>${day}</td>
                    <td>${hour}</td>
                `;
                tbody.appendChild(row);


            })
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
        document.querySelector('#worker_clock_ins_history button[type="submit"]').disabled = false;

    }

          
});

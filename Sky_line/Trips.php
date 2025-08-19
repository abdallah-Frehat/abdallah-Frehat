<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ... باقي الكود ...

session_start();
include 'db_config.php';

// Fetch flights data from database
$flights = [];
$query = "SELECT f.flight_id, f.flight_number, f.price, f.departure_time, f.arrival_time, 
                 da.airport_name as departure_airport, da.city as departure_city,
                 aa.airport_name as arrival_airport, aa.city as arrival_city
          FROM flights f
          JOIN airports da ON f.departure_airport_id = da.airport_id
          JOIN airports aa ON f.arrival_airport_id = aa.airport_id
          WHERE f.status = 'scheduled'";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Air Flight Page</title>
  <link rel="stylesheet" href="css/Trips.css">
  <link href="https://fonts.googleapis.com/css2?family=Lemonada:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="header">
  <h1>Trips page</h1>
  <p>Choose your destination and start your journey with us</p>
</div>

<div class="bar">
  <a href="home.php">Main</a>
  <a href="Trips.php">Trips</a>
  <a href="Support.php">Call Us</a>
  <a href="login.php" class="login-link">Login</a>

  <input type="search" id="searchInput" placeholder="Find your desired destination">
  <button class="search-button" onclick="searchFlights()">Search</button>
</div>

<div class="flights-wrapper">
  <?php
  // Group flights into rows of 2
  $flight_chunks = array_chunk($flights, 2);
  
  foreach ($flight_chunks as $flight_row) {
    echo '<div class="flight-row">';
    foreach ($flight_row as $flight) {
      $image_map = [
        'Dubai' => 'dubai.jpeg',
        'Cairo' => 'cairo.jpg',
        'Italy' => 'italy.jpg',
        'Iraq' => 'iraq.jpg',
        'China' => 'chine.jpg',
        'Greece' => 'greece.jpg',
        'Istanbul' => 'istanbul.jpg',
        'Maldive' => 'maldive.jpg'
      ];
      
      $city = $flight['arrival_city'];
      $image = isset($image_map[$city]) ? $image_map[$city] : 'default.jpg';
      
      echo '<div class="flight">
              <img src="css/img/'.$image.'" alt="'.$city.' Trip">
              <div class="flight-details">
                <h2>Flight No. '.$flight['flight_number'].': from '.$flight['departure_city'].' to '.$city.'</h2>
                <p>Departure: '.date('h:i A', strtotime($flight['departure_time'])).' - Arrival: '.date('h:i A', strtotime($flight['arrival_time'])).'</p>
                <p>Price: '.$flight['price'].' Jordanian dinars</p>
                <button class="book-ticket" onclick="bookFlight('.$flight['flight_id'].')">Book your ticket now</button>
              </div>
            </div>';
    }
    echo '</div>';
  }
  
  // If no flights found in database, show default flights
  if (empty($flights)) {
    echo '<div class="flight-row">
            <div class="flight">
              <img src="css/img/dubai.jpeg" alt="Dubai Trip">
              <div class="flight-details">
                <h2>Flight No. 101: from Amman to Dubai</h2>
                <p>Departure: 10:00 AM - Arrival: 1:00 PM</p>
                <p>Price: 360 Jordanian dinars</p>
                <button class="book-ticket">Book your ticket now</button>
              </div>
            </div>

            <div class="flight">
              <img src="css/img/cairo.jpg" alt="Cairo Trip">
              <div class="flight-details">
                <h2>Flight No. 102: from Amman to Cairo</h2>
                <p>Departure: 9:30 AM - Arrival: 11:00 AM</p>
                <p>Price: 230 Jordanian dinars</p>
                <button class="book-ticket">Book your ticket now</button>
              </div>
            </div>
          </div>

          <div class="flight-row">
            <div class="flight">
              <img src="css/img/italy.jpg" alt="Italy Trip">
              <div class="flight-details">
                <h2>Flight No. 103: from Amman to Italy</h2>
                <p>Departure: 6:00 AM - Arrival: 11:30 AM</p>
                <p>Price: 530 Jordanian dinars</p>
                <button class="book-ticket">Book your ticket now</button>
              </div>
            </div>

            <div class="flight">
              <img src="css/img/iraq.jpg" alt="Iraq Trip">
              <div class="flight-details">
                <h2>Flight No. 104: from Amman to Iraq</h2>
                <p>Departure: 2:00 PM - Arrival: 4:00 PM</p>
                <p>Price: 300 Jordanian dinars</p>
                <button class="book-ticket">Book your ticket now</button>
              </div>
            </div>
          </div>

          <div class="flight-row">
            <div class="flight">
              <img src="css/img/chine.jpg" alt="China Trip">
              <div class="flight-details">
                <h2>Flight No. 103: from Amman to China</h2>
                <p>Departure: 6:00 AM - Arrival: 11:30 AM</p>
                <p>Price: 530 Jordanian dinars</p>
                <button class="book-ticket">Book your ticket now</button>
              </div>
            </div>

            <div class="flight">
              <img src="css/img/greece.jpg" alt="Greece Trip">
              <div class="flight-details">
                <h2>Flight No. 104: from Amman to Greece</h2>
                <p>Departure: 2:00 PM - Arrival: 4:00 PM</p>
                <p>Price: 300 Jordanian dinars</p>
                <button class="book-ticket">Book your ticket now</button>
              </div>
            </div>
          </div>

          <div class="flight-row">
            <div class="flight">
              <img src="css/img/istanbul.jpg" alt="Istanbul Trip">
              <div class="flight-details">
                <h2>Flight No. 103: from Amman to Istanbul</h2>
                <p>Departure: 6:00 AM - Arrival: 11:30 AM</p>
                <p>Price: 530 Jordanian dinars</p>
                <button class="book-ticket">Book your ticket now</button>
              </div>
            </div>

            <div class="flight">
              <img src="css/img/maldive.jpg" alt="Maldive Trip">
              <div class="flight-details">
                <h2>Flight No. 104: from Amman to Maldive</h2>
                <p>Departure: 2:00 PM - Arrival: 4:00 PM</p>
                <p>Price: 300 Jordanian dinars</p>
                <button class="book-ticket">Book your ticket now</button>
              </div>
            </div>
          </div>';
  }
  ?>
</div>

<script>
function searchFlights() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  const flightRows = document.querySelectorAll('.flight-row');
  
  flightRows.forEach(row => {
    const flights = row.querySelectorAll('.flight');
    let hasVisibleFlights = false;
    
    flights.forEach(flight => {
      const flightText = flight.textContent.toLowerCase();
      
      if (flightText.includes(searchTerm)) {
        flight.style.display = 'block';
        hasVisibleFlights = true;
      } else {
        flight.style.display = 'none';
      }
    });
    
    row.style.display = hasVisibleFlights ? 'flex' : 'none';
  });
}

function bookFlight(flightId) {
  <?php if(isset($_SESSION['user_id'])): ?>
    window.location.href = 'booking.php?flight_id=' + flightId;
  <?php else: ?>
    alert('Please login to book a flight');
    window.location.href = 'login.php';
  <?php endif; ?>
}

document.getElementById('searchInput').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    searchFlights();
  }
});

window.addEventListener('load', function() {
  document.querySelectorAll('.flight').forEach(flight => {
    flight.style.display = 'block';
  });
  document.querySelectorAll('.flight-row').forEach(row => {
    row.style.display = 'flex';
  });
});
</script>

</body>
</html>
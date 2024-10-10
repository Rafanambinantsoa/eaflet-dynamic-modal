<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <title>Hello, world!</title>

    <style>
      #mapid {
        height: 400px; /* Définit la hauteur de la carte */
      }
    </style>
  </head>
  <body>
    <h1>Hello, world!</h1>
    <button type="button" class="btn btn-primary" onclick="openModal(1)">Open Modal with Map (ID 1)</button>
    <button type="button" class="btn btn-primary" onclick="openModal(3)">Open Modal with Map (ID 1)</button>
    
    <!-- Modal HTML -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Modal with Leaflet Map</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Conteneur pour la carte Leaflet -->
            <div id="mapid"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
      var map; // Variable globale pour la carte
      var marker; // Variable pour le marqueur

      // Fonction pour ouvrir le modal et appeler l'API avec un paramètre ID
      function openModal(id) {
        // Ouvre le modal
        $('#myModal').modal('show');

        // Appeler l'API pour récupérer les coordonnées
        fetch(`http://localhost:8001/api/coord/${id}`)
          .then(response => response.json())
          .then(data => {
            const latitude = parseFloat(data.latitude);
            const longitude = parseFloat(data.longitude);

            // Une fois le modal ouvert, initialiser la carte si ce n'est pas déjà fait
            $('#myModal').on('shown.bs.modal', function () {
              if (!map) {
                map = L.map('mapid').setView([latitude, longitude], 13); // Utilise les coordonnées de l'API
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  maxZoom: 19
                }).addTo(map);
              }

              // Si un marqueur existe déjà, le retirer
              if (marker) {
                map.removeLayer(marker);
              }

              // Ajouter un nouveau marqueur avec les coordonnées récupérées
              marker = L.marker([latitude, longitude]).addTo(map);

              // Ajuster la taille de la carte
              setTimeout(function() {
                map.invalidateSize();
                map.setView([latitude, longitude]); // Recentrer la carte sur les nouvelles coordonnées
              }, 200);
            });
          })
          .catch(error => {
            console.error('Erreur lors de la récupération des coordonnées:', error);
          });
      }
    </script>
  </body>
</html>

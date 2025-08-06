</div>
<!--   Core JS Files   -->
<script src="../../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../../assets/js/core/popper.min.js"></script>
<script src="../../assets/js/core/bootstrap.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="../../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Chart JS -->
<script src="../../assets/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="../../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="../../assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="../../assets/js/plugin/datatables/datatables.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="../../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="../../assets/js/plugin/jsvectormap/world.js"></script>

<!-- Sweet Alert -->
<script src="../../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Kaiadmin JS -->
<script src="../../assets/js/kaiadmin.min.js"></script>

<!-- Kaiadmin DEMO methods, don't include it in your project! -->
<script src="../../assets/js/setting-demo.js"></script>
<script src="../../assets/js/demo.js"></script>
<script src="../../assets/js/setting-demo2.js"></script>

<!-- Leaflet CSS & JS -->
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('map').setView([6.7879, 101.1726], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
    }).addTo(map);

    let markers = [];

    function loadMarkers() {
      fetch('get_locations.php')
        .then(res => res.json())
        .then(data => {
          markers.forEach(m => map.removeLayer(m));
          markers = [];

          data.forEach(user => {
            if (user.latitude && user.longitude) {
              const marker = L.marker([user.latitude, user.longitude])
                .addTo(map)
                .bindPopup(`<b>${user.first_name} ${user.last_name}</b><br>${user.phone}`);
              markers.push(marker);
            }
          });
        });
    }

    function sendCurrentLocationToServer() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
          const lat = position.coords.latitude;
          const lon = position.coords.longitude;

          fetch('update_location.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                latitude: lat,
                longitude: lon
              })
            })
            .then(res => res.json())
            .then(data => console.log(data))
            .catch(err => console.error("Location update failed:", err));
        });
      } else {
        alert("เบราว์เซอร์ไม่รองรับ GPS");
      }
    }

    loadMarkers();
    sendCurrentLocationToServer();
    setInterval(() => {
      loadMarkers();
      sendCurrentLocationToServer();
    }, 10000);
  });

  $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#177dff",
    fillColor: "rgba(23, 125, 255, 0.14)",
  });

  $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#f3545d",
    fillColor: "rgba(243, 84, 93, .14)",
  });

  $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#ffa534",
    fillColor: "rgba(255, 165, 52, .14)",
  });

  $(document).ready(function() {
    $("#basic-datatables").DataTable({});

    $("#multi-filter-select").DataTable({
      pageLength: 5,
      initComplete: function() {
        this.api()
          .columns()
          .every(function() {
            var column = this;
            var select = $(
                '<select class="form-select"><option value=""></option></select>'
              )
              .appendTo($(column.footer()).empty())
              .on("change", function() {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                column
                  .search(val ? "^" + val + "$" : "", true, false)
                  .draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function(d, j) {
                select.append(
                  '<option value="' + d + '">' + d + "</option>"
                );
              });
          });
      },
    });

    // Add Row
    $("#add-row").DataTable({
      pageLength: 5,
    });

    var action =
      '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

    $("#addRowButton").click(function() {
      $("#add-row")
        .dataTable()
        .fnAddData([
          $("#addName").val(),
          $("#addPosition").val(),
          $("#addOffice").val(),
          action,
        ]);
      $("#addRowModal").modal("hide");
    });
  });

  var myPieChart = new Chart(pieChart, {
    type: "pie",
    data: {
      datasets: [{
        data: [12, 8, 15, 10, 18, 20, 17],
        backgroundColor: ["#FF6B6B", "#6BCB77", "#4D96FF", "#FFD93D", "#FF6F91", "#845EC2", "#00C9A7"],
        borderWidth: 0,
      }, ],
      labels: ["ฉีดยา", "ดูดเสมหะ", "ตัดไหม", "ทำแผล", "พ่นยา", "เย็บแผล", "ตัดไให้น้ำเกลือหม"],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: "bottom",
        labels: {
          fontColor: "rgb(154, 154, 154)",
          fontSize: 11,
          usePointStyle: true,
          padding: 20,
        },
      },
      pieceLabel: {
        render: "percentage",
        fontColor: "white",
        fontSize: 14,
      },
      tooltips: false,
      layout: {
        padding: {
          left: 20,
          right: 20,
          top: 20,
          bottom: 20,
        },
      },
    },
  });
</script>
</body>

</html>
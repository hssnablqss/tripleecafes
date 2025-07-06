<?php
require '../BACKEND/db_connect.php';

// Fetch total sales per day (for PAID orders)
$sql = "
    SELECT 
        TO_CHAR(order_date, 'YYYY-MM-DD') AS sales_day, 
        SUM(order_total) AS total_sales 
    FROM orders 
    WHERE order_status = 'PAID'
    GROUP BY TO_CHAR(order_date, 'YYYY-MM-DD')
    ORDER BY sales_day
";
$stid = oci_parse($conn, $sql);
oci_execute($stid);

$dates = [];
$sales = [];

while ($row = oci_fetch_assoc($stid)) {
    $dates[] = $row['SALES_DAY'];
    $sales[] = floatval($row['TOTAL_SALES']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Update Your Details</title>
    <link rel="stylesheet" href="css/adminreport.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' />
    <style>
        #user-link.active {
            background-color: #f1c40f;
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- TOP NAV -->
    <div class="topnav">
        <div class="nav-left">
            <img src="./img/LogoP1.png" alt="Logo" class="logo" />
            <a href="adminhome.php">Home</a>
            <a href="adminmenu.php">Menu</a>
            <a href="adminreport.php" class="active">Report</a>
        </div>
        <div class="nav-right">
            <div class="icon-button">
                <button class="icon-button" id="user-link" onclick="toggleDropdown()">
                    <img src="./img/user-1.png" alt="user Icon" />
                    <span>User</span>
                </button>
                <div class="dropdown-content">
                    <a href="adminupddetails.html">Update Details</a>
                    <a href="adminlogin.html">Log out</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="chart">
            <canvas id="barchart" width="400" height="400"></canvas>
            <div id="bar-summary" class="chart-summary"></div>
            <button onclick="printChart('barchart')" class="print-btn">Print Bar Chart</button>
        </div>

        <div class="chart">
            <canvas id="doughnut" width="450" height="450"></canvas>
            <button onclick="printChart('doughnut')" class="print-btn">Print Doughnut Chart</button>
        </div>
    </div>

    <script>
        if (window.location.href.indexOf("adminupddetails.html") !== -1) {
            document.getElementById("user-link").classList.add("active");
        }

        document.addEventListener('click', function (event) {
            const isClickInside = document.querySelector('.icon-button').contains(event.target);
            if (!isClickInside) {
                const dropdown = document.querySelector('#userIcon .dropdown-content');
                if (dropdown) dropdown.style.display = 'none';
            }
        });

        function printChart(canvasId) {
            const canvas = document.getElementById(canvasId);
            const summary = canvas.parentElement.querySelector('.chart-summary');
            const win = window.open('', '', 'width=800,height=600');
            win.document.write('<html><head><title>Print Chart</title></head><body style="text-align:center; font-family:Poppins,sans-serif;">');
            win.document.write('<img src="' + canvas.toDataURL() + '" style="max-width:100%; height:auto;"/>');
            if (summary) {
                win.document.write('<div style="margin-top: 20px; font-size: 16px;">' + summary.innerHTML + '</div>');
            }
            win.document.write('</body></html>');
            win.document.close();
            win.focus();
            win.print();
            win.close();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
    <script>
        const labels = <?= json_encode($dates) ?>;
        const data = <?= json_encode($sales) ?>;

        const pastelColors = [
            '#f9c5d1', // pink
            '#fcd5ce', // peach
            '#d4e2d4', // mint
            '#cde7f0', // light blue
            '#fef9c7', // pastel yellow
            '#e0c3fc', // lavender
            '#ffdac1'  // coral
        ];

        const barCtx = document.getElementById('barchart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Sales (RM)',
                    data: data,
                    backgroundColor: pastelColors,
                    borderColor: '#ccc',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sales (RM)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });

        // Doughnut chart (still placeholder)
        const doughnutCtx = document.getElementById('doughnut').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Food', 'Drink', 'Dessert'],
                datasets: [{
                    label: 'Sales by Category',
                    data: [40, 30, 30], // placeholder
                    backgroundColor: ['#f9c5d1', '#d4e2d4', '#cde7f0'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            }
        });
    </script>
</body>

</html>

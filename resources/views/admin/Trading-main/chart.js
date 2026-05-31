// chart.js
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'line', // Line chart
    data: {
        labels: ['0:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00','9:00','10:00', '11:00', '12:00','13:00','14:00'], // X-axis labels
        datasets: [{
            label: 'Price', // Label for the dataset
            data: [0.0702, 0.0205, 0.025, 0.01111, 0.0505, 0.0602, 0.0200, 0.0030, 0.0802,0.0100,0.0667,0.0000, 0.0202,0.0300,0.0444], // Data points
            backgroundColor: 'rgba(255, 99, 132, 0.2)', // Background color
            borderColor: 'rgba(255, 99, 132, 1)', // Border color
            borderWidth: 1, // Border width
            fill: true // Fill area under the line
        }]
    },
    options: {
        scales: {
            x: {
                beginAtZero: true // Start the X-axis at 0
            },
            y: {
                beginAtZero: true // Start the Y-axis at 0
            }
        },
        elements: {
            line: {
                tension: 0.5 // Curve line
            }
        }
    }
});





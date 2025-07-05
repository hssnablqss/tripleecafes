document.addEventListener('DOMContentLoaded', () => {
  const ctx2 = document.getElementById('doughnut').getContext('2d');
  const data = [12, 19, 3, 5, 2, 3];
  const total = data.reduce((a, b) => a + b, 0);

  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: data,
        backgroundColor: [
          'rgba(255, 99, 132, 0.5)',
          'rgba(54, 162, 235, 0.5)',
          'rgba(255, 206, 86, 0.5)',
          'rgba(75, 192, 192, 0.5)',
          'rgba(153, 102, 255, 0.5)',
          'rgba(255, 159, 64, 0.5)'
        ],
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: 'white'
          }
        },
        datalabels: {
          color: 'white',
          formatter: (value, context) => {
            const percentage = (value / total * 100).toFixed(1);
            return percentage + '%';
          },
          font: {
            weight: 'bold'
          }
        },
        title: {
          display: true,
          text: 'Doughnut Chart - % Breakdown',
          color: 'white',
          font: {
            size: 18
          }
        }
      }
    },
    plugins: [ChartDataLabels]
  });
});

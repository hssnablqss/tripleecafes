document.addEventListener('DOMContentLoaded', () => {
  const ctx = document.getElementById('barchart').getContext('2d');
  const summaryDiv = document.getElementById('bar-summary');

  const labels = ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
  const data = [12, 19, 3, 5, 2, 3];
  const total = data.reduce((a, b) => a + b, 0);

  // Find highest & lowest
  const maxVal = Math.max(...data);
  const minVal = Math.min(...data);
  const maxIndex = data.indexOf(maxVal);
  const minIndex = data.indexOf(minVal);

  // Show summary
  summaryDiv.innerHTML = `
    <span>The highest is ${labels[maxIndex]} with ${maxVal} votes!</span><br>
    <span>The lowest is ${labels[minIndex]} with ${minVal} votes!</span>
  `;

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
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
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            color: 'white'
          }
        },
        x: {
          ticks: {
            color: 'white'
          }
        }
      },
      plugins: {
        legend: {
          labels: {
            color: 'white'
          }
        },
        datalabels: {
          color: 'white',
          anchor: 'end',
          align: 'top',
          formatter: (value) => {
            const percentage = (value / total * 100).toFixed(1);
            return percentage + '%';
          },
          font: {
            weight: 'bold'
          }
        },
        title: {
          display: true,
          text: 'Bar Chart - % Breakdown',
          color: 'white',
          font: {
            size: 18
          },
          padding: {
            top: 10,
            bottom: 30
          }
        }
      }
    },
    plugins: [ChartDataLabels]
  });
});

//$( document ).ready(function() {

  const chart=document.getElementById('piechart');
  Chart.defaults.scale.ticks.beginAtZero=true;

  var pieChart = new Chart(chart, {
    type: 'pie',
    data: {
      labels:['en cours','demande','fini'],
      datasets:[
        {
          label:'Status',
          backgroundColor:['#f1c40f','#e67e22','#16a085'],
          data:[10,20,30]
        }
      ]
    }
  });
//});

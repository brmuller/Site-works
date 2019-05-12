$( document ).ready(function() {

  //Chart.defaults.scale.ticks.beginAtZero=true;

  var ctx = document.getElementById('myChart');
  if (ctx){
    var doughnutChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels:['en cours','demande','fini'],
        datasets:[
          {
            label:'Status',
            backgroundColor:['#f1c40f','#e67e22','#16a085'],
            data:[10,20,30]
          }
        ]
      },
      options: {
        responsive: true
      }
    });
  }

});

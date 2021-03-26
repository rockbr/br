// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';


$(document).ready(function () {

  $("select[name=pagar_receber_mes_ano]").change(function () {
    var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
    var query = $(this).val();
    return $.ajax({
      type: 'POST',
      url: '/pagarreceberpormes',
      data: { csrf_test_name: csrf_hash, query: query },
      dataType: 'json',
    })
      .done(function (response) {
        $('input[name=csrf_test_name]').val(response.csrf_hash);

        //Remove e add novamente para limpar os dados e não dar erro na apresentacao
        $("canvas#chartReportPagarReceberPorMes").remove();
        document.querySelector("#chartReportPagarReceberPorMes").innerHTML = '<canvas id="chartPagarReceberPorMes" width="100%" height="50"></canvas>';

        var chart = document.getElementById("chartPagarReceberPorMes").getContext("2d");
        renderGraphLine(chart, response.labels, response.pointspagar, response.pointsreceber);
      })
      .fail(function (jqXHR, textStatus, msg) {
        alert(msg);
      });
  });

  $("select[name=pagar_mes_ano]").change(function () {
    var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
    var query = $(this).val();
    return $.ajax({
      type: 'POST',
      url: '/pagarpormes',
      data: { csrf_test_name: csrf_hash, query: query },
      dataType: 'json',
    })
      .done(function (response) {
        $('input[name=csrf_test_name]').val(response.csrf_hash);

        //Remove e add novamente para limpar os dados e não dar erro na apresentacao
        $("canvas#chartReportPagarPorMes").remove();
        document.querySelector("#chartReportPagarPorMes").innerHTML = '<canvas id="chartPagarPorMes" width="100%" height="50"></canvas>';

        var chart = document.getElementById("chartPagarPorMes").getContext("2d");
        renderGraph(chart, response.labels, response.points, 1);
      })
      .fail(function (jqXHR, textStatus, msg) {
        alert(msg);
      });
  });

  $("select[name=receber_mes_ano]").change(function () {
    var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
    var query = $(this).val();
    return $.ajax({
      type: 'POST',
      url: '/receberpormes',
      data: { csrf_test_name: csrf_hash, query: query },
      dataType: 'json',
    })
      .done(function (response) {
        $('input[name=csrf_test_name]').val(response.csrf_hash);

        //Remove e add novamente para limpar os dados e não dar erro na apresentacao
        $("canvas#chartReportReceberPorMes").remove();
        document.querySelector("#chartReportReceberPorMes").innerHTML = '<canvas id="chartReceberPorMes" width="100%" height="50"></canvas>';

        var chart = document.getElementById("chartReceberPorMes").getContext("2d");
        renderGraph(chart, response.labels, response.points, 2);
      })
      .fail(function (jqXHR, textStatus, msg) {
        alert(msg);
      });
  });

});

window.onload = function () {
  var chartPagarPorMes = document.getElementById("chartPagarPorMes").getContext("2d");

  if (chartPagarPorMes != null) {
    $(document).ready(function () {
      var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
      return $.ajax({
        type: 'POST',
        url: '/pagarpormes',
        data: { csrf_test_name: csrf_hash, query: dataAtualFormatada() },
        dataType: 'json',
      })
        .done(function (response) {
          $('input[name=csrf_test_name]').val(response.csrf_hash);
          renderGraph(chartPagarPorMes, response.labels, response.points, 1);
        })
        .fail(function (jqXHR, textStatus, msg) {
          alert(msg);
        });
    });
  }

  var chartReceberPorMes = document.getElementById("chartReceberPorMes").getContext("2d");

  if (chartReceberPorMes != null) {
    $(document).ready(function () {
      var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
      return $.ajax({
        type: 'POST',
        url: '/receberpormes',
        data: { csrf_test_name: csrf_hash, query: dataAtualFormatada() },
        dataType: 'json',
      })
        .done(function (response) {
          $('input[name=csrf_test_name]').val(response.csrf_hash);
          renderGraph(chartReceberPorMes, response.labels, response.points, 2);
        })
        .fail(function (jqXHR, textStatus, msg) {
          alert(msg);
        });
    });
  }

  var chartPagarReceberPorMes = document.getElementById("chartPagarReceberPorMes").getContext("2d");

  if (chartPagarReceberPorMes != null) {
    $(document).ready(function () {
      var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
      return $.ajax({
        type: 'POST',
        url: '/pagarreceberpormes',
        data: { csrf_test_name: csrf_hash, query: dataAtualFormatada() },
        dataType: 'json',
      })
        .done(function (response) {
          $('input[name=csrf_test_name]').val(response.csrf_hash);
          renderGraphLine(chartPagarReceberPorMes, response.labels, response.pointspagar, response.pointsreceber);
        })
        .fail(function (jqXHR, textStatus, msg) {
          alert(msg);
        });
    });
  }
}

var renderGraph = function (chart, rotulos, dados, cor) {

  var color;
  if (cor == 1)
    color = "rgba(255,0,0,0.7)";//Vermelho
  if (cor == 2)
    color = "rgba(2,100,205,7)";//Azul

  var myLineChart = new Chart(chart, {
    type: 'bar',
    data: {
      labels: rotulos,
      datasets: [{
        label: "Valor",
        backgroundColor: color,
        borderColor: color,
        data: dados,
      }],
    },
    options: {
      scales: {
        xAxes: [{
          time: {
            unit: 'day'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 31
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            maxTicksLimit: 10
          },
          gridLines: {
            display: true
          }
        }],
      },
      legend: {
        display: false
      }
    }
  })
};

var renderGraphLine = function (chart, rotulos, pagar, receber) {

  var lineChartData = new Chart(chart, {
    type: 'line',
    data: {
      labels: rotulos,
      datasets: [{
        label: 'Pagar',
        borderColor: 'red',
        backgroundColor: 'red',
        pointBackgroundColor: 'DarkRed',
        pointBorderColor: 'DarkRed',       
        fill: false,        
        data: pagar,
        yAxisID: 'y-axis-1',
      }, {
        label: 'Receber',
        borderColor: 'blue',
        backgroundColor: 'blue',
        pointBackgroundColor: 'DarkBlue',
        pointBorderColor: 'DarkBlue',
        fill: false,
        data: receber,
        yAxisID: 'y-axis-2'
      }]
    },
     options: {
      responsive: true,
      hoverMode: 'index',
      stacked: false,
      scales: {
        yAxes: [{
          type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
          display: true,
          position: 'left',
          id: 'y-axis-1',
        }, {
          type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
          display: true,
          position: 'right',
          id: 'y-axis-2',

          // grid line settings
          gridLines: {
            drawOnChartArea: false, // only want the grid lines for one axis to show up
          },
        }],
      }
    }
  })
};

function dataAtualFormatada() {
  var data = new Date(),
    mes = (data.getMonth() + 1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    ano = data.getFullYear();
  return mes + "/" + ano;
}

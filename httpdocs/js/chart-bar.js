// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';


$(document).ready(function () {

  $("select[name=mes_ano]").change(function () {
    var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
    var query = $(this).val();
    return $.ajax({
      type: 'POST',
      url: '/vendapormes',
      data: { csrf_test_name: csrf_hash, query: query },
      dataType: 'json',
    })
      .done(function (response) {
        $('input[name=csrf_test_name]').val(response.csrf_hash);

        //Remove e add novamente para limpar os dados e não dar erro na apresentacao
        $("canvas#chartReportVendaPorMes").remove();
        document.querySelector("#chartReportVendaPorMes").innerHTML = '<canvas id="chartVendaPorMes" width="100%" height="50"></canvas>';

        var chart = document.getElementById("chartVendaPorMes").getContext("2d");
        renderGraph(chart, response.labels, response.points, 1);
      })
      .fail(function (jqXHR, textStatus, msg) {
        alert(msg);
      });
  });

  $("select[name=mes_ano_vendedora]").change(function () {
    var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
    var query = $(this).val();
    return $.ajax({
      type: 'POST',
      url: '/vendapormesvendedora',
      data: { csrf_test_name: csrf_hash, query: query },
      dataType: 'json',
    })
      .done(function (response) {
        $('input[name=csrf_test_name]').val(response.csrf_hash);

        //Remove e add novamente para limpar os dados e não dar erro na apresentacao
        $("canvas#chartReportVendaPorMesVendedora").remove();
        document.querySelector("#chartReportVendaPorMesVendedora").innerHTML = '<canvas id="chartVendaPorMesVendedora" width="100%" height="50"></canvas>';

        var chart = document.getElementById("chartVendaPorMesVendedora").getContext("2d");
        renderGraph(chart, response.labels, response.points, 2);
      })
      .fail(function (jqXHR, textStatus, msg) {
        alert(msg);
      });
  });

});

window.onload = function () {
  var chartVendaPorMes = document.getElementById("chartVendaPorMes").getContext("2d");

  if (chartVendaPorMes != null) {
    $(document).ready(function () {
      var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
      return $.ajax({
        type: 'POST',
        url: '/vendapormes',
        data: { csrf_test_name: csrf_hash, query: dataAtualFormatada() },
        dataType: 'json',
      })
        .done(function (response) {
          $('input[name=csrf_test_name]').val(response.csrf_hash);
          renderGraph(chartVendaPorMes, response.labels, response.points, 1);
        })
        .fail(function (jqXHR, textStatus, msg) {
          alert(msg);
        });
    });
  }

  var chartVendaPorMesVendedora = document.getElementById("chartVendaPorMesVendedora").getContext("2d");

  if (chartVendaPorMesVendedora != null) {
    $(document).ready(function () {
      var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
      return $.ajax({
        type: 'POST',
        url: '/vendapormesvendedora',
        data: { csrf_test_name: csrf_hash, query: dataAtualFormatada() },
        dataType: 'json',
      })
        .done(function (response) {
          $('input[name=csrf_test_name]').val(response.csrf_hash);
          renderGraph(chartVendaPorMesVendedora, response.labels, response.points, 2);
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
    color = "rgba(2,100,205,7)";//Azul
  if (cor == 2)
    color = "rgba(255,0,0,0.7)";//Vermelho

  var myLineChart = new Chart(chart, {
    type: 'bar',
    data: {
      labels: rotulos,
      datasets: [{
        label: "Produtos Vendidos",
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

function dataAtualFormatada() {
  var data = new Date(),
    mes = (data.getMonth() + 1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    ano = data.getFullYear();
  return mes + "/" + ano;
}

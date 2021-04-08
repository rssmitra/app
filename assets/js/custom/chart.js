function GraphColumnStyle(id, nameid, url){

    //use getJSON to get the dynamic data via AJAX call
    $.getJSON(url, {id: id}, function(chartData) {
      //alert(chartData.xAxis.categories); return false;
      $('#'+nameid).highcharts({

        chart: {
              type: 'column'
          },
          title: {
              text: chartData.title,
          },
          subtitle: {
              text: chartData.subtitle,
          },
          xAxis: chartData.xAxis,
          yAxis: {
              min: 0,
              title: {
                  text: 'Total'
              }
          },
          plotOptions: {
              column: {
                  pointPadding: 0.2,
                  borderWidth: 0
              }
          },
          series: chartData.series
      });
    });
  }

  function GraphPieStyle(id, nameid, url){

    //use getJSON to get the dynamic data via AJAX call
    $.getJSON(url, {id: id}, function(chartData) {
      //alert(chartData.xAxis.categories); return false;
      $('#'+nameid).highcharts({

        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: chartData.title
        },
        subtitle: {
              text: chartData.subtitle,
          },
        tooltip: {
            pointFormat: 'Persentase: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    style: {
                        width: '300'
                    },
                    enabled: true,
                    color: '#000000',
                    maxStaggerLines:1,                    
                    connectorColor: '#000000',
                    format: '{point.name}'
                }
            }
        },
        series: [{
            data: chartData.series
        }]

      });

    });
  }

  function GraphLineStyle(id, nameid, url){

    //use getJSON to get the dynamic data via AJAX call
    $.getJSON(url, {id: id}, function(chartData) {
      //alert(chartData.xAxis.categories); return false;
      $('#'+nameid).highcharts({

        title: {
            text: chartData.title,
            x: -20 //center
        },
        subtitle: {
            text: chartData.subtitle,
            x: -20
        },
        xAxis: chartData.xAxis,
        yAxis: {
            title: {
                text: 'Total'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0
        },
        series: chartData.series

      });

    });
  }

  function GraphTableStyle(id, nameid, url){

    //use getJSON to get the dynamic data via AJAX call
    $.getJSON(url, {id: id}, function(chartData) {
      //alert(chartData.xAxis.categories); return false;
      $('#'+nameid).html('<h3 align="center">'+chartData.title+'</h3>'+chartData.series);

    });
  }
var randomizeArray = function (arg) {
    var array = arg.slice();
    var currentIndex = array.length, temporaryValue, randomIndex;
  
    while (0 !== currentIndex) {
  
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex -= 1;
  
      temporaryValue = array[currentIndex];
      array[currentIndex] = array[randomIndex];
      array[randomIndex] = temporaryValue;
    }
  
    return array;
  }

var sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35,38, 56, 24, 65, 31, 37, 39];





var colorPalette = ['#00D8B6','#008FFB',  '#FEB019', '#FF4560', '#775DD0']

// chart-1


var options1 = {
    series: [{
    data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54]
  }],
    chart: {
    type: 'area',
    width: 100,
    height: 35,
    sparkline: {
      enabled: true
    }
  },
  tooltip: {
    fixed: {
      enabled: false
    },
    x: {
      show: false
    },
    y: {
      title: {
        formatter: function (seriesName) {
          return ''
        }
      }
    },
    marker: {
      show: false
    }
  }
  };

  var chart1 = new ApexCharts(document.querySelector("#chart-1"), options1);
  chart1.render();

//   chart-2


var options2 = {
    series: [{
    data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14]
  }],
    chart: {
    type: 'area',
    width: 100,
    height: 35,
    sparkline: {
      enabled: true
    }
  },
  tooltip: {
    fixed: {
      enabled: false
    },
    x: {
      show: false
    },
    y: {
      title: {
        formatter: function (seriesName) {
          return ''
        }
      }
    },
    marker: {
      show: false
    }
  }
  };

  var chart2 = new ApexCharts(document.querySelector("#chart-2"), options2);
  chart2.render();

// chart 3


var options3 = {
    series: [{
    data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14]
  }],
    chart: {
    type: 'line',
    width: 100,
    height: 35,
    sparkline: {
      enabled: true
    }
  },
  tooltip: {
    fixed: {
      enabled: false
    },
    x: {
      show: false
    },
    y: {
      title: {
        formatter: function (seriesName) {
          return ''
        }
      }
    },
    marker: {
      show: false
    }
  }
  };

  var chart3 = new ApexCharts(document.querySelector("#chart-3"), options3);
  chart3.render();

//   chart-4

var options5 = {
    series: [{
    data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54]
  }],
    chart: {
    type: 'bar',
    width: 100,
    height: 35,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      columnWidth: '80%'
    }
  },
  labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
  xaxis: {
    crosshairs: {
      width: 1
    },
  },
  tooltip: {
    fixed: {
      enabled: false
    },
    x: {
      show: false
    },
    y: {
      title: {
        formatter: function (seriesName) {
          return ''
        }
      }
    },
    marker: {
      show: false
    }
  }
  };

  var chart5 = new ApexCharts(document.querySelector("#chart-4"), options5);
  chart5.render();



//   chart-deposit-1



var options6 = {
    series: [{
    data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14]
  }],
    chart: {
    type: 'bar',
    width: 100,
    height: 35,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      columnWidth: '80%'
    }
  },
  colors:['#94a3b8'],
  labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
  xaxis: {
    crosshairs: {
      width: 1
    },
  },
  tooltip: {
    fixed: {
      enabled: false
    },
    x: {
      show: false
    },
    y: {
      title: {
        formatter: function (seriesName) {
          return '$'
        }
      }
    },
    marker: {
      show: false
    }
  }
  };

  var chart6 = new ApexCharts(document.querySelector("#deposit-chart-1"), options6);
  chart6.render();


//   chart-deposit-2

var chartdeposit2options6 = {
    series: [{
    data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14]
  }],
    chart: {
    type: 'bar',
    width: 100,
    height: 35,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      columnWidth: '80%'
    }
  },
  colors:['#4ade80'],
  labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
  xaxis: {
    crosshairs: {
      width: 1
    },
  },
  tooltip: {
    fixed: {
      enabled: false
    },
    x: {
      show: false
    },
    y: {
      title: {
        formatter: function (seriesName) {
          return 'USD'
        }
      }
    },
    marker: {
      show: false
    }
  }
  };

  var chartdeposit2 = new ApexCharts(document.querySelector("#deposit-chart-2"), chartdeposit2options6);
  chartdeposit2.render();




// chart-deposit-3


var chartdeposit3options6 = {
    series: [{
    data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14]
  }],
    chart: {
    type: 'bar',
    width: 100,
    height: 35,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      columnWidth: '80%'
    }
  },
  colors:['#e879f9'],
  labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
  xaxis: {
    crosshairs: {
      width: 1
    },
  },
  tooltip: {
    fixed: {
      enabled: false
    },
    x: {
      show: false
    },
    y: {
      title: {
        formatter: function (seriesName) {
          return '$'
        }
      }
    },
    marker: {
      show: false
    }
  }
  };

  var chartdeposit3 = new ApexCharts(document.querySelector("#deposit-chart-3"), chartdeposit3options6);
  chartdeposit3.render();


// bar  web statics

var optionsBar = {
    chart: {
      type: 'bar',
      height: 250,
      width: '100%',
      stacked: true,
      foreColor: '#999',
    },
    plotOptions: {
      bar: {
        dataLabels: {
          enabled: false
        },
        columnWidth: '75%',
        endingShape: 'rounded'
      }
    },
    colors: ["#00C5A4", '#F3F2FC'],
    series: [{
      name: "Sessions",
      data: [20, 16, 24, 28, 26, 22, 15, 5, 14, 16, 22, 29, 24, 19, 15, 10, 11, 15, 19, 23],
    }, {
      name: "Views",
      data: [20, 16, 24, 28, 26, 22, 15, 5, 14, 16, 22, 29, 24, 19, 15, 10, 11, 15, 19, 23],
    }],
    labels: [15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 1, 2, 3, 4],
    xaxis: {
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      },
      crosshairs: {
        show: false
      },
      labels: {
        show: false,
        style: {
          fontSize: '14px'
        }
      },
    },
    grid: {
      xaxis: {
        lines: {
          show: false
        },
      },
      yaxis: {
        lines: {
          show: false
        },
      }
    },
    yaxis: {
      axisBorder: {
        show: false
      },
      labels: {
        show: false
      },
    },
    legend: {
      floating: true,
      position: 'top',
      horizontalAlign: 'right',
      offsetY: -36
    },
    title: {
      text: 'Web Statistics',
      align: 'left',
    },
    subtitle: {
      text: 'Sessions and Views'
    },
    tooltip: {
      shared: true,
      intersect: false
    }
  
  }
  
  var chartBar = new ApexCharts(document.querySelector('#bar'), optionsBar);
  chartBar.render();



  // *****************Bounce Rate******************


  var optionsCircle1 = {
    chart: {
      type: 'radialBar',
      height: 266,
      zoom: {
        enabled: false
      },
      offsetY: 20
    },
    colors: ['#E91E63'],
    plotOptions: {
      radialBar: {
        dataLabels: {
          name: {
            show: false
          },
          value: {
            offsetY: 0
          }
        }
      }
    },
    series: [65],
    theme: {
      monochrome: {
        enabled: false
      }
    },
    legend: {
      show: false
    },
    title: {
      text: 'Bounce Rate',
      align: 'left'
    }
  }
  
  var chartCircle1 = new ApexCharts(document.querySelector('#radialBar1'), optionsCircle1);
  chartCircle1.render();

  
  // *********************end bounce Rate****

  // genearl insigths


  var spark10 = {
    chart: {
      id: 'sparkline1',
      type: 'line',
      height: 140,
      sparkline: {
        enabled: true
      },
      group: 'sparklines'
    },
    series: [{
      name: 'visits',
      data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
    }],
    stroke: {
      curve: 'smooth'
    },
    markers: {
      size: 0
    },
    tooltip: {
      fixed: {
        enabled: true,
        position: 'right'
      },
      x: {
        show: false
      }
    },
    title: {
      text: 'Total Visits:  439',
      style: {
        fontSize: '24px',
        color:'#eee'
      }
    },
    colors: ['#734CEA']
  }
  
  var spark20 = {
    chart: {
      id: 'sparkline2',
      type: 'line',
      height: 140,
      sparkline: {
        enabled: true
      },
      group: 'sparklines'
    },
    series: [{
      name: 'Rates',
      data: [12, 14, 2, 47, 32, 44, 14, 55, 41, 69]
    }],
    stroke: {
      curve: 'smooth'
    },
    markers: {
      size: 0
    },
    tooltip: {
      fixed: {
        enabled: true,
        position: 'right'
      },
      x: {
        show: false
      }
    },
    title: {
      text: 'Bounce Rates: 387',
      style: {
        fontSize: '26px',
        color:'#eee'
      }
    },
    colors: ['#34bfa3']
  }
  
  var spark30 = {
    chart: {
      id: 'sparkline3',
      type: 'line',
      height: 140,
      sparkline: {
        enabled: true
      },
      group: 'sparklines'
    },
    series: [{
      name: 'Views',
      data: [47, 45, 74, 32, 56, 31, 44, 33, 45, 19]
    }],
    stroke: {
      curve: 'smooth'
    },
    markers: {
      size: 0
    },
    tooltip: {
      fixed: {
        enabled: true,
        position: 'right'
      },
      x: {
        show: false
      }
    },
    colors: ['#f4516c'],
    title: {
      text: 'Page Views: 577',
      style: {
        fontSize: '24px',
        color:'#eee'
      }
    },
    xaxis: {
      crosshairs: {
        width: 1
      },
    }
  }
  
  var spark40 = {
    chart: {
      id: 'sparkline4',
      type: 'line',
      height: 140,
      sparkline: {
        enabled: true
      },
      group: 'sparklines'
    },
    series: [{
      name: 'Unique-visits',
      data: [15, 75, 47, 65, 14, 32, 19, 54, 44, 61]
    }],
    stroke: {
      curve: 'smooth'
    },
    markers: {
      size: 0
    },
    tooltip: {
      fixed: {
        enabled: true,
        position: 'right'
      },
      x: {
        show: false
      }
    },
    colors: ['#00c5dc'],
    title: {
      text: 'Unique Visits: 615',
      style: {
        fontSize: '24px',
        color:'#eee'
      }
    },
    xaxis: {
      crosshairs: {
        width: 1
      },
    }
  }
  
  new ApexCharts(document.querySelector("#spark10"), spark10).render();
  new ApexCharts(document.querySelector("#spark20"), spark20).render();
  new ApexCharts(document.querySelector("#spark30"), spark30).render();
  new ApexCharts(document.querySelector("#spark40"), spark40).render(); 
   // End general insights 


// donut top

var optionsDonutTop = {
    chart: {
      height: 265,
      type: 'donut',
      offsetY: 20
    },
    plotOptions: {
      pie: {
        customScale: 1,
        donut: {
          size: '72%',
        },
        dataLabels: {
          enabled: false
        }
      }
    },
    colors: ["#0f172a","#4338ca","#7e22ce","#be185d","#0284c7","#047857"],
    title: {
      text: 'Browser used by users'
    },
    series: [5, 7, 5,9,9,10,],
    labels: ["Google Chrome","Mozilla FireFox","Safari Browser","UC Browser","Edge/IE","Other Browsers"],
    legend: {
      show: false
    }
  }
  
  var chartDonut2 = new ApexCharts(document.querySelector('#donutTop'), optionsDonutTop);
  chartDonut2.render().then(function () {
    // window.setInterval(function () {
    //   chartDonut2.updateSeries([getRandom(), getRandom(), getRandom()])
    // }, 1000)
  });

var optionDonut = {
    chart: {
        type: 'donut',
        width: '100%',
        height: 400
    },
    dataLabels: {
      enabled: false,
    },
    plotOptions: {
      pie: {
        customScale: 0.8,
        donut: {
          size: '75%',
        },
        offsetY: 20,
      },
      stroke: {
        colors: undefined
      }
    },
    colors: colorPalette,
    title: {
    //   text: 'Session By Devices',
      style: {
        fontSize: '16px',
        paddingTop:'10px'
      }
    },
    series: [21, 70, 19],
    
    labels: ['Desktop', 'Mobile', 'Tablet'],
    legend: {
      position: 'left',
      offsetY: 80
    }
  }



  
  var donut = new ApexCharts(
    document.querySelector("#donut"),
    optionDonut
  )
  donut.render();


var spark1 = {
  chart: {
    id: 'sparkline1',
    group: 'sparklines',
    type: 'area',
    height: 100,
    // width:300,
    sparkline: {
      enabled: true
    },
  },
  stroke: {
    curve: 'straight'
  },
  fill: {
    opacity: 1,
  },
  series: [{
    name: 'Rate',
    data: randomizeArray(sparklineData)
  }],
  labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
  yaxis: {
    min: 0
  },
  xaxis: {
    type: 'datetime',
  },
  colors: ['#7c3aed'],
  title: {
    text: 'Bounce Rate (AVG)',
    offsetX: 0,
    style: {
      fontSize: '14px',
      cssClass: 'text-indigo-600'
    }
  },
  subtitle: {
    // text: 'Sales',
    offsetX: 30,
    style: {
      fontSize: '14px',
      cssClass: 'apexcharts-yaxis-title'
    }
  }
}

var spark2 = {
    chart: {
      id: 'sparkline2',
      group: 'sparklines',
      type: 'area',
      height: 100,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
      curve: 'straight'
    },
    fill: {
      opacity: 1,
    },
    series: [{
      name: 'Views',
      data: randomizeArray(sparklineData)
    }],
    labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
    yaxis: {
      min: 0
    },
    xaxis: {
      type: 'datetime',
    },
    colors: ['#52525b'],
    title: {
      text: 'Page Views (AVG)',
      offsetX: 0,
      style: {
        fontSize: '14px',
        cssClass: 'apexcharts-yaxis-title'
      }
    },
    subtitle: {
    //   text: 'Expenses',
      offsetX: 30,
      style: {
        fontSize: '14px',
        cssClass: 'apexcharts-yaxis-title'
      }
    }
  }
  
  var spark3 = {
    chart: {
      id: 'sparkline3',
      group: 'sparklines',
      type: 'area',
      height: 100,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
      curve: 'straight'
    },
    fill: {
      opacity: 1,
    },
    series: [{
      name: 'Users',
      data: randomizeArray(sparklineData)
    }],
    labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
    xaxis: {
      type: 'datetime',
    },
    yaxis: {
      min: 0
    },
    colors: ['#67e8f9'],
    //colors: ['#5564BE'],
    title: {
      text: 'New Users (AVG)',
      offsetX: 0,
      style: {
        fontSize: '14px',
        color:'#52525b',
        cssClass: 'apexcharts-yaxis-title'
      }
    },
    subtitle: {
    //   text: 'Profits',
      offsetX: 0,
      style: {
        fontSize: '14px',
        cssClass: 'apexcharts-yaxis-title'
      }
    }
  }


new ApexCharts(document.querySelector("#spark1"), spark1).render()
new ApexCharts(document.querySelector("#spark2"), spark2).render()
new ApexCharts(document.querySelector("#spark3"), spark3).render()


















var options = {
    series: [{
      name: "USD",
      data: [5.00, 20.02, 10.00, 41.23, 35.00]
  }],
    chart: {
    height: 190,
    type: 'line',
    zoom: {
      enabled: false
    }
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    // curve: 'straight'
    width: 5,
    curve: 'smooth'
  },
  markers: {
    size: 6,
    strokeWidth: 0,
    hover: {
      size: 9
    }
  },
  title: {
    text: 'Monthly Income',
    align: 'left'
  },
  grid: {
    row: {
      colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
      opacity: 0.5
    },
  },
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
  }
  };



  var linechart = new ApexCharts(document.querySelector("#line-chart"), options);
  linechart.render();


  var optionsSales = {
    series: [{
    name: 'Net Profit',
    data: [60, 85, 99, 120, 130,150]
  }, 
//   {
//     name: 'Revenue',
//     data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
//   },
//    {
//     name: 'Free Cash Flow',
//     data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
//   }
],
    chart: {
    type: 'bar',
    height: 190
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '55%',
      endingShape: 'rounded'
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    show: true,
    width: 2,
    colors: ['transparent']
  },
  
  title: {
    text: 'Sales Reporting',
    align: 'left'
  },
  xaxis: {
    categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul',],
  },
  yaxis: {
    title: {
      text: '$ (thousands)'
    }
  },
  fill: {
    opacity: 1
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return "$ " + val + " thousands"
      }
    }
  }
  };

  var salesChart = new ApexCharts(document.querySelector("#sales-report-chart"), optionsSales);
  salesChart.render();



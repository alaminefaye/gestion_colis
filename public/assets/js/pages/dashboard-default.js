'use strict';
document.addEventListener('DOMContentLoaded', function () {
  setTimeout(function () {
    floatchart();
  }, 500);
});

function floatchart() {
  // Graphique de statistiques par jour (nouveau - par défaut)
  (function () {
    // Préparer les données pour le graphique jour
    const heures = statistiquesLivraisons.map(item => item.heure);
    const livraisons = statistiquesLivraisons.map(item => item.livraisons);
    const ramassages = statistiquesLivraisons.map(item => item.ramassages);
    
    var options = {
      chart: {
        height: 450,
        type: 'area',
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      colors: ['#1890ff', '#13c2c2'],
      series: [{
        name: 'Livraisons',
        data: livraisons
      }, {
        name: 'Ramassages',
        data: ramassages
      }],
      stroke: {
        curve: 'smooth',
        width: 2
      },
      xaxis: {
        categories: heures,
      },
      yaxis: {
        title: {
          text: 'Nombre de colis'
        }
      },
      tooltip: {
        shared: true,
        intersect: false,
      }
    };
    var chartJour = new ApexCharts(document.querySelector('#visitor-chart-jour'), options);
    chartJour.render();
  })();

  // Graphique de statistiques par semaine (existant)
  (function () {
    var options = {
      chart: {
        height: 450,
        type: 'area',
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      colors: ['#1890ff', '#13c2c2'],
      series: [{
        name: 'Livraisons',
        data: [31, 40, 28, 51, 42, 109, 100]
      }, {
        name: 'Ramassages',
        data: [11, 32, 45, 32, 34, 52, 41]
      }],
      stroke: {
        curve: 'smooth',
        width: 2
      },
      xaxis: {
        categories: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
      },
      yaxis: {
        title: {
          text: 'Nombre de colis'
        }
      }
    };
    var chart = new ApexCharts(document.querySelector('#visitor-chart'), options);
    chart.render();
  })();

  // Graphique de statistiques par mois (existant)
  (function () {
    var options1 = {
      chart: {
        height: 450,
        type: 'area',
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      colors: ['#1890ff', '#13c2c2'],
      series: [{
        name: 'Livraisons',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 86, 115, 35]
      }, {
        name: 'Ramassages',
        data: [110, 60, 150, 35, 60, 36, 26, 45, 65, 52, 53, 41]
      }],
      stroke: {
        curve: 'smooth',
        width: 2
      },
      xaxis: {
        categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
      },
      yaxis: {
        title: {
          text: 'Nombre de colis'
        }
      }
    };
    var chart = new ApexCharts(document.querySelector('#visitor-chart-1'), options1);
    chart.render();
  })();

  // Graphique Aperçu des Revenus (avec données dynamiques)
  (function () {
    // Préparer les données pour le graphique des revenus
    const jours = apercuRevenus.map(item => item.jour);
    const revenus = apercuRevenus.map(item => Math.round((item.revenus || 0) / 1000)); // Diviser par 1000 et arrondir
    
    var options = {
      chart: {
        type: 'bar',
        height: 365,
        toolbar: {
          show: false
        }
      },
      colors: ['#13c2c2'],
      plotOptions: {
        bar: {
          columnWidth: '45%',
          borderRadius: 4
        }
      },
      dataLabels: {
        enabled: false
      },
      series: [{
        name: 'Revenus (k FCFA)',
        data: revenus
      }],
      stroke: {
        curve: 'smooth',
        width: 2
      },
      xaxis: {
        categories: jours,
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        show: false
      },
      grid: {
        show: false
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val.toLocaleString('fr-FR') + "k FCFA"
          }
        }
      }
    };
    var chart = new ApexCharts(document.querySelector('#income-overview-chart'), options);
    chart.render();
  })();

  
  (function () {
    var options = {
      chart: {
        type: 'line',
        height: 340,
        toolbar: {
          show: false
        }
      },
      colors: ['#faad14'],
      plotOptions: {
        bar: {
          columnWidth: '45%',
          borderRadius: 4
        }
      },
      stroke: {
        curve: 'smooth',
        width: 1.5
      },
      grid: {
        strokeDashArray: 4
      },
      series: [{
        data: [58, 90, 38, 83, 63, 75, 35, 55]
      }],
      xaxis: {
        type: 'datetime',
        categories: [
          '2018-05-19T00:00:00.000Z',
          '2018-06-19T00:00:00.000Z',
          '2018-07-19T01:30:00.000Z',
          '2018-08-19T02:30:00.000Z',
          '2018-09-19T03:30:00.000Z',
          '2018-10-19T04:30:00.000Z',
          '2018-11-19T05:30:00.000Z',
          '2018-12-19T06:30:00.000Z'
        ],
        labels: {
          format: 'MMM'
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        show: false
      },
    };
    var chart = new ApexCharts(document.querySelector('#analytics-report-chart'), options);
    chart.render();
  })();
  // Rapport des Ventes (avec données dynamiques de la semaine)
  (function () {
    // Utiliser les données des revenus de la semaine
    const jours = apercuRevenus.map(item => item.jour);
    const revenus = apercuRevenus.map(item => Math.round((item.revenus || 0) / 1000)); // En milliers, arrondi
    const profits = apercuRevenus.map(item => Math.round(((item.revenus || 0) * 0.7) / 1000)); // Simuler profit à 70%, arrondi
    
    var options = {
      chart: {
        type: 'bar',
        height: 430,
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          columnWidth: '30%',
          borderRadius: 4
        }
      },
      stroke: {
        show: true,
        width: 8,
        colors: ['transparent']
      },
      dataLabels: {
        enabled: false
      },
      legend: {
        position: 'top',
        horizontalAlign: 'right',
        show: true,
        fontFamily: `'Public Sans', sans-serif`,
        offsetX: 10,
        offsetY: 10,
        labels: {
          useSeriesColors: false
        },
        markers: {
          width: 10,
          height: 10,
          radius: '50%',
          offsexX: 2,
          offsexY: 2
        },
        itemMargin: {
          horizontal: 15,
          vertical: 5
        }
      },
      colors: ['#faad14', '#1890ff'],
      series: [{
        name: 'Profit Net (k FCFA)',
        data: profits
      }, {
        name: 'Revenus (k FCFA)',
        data: revenus
      }],
      xaxis: {
        categories: jours
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val.toLocaleString('fr-FR') + "k FCFA"
          }
        }
      }
    }
    var chart = new ApexCharts(document.querySelector('#sales-report-chart'), options);
    chart.render();
  })();
}

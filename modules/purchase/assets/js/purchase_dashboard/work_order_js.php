<script>
(function($) {
  "use strict";
})(jQuery);

var pieChartPOStatus;
var lineChartPOTrends;
var barChartPOVsTax;

get_purchase_order_dashboard();

function get_purchase_order_dashboard() {
  "use strict";

  var data = {
    vendors: $('select[name="vendors"]').val(),
    group_pur: $('select[name="group_pur"]').val(),
    kind: $('select[name="kind"]').val(),
    from_date: $('input[name="from_date"]').val(),
    to_date: $('input[name="to_date"]').val()
  };

  $.post(admin_url + 'purchase/get_work_order_dashboard', data).done(function(response){
    response = JSON.parse(response);

    // Update value summaries
    $('.total_po_value').text(response.total_po_value);
    $('.approved_po_value').text(response.approved_po_value);
    $('.draft_po_value').text(response.draft_po_value);

    // PIE CHART - Approval Status
    var pieCtx = document.getElementById('pieChartForPOApprovalStatus').getContext('2d');
    var pieData = [response.approved_po_count, response.draft_po_count, response.rejected_po_count];

    if (pieChartPOStatus) {
      pieChartPOStatus.data.datasets[0].data = pieData;
      pieChartPOStatus.update();
    } else {
      pieChartPOStatus = new Chart(pieCtx, {
        type: 'pie',
        data: {
          labels: ['Approved', 'Draft', 'Rejected'],
          datasets: [{
            data: pieData,
            backgroundColor: [
              'rgba(75, 192, 192, 0.7)',
              'rgba(255, 206, 86, 0.7)',
              'rgba(255, 99, 132, 0.7)'
            ],
            borderColor: [
              'rgba(75, 192, 192, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    }

    // LINE CHART - WO Trends Over Time
    var lineCtx = document.getElementById('lineChartPOTrendsOverTime').getContext('2d');

    if (lineChartPOTrends) {
      lineChartPOTrends.data.labels = response.line_order_date;
      lineChartPOTrends.data.datasets[0].data = response.line_order_total;
      lineChartPOTrends.update();
    } else {
      lineChartPOTrends = new Chart(lineCtx, {
        type: 'line',
        data: {
          labels: response.line_order_date,
          datasets: [{
            label: 'WO Value',
            data: response.line_order_total,
            fill: false,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: 'bottom'
            },
            tooltip: {
              mode: 'index',
              intersect: false
            }
          },
          scales: {
            x: {
              title: {
                display: true,
                text: 'Order Date'
              }
            },
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'WO Value'
              }
            }
          }
        }
      });
    }

    // COLUMN CHART - WO vs Tax Value
    var barCtx = document.getElementById('barChartPOVsTax').getContext('2d');
    var barData = {
      labels: response.column_po_labels,
      datasets: [
        {
          label: 'WO Value',
          data: response.column_po_value,
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        },
        {
          label: 'Tax Value',
          data: response.column_po_tax,
          backgroundColor: 'rgba(255, 159, 64, 0.7)',
          borderColor: 'rgba(255, 159, 64, 1)',
          borderWidth: 1
        }
      ]
    };

    if (barChartPOVsTax) {
      barChartPOVsTax.data.labels = barData.labels;
      barChartPOVsTax.data.datasets[0].data = barData.datasets[0].data;
      barChartPOVsTax.data.datasets[1].data = barData.datasets[1].data;
      barChartPOVsTax.update();
    } else {
      barChartPOVsTax = new Chart(barCtx, {
        type: 'bar',
        data: barData,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          },
          scales: {
            x: {
              title: {
                display: false,
                text: 'Order Date'
              }
            },
            y: {
              beginAtZero: true,
              title: {
                display: false,
                text: 'Amount'
              }
            }
          }
        }
      });
    }

    // PIE CHART - Tax Distribution by Budget
    var taxPieCtx = document.getElementById('pieChartForTaxByBudget').getContext('2d');
    var taxData = response.pie_tax_value;
    var budgetLabels = response.pie_budget_name;

    if (window.taxByBudgetChart) {
      taxByBudgetChart.data.labels = budgetLabels;
      taxByBudgetChart.data.datasets[0].data = taxData;
      taxByBudgetChart.update();
    } else {
      window.taxByBudgetChart = new Chart(taxPieCtx, {
        type: 'pie',
        data: {
          labels: budgetLabels,
          datasets: [{
            data: taxData,
            backgroundColor: budgetLabels.map((_, i) => `hsl(${i * 35 % 360}, 70%, 60%)`),
            borderColor: '#fff',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.label + ': ' + context.formattedValue;
                }
              }
            }
          }
        }
      });
    }

    // BAR CHART - Top 10 Vendors by WO Value
    var vendorBarCtx = document.getElementById('barChartTopVendors').getContext('2d');
    var vendorLabels = response.bar_top_vendor_name;
    var vendorData = response.bar_top_vendor_value;

    if (window.barTopVendorsChart) {
      barTopVendorsChart.data.labels = vendorLabels;
      barTopVendorsChart.data.datasets[0].data = vendorData;
      barTopVendorsChart.update();
    } else {
      window.barTopVendorsChart = new Chart(vendorBarCtx, {
        type: 'bar',
        data: {
          labels: vendorLabels,
          datasets: [{
            label: 'WO Value',
            data: vendorData,
            backgroundColor: 'rgba(153, 102, 255, 0.7)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            x: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'WO Value'
              }
            },
            y: {
              ticks: {
                autoSkip: false
              },
              title: {
                display: true,
                text: 'Vendors'
              }
            }
          }
        }
      });
    }

    // PIE CHART - Top 10 Vendors by WO Value
    var vendorPieCtx = document.getElementById('pieChartTopVendors').getContext('2d');
    var vendorPieLabels = response.bar_top_vendor_name;
    var vendorPieData = response.bar_top_vendor_value;

    if (window.vendorPieChart) {
      vendorPieChart.data.labels = vendorPieLabels;
      vendorPieChart.data.datasets[0].data = vendorPieData;
      vendorPieChart.update();
    } else {
      window.vendorPieChart = new Chart(vendorPieCtx, {
        type: 'pie',
        data: {
          labels: vendorPieLabels,
          datasets: [{
            data: vendorPieData,
            backgroundColor: vendorPieLabels.map((_, i) => `hsl(${i * 36}, 70%, 60%)`),
            borderColor: '#fff',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.label + ': ' + context.formattedValue;
                }
              }
            }
          }
        }
      });
    }

  });
}
</script>

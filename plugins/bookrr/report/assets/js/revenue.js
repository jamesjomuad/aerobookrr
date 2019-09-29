

// Revenue
$(function () {
    if($('#revenueTotal').length){
        var ctxB = document.getElementById("revenueTotal").getContext('2d');
        var BarChart = new Chart(ctxB, {
            type: 'bar',
            data: {
                labels: ["April", "March", "February", "January", "December", "November", "October", "September", "August", "July", "June", "May"],
                datasets: [{
                    label: 'Monthly Total Revenue',
                    data: [100, 300, 400, 800, 220, 250, 500, 650, 500, 750, 800, 900],
                    backgroundColor: [
                        'rgba(100, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(100, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(100, 159, 64, 0.2)',
                        'rgba(100, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(100, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(100, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(100,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(100, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(100, 159, 64, 1)',
                        'rgba(100,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(100, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(100, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        BarChart.aspectRatio = 0;
    }

    if($('#revenueSales').length){
        var ctxL = document.getElementById("revenueSales").getContext('2d');
        var myLineChart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                datasets: [{
                    label: "Weekly Check In/Out",
                    data: [28, 48, 40, 19, 86, 100, 90],
                    backgroundColor: ['rgba(0, 137, 132, .2)', ],
                    borderColor: [
                        'rgba(0, 10, 130, .7)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true
            }
        });
    } 

    if($('#revenueDividend').length){
        var ctxD = document.getElementById("revenueDividend").getContext('2d');
        var myLineChart = new Chart(ctxD, {
            type: 'doughnut',
            data: {
                labels: ["Aero", "Aeroparks", "Marketing", "Staff", "Misc"],
                datasets: [{
                    data: [300, 120, 100, 40, 50],
                    backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],
                    hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]
                }]
            },
            options: {
                responsive: true
            }
        });
    }

    if($('#revenueRent').length){
        var ctxL = document.getElementById("revenueRent").getContext('2d');
        var myLineChart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: ["April", "March", "February", "January", "December", "November", "October", "September", "August", "July", "June", "May"],
                datasets: [{
                    label: "Monthly Check In/Out",
                    data: [28, 48, 40, 19, 86, 100, 90, 55, 100, 250, 350, 120],
                    backgroundColor: ['rgba(0, 137, 132, .2)', ],
                    borderColor: [
                        'rgba(0, 10, 130, .7)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true
            }
        });
    }

    if($('#revenueExpense').length){
        new Chart(document.getElementById("revenueExpense"), {
            "type": "horizontalBar",
            "data": {
              "labels": ["Medical", "Food", "Transportation", "Clothing", "Utilities", "Debt", "Misc"],
              "datasets": [{
                "label": "Expenses",
                "data": [22, 33, 55, 12, 86, 23, 14],
                "fill": false,
                "backgroundColor": ["rgba(255, 99, 132, 0.2)", "rgba(255, 159, 64, 0.2)",
                  "rgba(255, 205, 86, 0.2)", "rgba(75, 192, 192, 0.2)", "rgba(54, 162, 235, 0.2)",
                  "rgba(153, 102, 255, 0.2)", "rgba(201, 203, 207, 0.2)"
                ],
                "borderColor": ["rgb(255, 99, 132)", "rgb(255, 159, 64)", "rgb(255, 205, 86)",
                  "rgb(75, 192, 192)", "rgb(54, 162, 235)", "rgb(153, 102, 255)", "rgb(201, 203, 207)"
                ],
                "borderWidth": 1
              }]
            },
            "options": {
              "scales": {
                "xAxes": [{
                  "ticks": {
                    "beginAtZero": true
                  }
                }]
              }
            }
          });
    }
});

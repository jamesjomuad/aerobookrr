

// Insights
$(function () {
    if($('#WeeklySales').length){
        var ctxB = document.getElementById("WeeklySales").getContext('2d');
        var myBarChart = new Chart(ctxB, {
            type: 'bar',
            data: {
                labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                datasets: [{
                    label: 'Weekly Sales',
                    data: [100, 300, 250, 200, 220, 250, 350, 380, 290, 100, 111, 112],
                    backgroundColor: [
                        'rgba(150, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(150,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)'
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
    }
    if($('#MonthlySales').length){
        var ctxB = document.getElementById("MonthlySales").getContext('2d');
        var myBarChart = new Chart(ctxB, {
            type: 'bar',
            data: {
                labels: ["April", "March", "February", "January", "December", "November", "October", "September", "August", "July", "June", "May"],
                datasets: [{
                    label: 'Monthly Sales',
                    data: [100, 300, 250, 200, 220, 250, 350, 380, 290, 100, 111, 112],
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
    } 
    if($('#WeeklyInOut').length){
        var ctxL = document.getElementById("WeeklyInOut").getContext('2d');
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
    if($('#MonthlyInOut').length){
        var ctxL = document.getElementById("MonthlyInOut").getContext('2d');
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
});

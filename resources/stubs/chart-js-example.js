var config = {
    type: "line",
    data: {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [
            {
                label: "My First dataset",
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                fill: false,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
            },
            {
                label: "My Second dataset",
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                fill: false,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
            }]
    },
    options: {
        responsive: true,
        title: {
            display: true,
            text: "Chart.js Line Chart - Logarithmic"
        },
        scales: {
            xAxes: [{
                display: true,
            }],
            yAxes: [{
                display: true,
                type: "logarithmic",
            }]
        }
    }
};

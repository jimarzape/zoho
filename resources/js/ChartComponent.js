// Import Alpine.js
import Alpine from "alpinejs";
// Import Chart.js if you're bundling it with your app, otherwise, you can link it via CDN in your blade template.
import Chart from "chart.js/auto";

window.chart = () => {
    return {
        init() {
            var ctx = document.getElementById("salesChart").getContext("2d");
            var myChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: [
                        "January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                    ],
                    datasets: [
                        {
                            label: "Sales",
                            data: [65, 59, 80, 81, 56, 55, 40],
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        },
    };
};

// Register the component with Alpine.js
Alpine.data("chart", window.chart);

// Start Alpine.js
window.Alpine = Alpine;
Alpine.start();

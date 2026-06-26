<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'db_config.php';
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <div class="home-btn"><a href="admin_panel.php" class="back-link">← 返回後台管理系統</div>
    <title>營收概況</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container { width: 80%; margin: 50px auto; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <div class="chart-container">
        <canvas id="revenueChart"></canvas>
    </div>

    <script>
    fetch('get_chart_data.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.date);
            const values = data.map(item => item.daily_revenue);

            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '每日營收 (NT$)',
                        data: values,
                        backgroundColor: '#cd6143',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
    </script>
</body>
</html>
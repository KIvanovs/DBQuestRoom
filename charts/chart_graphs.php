

<?php
$pageTitle = 'Quest Room chart info';
include_once '../includes/header.php';
include '../charts/income_chart.php';

?>

<div style="margin-top: 20px;"></div>

<?php
include '../charts/popday_chart.php';
?>

<div style="margin-top: 20px;"></div>

<?php
include '../charts/popularity_chart.php';
?>

<button onclick="window.location.href='../charts/generate_pdf.php'">Скачать отчет в PDF</button>

<?php 
include_once '../includes/footer.php';
?>

<!-- попробовать использовать mpdf скачивать библу надо тут в терминале а не cmd -->
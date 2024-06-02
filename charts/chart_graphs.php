<?php
$pageTitle = 'Quest Room chart info';
include_once '../includes/header.php';

$selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('m');
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <form method="post" class="mb-3">
                <div class="mb-3 d-flex flex-row gap-2 align-items-end">
                    <div class="form-group flex-fill">
                        <label for="year">Select Year:</label>
                        <select name="year" id="year" class="form-control">
                            <?php
                            for ($i = 2020; $i <= date('Y'); $i++) {
                                echo "<option value='$i'" . ($i == $selectedYear ? ' selected' : '') . ">$i</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group flex-fill">
                        <label for="month">Select Month:</label>
                        <select name="month" id="month" class="form-control">
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                $monthNum = str_pad($i, 2, '0', STR_PAD_LEFT);
                                $monthName = date('F', mktime(0, 0, 0, $i, 10));
                                echo "<option value='$monthNum'" . ($monthNum == $selectedMonth ? ' selected' : '') . ">$monthName</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="action" value="filter">Filter</button>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" formaction="../charts/generate_pdf.php" class="btn btn-secondary w-100" name="action" value="download">Download PDF report</button>
                </div>
                <input type="hidden" name="selectedYear" value="<?php echo $selectedYear; ?>">
                <input type="hidden" name="selectedMonth" value="<?php echo $selectedMonth; ?>">
            </form>
        </div>
    </div>
</div>

<div style="margin-top: 20px;"></div>
<?php include '../charts/income_chart.php'; ?>

<div style="margin-top: 20px;"></div>
<?php include '../charts/popday_chart.php'; ?>

<div style="margin-top: 20px;"></div>
<?php include '../charts/popularity_chart.php'; ?>

<?php 
include_once '../includes/footer.php';
?>

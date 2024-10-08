<?php 
include('DB/database.php');
include('DB/employee.php');
include('DB/department.php');
include('DB/advance.php');


unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
include("check_session.php");

$employee = new employee($db);
$employees=$employee->Count();
$emp_salary_total=$employee->select("SELECT sum(basic_salary) AS total FROM employees");
$department= new department($db);
$departments=$department->Count();
$advance=new advance($db);
$advance_total=$advance->select("SELECT sum(amount) AS total FROM advances");
$TOP=$employee->select("SELECT  COUNT(employees.id) AS emp,departments.name FROM employees JOIN departments ON employees.department_id = departments.id Group by departments.name LIMIT 3");

$labels = [];
$data = [];
foreach ($TOP as $top3) {
    $labels[] = $top3['name']; 
    $data[] = (int)$top3['emp']; 
}

?>

<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>HR - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/bootstrap.css">
   
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include('Sidebar.html') ?>
        
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include('navbar.php') ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <ul  class="breadcrumb m-3">
                            <li class="breadcrumb-item active">الرئيسية </li> 
                        </ul>
                        <div class="d-flex">
                        <a href="add_employee.php" class="d-none d-sm-inline-block btn btn-sm btn-outline-success shadow-sm me-2">
                                <i class="fas fa-sm text-white-50"></i> إضافة موظف
                            </a>
                            <a href="Reports.php" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm me-2">
                                <i class="fas fa-download fa-sm text-white-50"></i> توليد تقرير
                            </a>
                            
                            <a href="add_resignation.php" class="d-none d-sm-inline-block btn btn-sm btn-outline-secondary shadow-sm">
                                <i class="fas fa-sm text-white-50"></i> طلب استقالة
                            </a>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                              <h4> الاقسام</h4> </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php foreach($departments as $department){echo $department['count'];} ?></div>
                                        </div>
                                        <div class="col-auto">
                                        <img src="img/corporate-culture.gif" alt="" width="80px" hight="80px" srcset="">
                                        
                                            <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                               <h4> الموظفين</h4></div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php foreach($employees as $employee){echo $employee['count'];} ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <img src="img/businessman.gif" alt="" width="80px" hight="80px" srcset="">
                                        <!-- <i class="fas fa-comments fa-2x text-gray-300"></i> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                <h4> السلف</h4>
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php foreach($advance_total as $total){echo $total['total']??'0';} ?></div>
                                                </div>
                                                <div class="col">
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                        <img src="img/advance.gif" alt="" width="80px" hight="80px" srcset="">
                                            <!-- <i class="fas fa-clipboard-list fa-2x text-gray-300"></i> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                <h4> الرواتب</h4></div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php foreach($emp_salary_total as $total){echo $total['total']??'0';} ?></div>
                                        </div>
                                        <div class="col-auto">
                                        
                                        <img src="img/salary.gif" alt="" width="80px" hight="80px" srcset="">
                                            <!-- <i class="fas fa-dollar-sign fa-2x text-gray-300"></i> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-secondary">Bar Chart</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-secondary"> اكبر 3 اقسام</h6>
                                    
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include('footer.html') ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <?php include("Scroll.html") ?>

    <!-- Logout Modal-->
   <?php include('Logout_model.html') ?>

    <!-- Bootstrap core JavaScript-->
    <?php include("script.html") ?>
    <script>
var labels = <?php echo json_encode($labels); ?>;
var data = <?php echo json_encode($data); ?>;

var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: labels, 
    datasets: [{
      data: data, 
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'], 
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: true 
    },
    cutoutPercentage: 80,
  },
});
</script>

</body>

</html>
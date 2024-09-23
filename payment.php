<?php 
include('DB/database.php');
include('DB/advance.php');
include('DB/payment.php');
include('Validattion/Validator.php');


unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
include("check_session.php");

if(!isset($_GET['id'])){
  header("location: home.php");  
}
$advance_id=$_GET['id'];
$advance=new advance($db);

$advanc=$advance->find($advance_id);
$advs=$advance->select("SELECT sum(payments.amount) AS total FROM advances JOIN payments ON advances.id = payments.advance_id WHERE advances.id=$advance_id");
$count=0;
foreach($advanc as $adv){
    $date=$adv['date'];
    $amount=$adv['amount'];
}
foreach($advs as $a){
    $total_payment=$a['total'];
}
$sum=(int)$amount-(int)$total_payment;
if(isset($_POST['pay'])){
    $amount=$_POST['amount'];
    if($amount>$sum){
        $message="المبلغ المدفوع اكبر من المتبقي";
    }
    else{
        $data=[
           'amount'=>$amount,
           'date'=>date_format(date_create(),'Y-m-d'),
           'advance_id'=>$advance_id
        ];
        $payment=new payment($db);
        $payment->Create($data);
        header("location: payment.php?id=$advance_id");

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/bootstrap.css">
   
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    

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

                    <ul  class="breadcrumb m-3">
                        <li class="breadcrumb-item"> <a href="home.php" class='text-success'>الرئيسية</a></li> 
                        <li class="breadcrumb-item"> <a href="advance.php" class='text-success'>السلف</a></li> 
                        <li class="breadcrumb-item active">تسديد سلفة</li> 
                    </ul>
                    <div class="card shadow mb-4">
                                <div class="card-body">
                                <h3 class="mb-2 mt-3 text-gray-800">تسديد سلفه</h3>
                                    <div class="table-responsive" style="max-height: 200px; min-height: 200px; overflow-y: auto;">
                                        <table class="table table-bordered border-bottom-success" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center bg-gray-200">المبلغ</th>
                                                    <th class="text-center bg-gray-200">تاريخ</th>
                                                    <th class="text-center bg-gray-200">المتبقي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="text-center"><?=$amount?></th>
                                                    <th class="text-center"><?=$date?></th>
                                                    <th class="text-center"><?=$sum?></th>
                                                </tr>
                                                <tr>
                                                <form action="" method="post">
                                                <div class="form-group">
                                                    <th colspan="2" class="text-center"> 
                                                         <?php if(isset($message)){
                                                          ?>     
                                                        <label for="amount"><span class="text-danger"><?=$message?></span></label>
                                                          <?php }?>           
                                                        <input  type="number" placeholder="المبلغ " class="form-control" value="" id="amount" name="amount" required>
                                                    </th>
                                                    <th><input class="btn btn-outline-success" name="pay" type="submit" value="تسديد"></th>
                                                    </div>
                                                </form>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    
                    
                    <!-- DataTales Example -->
                    
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php include("footer.html") ?>
    <!-- End of Footer -->

    <!-- Scroll to Top Button-->
    <?php include("Scroll.html") ?>

    <!-- Logout Modal-->
    <?php include("Logout_model.html") ?>

    <!-- Bootstrap core JavaScript-->
    <?php include("script.html") ?>
</body>

</html>
<?php 
include('DB/database.php');
include('DB/employee.php');
include('DB/allowance.php');
include('DB/jop.php');
include('DB/department.php');
include('Validattion/Validator.php');

$database = new Database();
$db = $database->connect();
include("check_session.php");
if(isset($_POST['save'])) {
    $data=[
        'type1'=>$_POST['type1'],
        'allowance1'=>$_POST['allowance1'],
        'type2'=>$_POST['type2'],
        'allowance2'=>$_POST['allowance2'],
    ];
    $rules=[];
    $validation= new Validator($db);
    if($validation->validate($data,$rules)){
        $_SESSION['allowances'] = $data;
        header("Location: add_employee3.php");
        exit; 
    }
    else{
        $validation=$validation->errors(); 
    }


}
if(isset($_GET['edit'])){
    header("location: add_employee.php");
}
if(isset($_SESSION['allowances'])){
    $allowances=$_SESSION['allowances'];
    unset($_SESSION['allowances']);
}
if(!isset($_SESSION['data_basic'])){
    header("location: add_employee.php");
}
$data = $_SESSION['data_basic'];
$department=new department($db);
$departments=$department->find($data['department']);
$jops=new jop($db);
$jop=$jops->find($data['jop']);
$allowance_type=new allowance($db);
$allowance_types=$allowance_type->All();
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

    <style>
    .custom-row {margin: 0; }
    .custom-col {padding: 0.2rem; }
    </style>

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
                    <ul  class="breadcrumb m-3">
                            <li class="breadcrumb-item"> <a href="home.php" class='text-success'>الرئيسية</a></li> 
                            <li class="breadcrumb-item "><a href="Employee.php" class='text-success'>الموظفين</a> </li>
                            <li class="breadcrumb-item active">إضافة موظف جديد </li> 
                         </ul>

                    <div >
                    <h3 class="text-center">بيانات الموظف الاساسية</h3>
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-3 text-center">
                            <img id="profileImage" src="<?=$data['image']?>" alt="صورة الموظف" class="img-thumbnail" width="200" height="200">
                        </div>
                    <div class="col-md-8">
                        <div class="row">
            <div class="col-md-6 custom-col">  
                <p><strong>الاسم:</strong> <?=$data['name']?> </p>     
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>رقم الهوية:</strong> <?=$data['divinity_no']?></p>
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>تاريخ الميلاد:</strong> <?=$data['birthdate']?> </p>
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>رقم التواصل:</strong> <?=$data['phone']?> </p>
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>العنوان:</strong> <?=$data['address']?></p>
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>الراتب الأساسي:</strong> <?=$data['basic_salary']?> </p>
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>الجنس:</strong> <?php echo $data['sex'] == true ? 'ذكر' : 'أنثى'; ?> </p>
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>القسم:</strong> <?php foreach($departments as $dep){echo $dep['name'];} ?> </p>
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>الوظيفة:</strong> <?php foreach($jop as $j){echo $j['name'];} ?> </p>
            </div>
            <div class="col-md-6 custom-col">
                <p><strong>تعديل البيانات:</strong> <a href="add_employee2.php?edit=".true >تعديل</a> </p>
            </div>
        </div>
    </div>
</div>
</div>
<h3 class="text-center"> إضافة بدلات للموظف</h3>
<form action="add_employee2.php" method="POST">
    <div class="row">
        <div class="col-md-6">
            <label for="type1">النوع:</label>
            <select class="form-control" id="type1" name="type1">
                <option value="">اختر نوع البدل</option>
                <?php foreach($allowance_types as $type){  ?>
                <?php if($type['id']==$allowances['type1']){ ?>
                <option selected value="<?=$type['id']?>"> <?=$type['name']?></option>
                <?php } else{?>
                <option value="<?=$type['id']?>"> <?=$type['name']?></option>
                <?php }}?>
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">المقدار :</label>
                <input type="text" value="<?=$allowances['allowance1']??''?>" class="form-control" id="allowance1" name="allowance1">
            </div>
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="type2">النوع:</label>
            <select class="form-control" id="type2" name="type2">
                <option value="">اختر نوع البدل</option>
                <?php foreach($allowance_types as $type){  ?>
                <?php if($type['id']==$allowances['type2']){ ?>
                <option selected value="<?=$type['id']?>"> <?=$type['name']?></option>
                <?php } else{?>
                <option value="<?=$type['id']?>"> <?=$type['name']?></option>
                <?php }}?>
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">المقدار :</label>
                <input type="text" value="<?=$allowances['allowance2']??''?>" class="form-control" id="allowance2" name="allowance2" >
            </div>
        </div>
    </div>
        <button type="submit" name="save" class="btn btn-outline-success">إضافة البدلات</button>
    </form>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("footer.html") ?>
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
    document.querySelector('.custom-file-input').addEventListener('change', function (event) {
        const fileName = event.target.files[0]?.name || 'تصفح';
        const label = event.target.nextElementSibling;
        label.textContent = fileName;
    });
</script>
</body>
</html>
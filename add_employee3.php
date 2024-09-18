<?php 
include('DB/database.php');
include('DB/employee.php');
include('DB/allowance.php');
include('DB/file.php');
include('DB/jop.php');
include('DB/department.php');
include('DB/employee_file.php');
include('DB/allowance_employee.php');
include('Validattion/Validator.php');

session_start();

$database = new Database();
$db = $database->connect();

if(isset($_GET['edit'])){
    header("location: add_employee2.php");
}
if(!isset($_SESSION['allowances'])){
    header("location: add_employee2.php");
}
$allowances = $_SESSION['allowances'];
$data_basic = $_SESSION['data_basic'];
if(isset($_POST['save'])) {
    $data=[
        'type1'=>$_POST['type1'],
        'type2'=>$_POST['type2'],
        'type3'=>$_POST['type3'],
    ];
    $employee=new employee($db);
    $employee_id=$employee->Create($data_basic);
    $allowances['employee_id']=$employee_id;
    $allowance_employee=new allowance_employee($db);
    if($allowances['type1']!='' and $allowances['type2']!=''){
        $allowance_employee->CreateAll($allowances);
    }
    elseif($allowances['type1']!=''){
        $allowance_employee->Create($allowances);
    }
    $data['employee_id']=$employee_id;
    $employee_file=new employee_file($db);
    if($data['type1']!='' and $data['type2']!='' and $data['type3']!=''){
        $path='Upload/'.random_int(999,99999).$_FILES['attachment1']['name'];
        move_uploaded_file($_FILES['attachment1']['tmp_name'],$path);
        $data['path1']=$path;
        $path='Upload/'.random_int(999,99999).$_FILES['attachment2']['name'];
        move_uploaded_file($_FILES['attachment2']['tmp_name'],$path);
        $data['path2']=$path;
        $path='Upload/'.random_int(999,99999).$_FILES['attachment3']['name'];
        move_uploaded_file($_FILES['attachment3']['tmp_name'],$path);
        $data['path3']=$path;
        $employee_file->CreateAll($data);
    }
    elseif($data['type1']!='' and $data['type2']!=''){
        $path='Upload/'.random_int(999,99999).$_FILES['attachment1']['name'];
        move_uploaded_file($_FILES['attachment1']['tmp_name'],$path);
        $data['path1']=$path;
        $path='Upload/'.random_int(999,99999).$_FILES['attachment2']['name'];
        move_uploaded_file($_FILES['attachment2']['tmp_name'],$path);
        $data['path2']=$path;
        $employee_file->CreateAll($data);
    }
    elseif($data['type1']!=''){
        $path='Upload/'.random_int(999,99999).$_FILES['attachment1']['name'];
        move_uploaded_file($_FILES['attachment1']['tmp_name'],$path);
        $employee_file->Create($data);
    }
    var_dump($data);
    exit;
}
$department=new department($db);
$departments=$department->find($data_basic['department']);
$jops=new jop($db);
$jop=$jops->find($data_basic['jop']);
$allowance_type=new allowance($db);
$file_type= new file($db);
$file_types=$file_type->All();
$type1=$allowances['type1'];
$type2=$allowances['type2'];
if($type1 !=''){
    $type1=$allowance_type->find($allowances['type1']);
}
if($type2 !=''){
    $type2=$allowance_type->find($allowances['type2']);
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
               <?php include('navbar.html') ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <ul  class="breadcrumb m-3">
                        <li class="breadcrumb-item"> <a href="home.php">الرئيسية</a></li> 
                        <li class="breadcrumb-item "><a href="Employee.php">الموظفين</a> </li>
                        <li class="breadcrumb-item active">إضافة موظف جديد </li> 
                    </ul>

                    
                    <h3 class="text-center">بيانات الموظف الاساسية</h3>
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-3 text-center">
                            <img id="profileImage" src="<?=$data_basic['image']?>" alt="صورة الموظف" class="img-thumbnail" width="200" height="200">
                        </div>
                            <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 custom-col">  
                                    <p><strong>الاسم:</strong> <?=$data_basic['name']?> </p>     
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>رقم الهوية:</strong> <?=$data_basic['divinity_no']?></p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>تاريخ الميلاد:</strong> <?=$data_basic['birthdate']?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>رقم التواصل:</strong> <?=$data_basic['phone']?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>العنوان:</strong> <?=$data_basic['address']?></p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>الراتب الأساسي:</strong> <?=$data_basic['basic_salary']?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>الجنس:</strong> <?php echo $data_basic['sex'] == true ? 'ذكر' : 'أنثى'; ?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>القسم:</strong> <?php foreach($departments as $dep){echo $dep['name'];} ?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>الوظيفة:</strong> <?php foreach($jop as $j){echo $j['name'];} ?> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-center">بدلات الموظف:  </h3>
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-3 text-center">
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 custom-col">
                                    <p><strong><?php if($type1!=''){ foreach($type1 as $type){echo $type['name'];}}?> :</strong> <?= $allowances['allowance1'] ??'' ?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong> <?php if($type2!=''){ foreach($type2 as $type){echo $type['name'];}} ?> :</strong> <?= $allowances['allowance2']??'' ?> </p>
                                </div>
                                <div class="col-md-6 custom-col mx-auto">
                                    <p><strong>تعديل البيانات:</strong> <a href="add_employee3.php?edit=".true >تعديل</a> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-center"> إضافة مرفقات للموظف</h3>
                    <form action="add_employee3.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label for="type1">النوع:</label>
                                <select class="form-control" id="type1" name="type1">
                                    <option value="">اختر نوع المرفق</option>
                                    <?php foreach($file_types as $type){  ?>
                                    <option value="<?=$type['id']?>"> <?=$type['type']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image"> المرفق : </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="attachment1" name="attachment1">
                                        <label class="custom-file-label" for="image">رفع الملف </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="type1">النوع:</label>
                                <select class="form-control" id="type2" name="type2">
                                    <option value="">اختر نوع المرفق</option>
                                    <?php foreach($file_types as $type){  ?>
                                    <option value="<?=$type['id']?>"> <?=$type['type']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image"> المرفق : </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="attachment2" name="attachment2" >
                                        <label class="custom-file-label" for="image">رفع الملف </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="type1">النوع:</label>
                                <select class="form-control" id="type3" name="type3">
                                    <option value="">اختر نوع المرفق</option>
                                    <?php foreach($file_types as $type){  ?>
                                    <option value="<?=$type['id']?>"> <?=$type['type']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image"> المرفق : </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="attachment3" name="attachment3">
                                        <label class="custom-file-label" for="image">رفع الملف </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="save" class="btn btn-primary">رفع جميع المرفقات</button>
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
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include('Logout_model.html') ?>

    <!-- Bootstrap core JavaScript-->
   <?php include("script.html") ?>
    <script>
    // تحديث نص التسمية عند اختيار ملف
    document.querySelectorAll('.custom-file-input')[0].addEventListener('change', function (event) {
        var fileName = event.target.files[0]?.name || 'تصفح';
        var label = event.target.nextElementSibling;
        label.textContent = fileName;
    });
    document.querySelectorAll('.custom-file-input')[1].addEventListener('change', function (event) {
        var fileName = event.target.files[0]?.name || 'تصفح';
        var label = event.target.nextElementSibling;
        label.textContent = fileName;
    });
     document.querySelectorAll('.custom-file-input')[2].addEventListener('change', function (event) {
        var fileName = event.target.files[0]?.name || 'تصفح';
        var label = event.target.nextElementSibling;
        label.textContent = fileName;
    });
</script>

</body>


</html>
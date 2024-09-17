<?php 
include('DB/database.php');
include('DB/employee.php');
include('DB/file_type.php');
include('DB/jop.php');
include('DB/department.php');

$database = new Database();
$db = $database->connect();
session_start();
if(!isset($_SESSION['data'])){
    header("location: add_employee.php");
}
$data = $_SESSION['data'];
unset($_SESSION['data']);
$department=new department($db);
$departments=$department->find($data['department']);
$jops=new jop($db);
$jop=$jops->find($data['jop']);
$file_type=new file_type($db);
$files_type=$file_type->All();
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
                <p><strong>تعديل البيانات:</strong> <a href="" >تعديل</a> </p>
            </div>
            
        </div>
    </div>
</div>
</div>
<h3 class="text-center"> إضافة المرفقات</h3>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="image">البطاقة الشخصية  : <span class="text-danger">*</span></label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image[]" accept="image/*" required multiple>
                    <label class="custom-file-label" for="image">اختر ملف المرفق</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="department">نوع المرفق: <span class="text-danger">*</span></label>
                <select class="form-control" id="department" name="department[]" required>
                    <option value="">اختر نوع المرفق</option>
                    <?php foreach($files_type as $file_type) { ?>
                    <option value="<?= htmlspecialchars($file_type['id']) ?>"> <?= htmlspecialchars($file_type['type']) ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="image">السيرة الداتية  : </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image[]" accept="image/*" required multiple>
                    <label class="custom-file-label" for="image">اختر ملف المرفق</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="department">نوع المرفق: </label>
                <select class="form-control" id="department" name="department[]" required>
                    <option value="">اختر نوع المرفق</option>
                    <?php foreach($files_type as $file_type) { ?>
                    <option value="<?= htmlspecialchars($file_type['id']) ?>"> <?= htmlspecialchars($file_type['type']) ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="image">اخرى  :</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image[]" accept="image/*" required multiple>
                    <label class="custom-file-label" for="image">اختر ملف المرفق</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="department">نوع المرفق: </label>
                <select class="form-control" id="department" name="department[]" required>
                    <option value="">اختر نوع المرفق</option>
                    <?php foreach($files_type as $file_type) { ?>
                    <option value="<?= htmlspecialchars($file_type['id']) ?>"> <?= htmlspecialchars($file_type['type']) ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="image">اخرى  : </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image[]" accept="image/*" required multiple>
                    <label class="custom-file-label" for="image">اختر ملف المرفق</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="department">نوع المرفق: </label>
                <select class="form-control" id="department" name="department[]" required>
                    <option value="">اختر نوع المرفق</option>
                    <?php foreach($files_type as $file_type) { ?>
                    <option value="<?= htmlspecialchars($file_type['id']) ?>"> <?= htmlspecialchars($file_type['type']) ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <button type="submit" name="save" class="btn btn-primary">إضافة مرفقات</button>
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
    document.querySelector('.custom-file-input').addEventListener('change', function (event) {
        const fileName = event.target.files[0]?.name || 'تصفح';
        const label = event.target.nextElementSibling;
        label.textContent = fileName;
    });
</script>

</body>


</html>
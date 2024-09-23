<?php
include('DB/database.php');
include('DB/employee.php');
include('DB/jop.php'); 
include('DB/department.php');
include('Validattion/Validator.php');
unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
if(!isset($_GET['id'])){
   header("location: home.php");
}
include("check_session.php");
$emp_id=$_GET['id'];
$employee=new employee($db);
$jop=new jop($db);
$department=new department($db);
if(isset($_POST['update'])){
    if(isset($_POST['image'])){

    }
    $data=[
    'name'=>$_POST['name'],
    'basic_salary'=>$_POST['salary'],
    'sex'=>$_POST['sex'],
    'start_date'=>$_POST['start_date'],
    'birthdate'=>$_POST['birthdate'],
    'phone'=>$_POST['phone'],
    'address'=>$_POST['address'],
    'image'=>$_POST['name'],
    'divinity_no'=>$_POST['divinity_no'],
    'department'=>$_POST['department'],
    'jop'=>$_POST['jop']
    ];

}
$emps=$employee->select("SELECT emp.*, dep.id AS dep_id, jop.id AS jop_id
FROM employees AS emp 
JOIN departments AS dep ON emp.department_id = dep.id
JOIN jops AS jop ON emp.jop_id = jop.id
WHERE emp.id = $emp_id");
$jops=$jop->All();
$departments=$department->All();
foreach($emps as $emp){
    $emp_id=$emp['id'];
    $name=$emp['name'];
    $basic_salary=$emp['basic_salary'];
    $sex=$emp['sex'];
    $birthday=$emp['birthday'];
    $phone=$emp['phone'];
    $address=$emp['address'];
    $image=$emp['imge'];
    $divinity_no=$emp['divinity_no'];
    $department_id=$emp['department_id'];
    $jop_id=$emp['jop_id'];
    $email=$emp['email'];
    $start_date=$emp['start_date'];
}

if(isset($_POST['update'])){
    if(!empty($_FILES['image']['name'])){
        $path='Upload/'.random_int(999,99999).$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],$path);
        if(file_exists($image)){
            unlink($image);
        }
    }
    else{
        $path=$_POST['img'];
    }
    $data=[
    'name'=>$_POST['name'],
    'basic_salary'=>$_POST['salary'],
    'sex'=>$_POST['sex'],
    'start_date'=>$_POST['start_date'],
    'birthdate'=>$_POST['birthdate'],
    'phone'=>$_POST['phone'],
    'address'=>$_POST['address'],
    'image'=>$path,
    'divinity_no'=>$_POST['divinity_no'],
    'department'=>$_POST['department'],
    'jop'=>$_POST['jop'],
    'email'=>$_POST['email']
    ];
    $rules=[
        'name'=>'required|full_name',
        'divinity_no'=>'required|',
        'email'=>'email',
        'basic_salary'=>'required',
        'department'=>'required',
        'sex'=>'required',
        'jop'=>'required',
        'birthdate'=>'required',
        'phone'=>'required|',
        'address'=>'required',
    ];
    $validation= new Validator($db);
   if($validation->validate($data,$rules)){
    $employee->Update($data,$emp_id);
    header("location: employee_details.php?id=$emp_id");
        
   } 
   else{
    $validation=$validation->errors(); 
    var_dump($validation);
    exit;
   }
   
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
                            <li class="breadcrumb-item active">تعديل بيانات الموظف  </li> 
                         </ul>
                    <h1 class="h3 mb-2 text-gray-800">المعلومات الاساسية </h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">الاسم: <span class="text-danger">*</span><span class="text-danger"><?php if(isset($validation['name'][0])){echo $validation['name'][0];}?></span></label>
                                    <input type="text" class="form-control" value="<?=$name?>" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">رقم الهوية : <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?=$divinity_no?>" id="divinity_no" name="divinity_no" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">البريد الإلكتروني:</label>
                                    <input type="email" class="form-control" value="<?=$email??''?>" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">الراتب الاساسي  : <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?=$basic_salary?>" id="salary" name="salary" required>
                                </div>
                            </div>
                        </div>

        <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                    <label for="department">القسم: <span class="text-danger">*</span></label>
                    <select class="form-control" id="department" name="department" required>
                        <option value="">اخترالقسم</option>
                        <?php foreach($departments as $department){?>
                            <?php if($department['id']==$department_id){ ?>
                        <option selected value="<?=$department['id']?>"> <?=$department['name']?></option>
                        <?php } else{?>
                            <option value="<?=$department['id']?>"> <?=$department['name']?></option>
                        <?php }}?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>الجنس: <span class="text-danger">*</span></label><br>
                    <div class="form-check form-check-inline">
                    <?php if( $sex == '1'){ ?>
                        <input class="form-check-input" type="radio" id="male" name="sex" value="1" required checked>
                        <?php } else{?>
                        <input class="form-check-input" type="radio" id="male" name="sex" value="1" required>
                        <?php }?>
                        <label class="form-check-label" for="male">ذكر</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <?php if( $sex == '0'){ ?>
                        <input class="form-check-input" type="radio" id="female" name="sex" value="0" required checked>
                        <?php } else{?>
                        <input class="form-check-input" type="radio" id="female" name="sex" value="0" required>
                        <?php }?>
                        <label class="form-check-label" for="female">أنثى</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="department">الوظيفة: <span class="text-danger">*</span></label>
                    <select class="form-control" id="department" name="jop" required>
                        <option value="">اختر الوظيفة</option>
                        <?php foreach($jops as $jop){?>
                        <?php if($jop['id']==$jop_id){ ?>
                        <option selected value="<?=$jop['id']?>"> <?=$jop['name']?></option>
                        <?php } else{?>
                            <option value="<?=$jop['id']?>"> <?=$jop['name']?></option>
                        <?php }}?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                    <label for="birthdate">تاريخ الميلاد: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="birthdate" value="<?=$birthday?>" name="birthdate" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">رقم التواصل : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="<?=$phone?>" id="phone" name="phone" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">العنوان  : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="<?=$address?>" id="address" name="address" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                        <div class="col-md-3 text-center">
                            <img id="profileImage" src="<?=$image?>" alt="صورة الموظف" class="img-thumbnail" width="200" height="200">
                            <input type="hidden" value="<?=$image?>" name="img">
                        </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="image">تعديل الصورة: <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                        <label class="custom-file-label" for="image">تعديل الصورة</label>
                    </div>
                </div>
            </div>

            <input type="hidden" name="start_date" value="<?=$start_date?>">
        </div>
        <button type="submit" name="update" class="btn btn-outline-success">تعديل بيانات الموظف</button>
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
    // تحديث نص التسمية عند اختيار ملف
    document.querySelector('.custom-file-input').addEventListener('change', function (event) {
        const fileName = event.target.files[0]?.name || 'تصفح';
        const label = event.target.nextElementSibling;
        label.textContent = fileName;
    });
</script>

</body>


</html>
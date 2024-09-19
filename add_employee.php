<?php
include('DB/database.php');
include('DB/department.php');
include('DB/jop.php');
include('DB/employee.php');
include('Validattion/Validator.php');
$database = new Database();
$db = $database->connect();
session_start();

if(isset($_POST['save'])) {
    $data=[
        'name'=>$_POST['name'],
        'divinity_no'=>$_POST['divinity_no'],
        'sex'=>$_POST['sex']=="1"?true:false,
        'start_date'=>date_format(date_create(),'Y-m-d'),
        'birthdate'=>$_POST['birthdate'],
        'phone'=>$_POST['phone'],
        'address'=>$_POST['address'],
        'imge'=>'',
        'basic_salary'=>$_POST['salary'],
        'department'=>$_POST['department'],
        'jop'=>$_POST['jop'],
        'email'=>$_POST['email'],
    ];
    $rules=[
        'name'=>'required',
        'divinity_no'=>'required',
        'email'=>'email',
        'basic_salary'=>'required',
        'department'=>'required',
        'sex'=>'required|boolean',
        'jop'=>'required',
        'birthdate'=>'required',
        'phone'=>'required',
        'address'=>'required',
    ];
    $validation= new Validator($db);
   if($validation->validate($data,$rules)){
        if($_FILES['image']['tmp_name']){
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $base64Image = base64_encode($imageData);
            $data['image'] = 'data:' . $_FILES['image']['type'] . ';base64,' . $base64Image;
        }else{
            $data['image'] = $_POST['img'] ;
        }
            
        session_start();
        $_SESSION['data_basic'] = $data;
        header("Location: add_employee2.php");
        exit; 
   } 
   else{
    $validation=$validation->errors(); 
   }
}
if(isset($_SESSION['data_basic'])){
    $data=$_SESSION['data_basic'];
    unset($_SESSION['data_basic']);
}
$jop=new jop($db);
$jops=$jop->All();
$department=new department($db);
$departments=$department->All();
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
                    <h1 class="h3 mb-2 text-gray-800">المعلومات الاساسية </h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">الاسم: <span class="text-danger">*</span><span class="text-danger"><?php if(isset($validation['name'][0])){echo $validation['name'][0];}?></span></label>
                                    <input type="text" class="form-control" value="<?=$data['name']??''?>" id="name" name="name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">رقم الهوية : <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?=$data['divinity_no']??''?>" id="email" name="divinity_no" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">البريد الإلكتروني:</label>
                                    <input type="email" class="form-control" value="<?=$data['email']??''?>" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">الراتب الاساسي  : <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?=$data['basic_salary']??''?>" id="salary" name="salary" required>
                                </div>
                            </div>
                        </div>

        <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                    <label for="department">القسم: <span class="text-danger">*</span></label>
                    <select class="form-control" id="department" name="department" >
                        <option value="">اخترالقسم</option>
                        <?php foreach($departments as $department){?>
                            <?php if($department['id']==$data['department']){ ?>
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
                    <?php if(isset($data['sex']) && $data['sex'] == '1'){ ?>
                        <input class="form-check-input" type="radio" id="male" name="sex" value="1" required checked>
                        <?php } else{?>
                        <input class="form-check-input" type="radio" id="male" name="sex" value="1" required>
                        <?php }?>
                        <label class="form-check-label" for="male">ذكر</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <?php if(isset($data['sex']) && $data['sex'] == '0'){ ?>
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
                        <?php if($jop['id']==$data['jop']){ ?>
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
                    <input type="date" class="form-control" id="birthdate" value="<?=$data['birthdate']??''?>" name="birthdate" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">رقم التواصل : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="<?=$data['phone']??''?>" id="phone" name="phone" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">العنوان  : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="<?=$data['address']??''?>" id="address" name="address" required>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if(isset($data['image'])){ ?>
            <div class="col-md-6">
                <div class="form-group">
                        <div class="col-md-3 text-center">
                            <img id="profileImage" src="<?=$data['image']?>" alt="صورة الموظف" class="img-thumbnail" width="200" height="200">
                            <input type="hidden" value="<?=$data['image']?>" name="img">
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
            <?php } else{ ?>
                <div class="col-md-12">
                <div class="form-group">
                    <label for="image">تحميل الصورة: <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image" accept="image/*" required>
                        <label class="custom-file-label" for="image">رفع ملف الصورة</label>
                    </div>
                </div>
            </div>
            <?php }?>

            
        </div>
        <button type="submit" name="save" class="btn btn-primary">إضافة موظف</button>
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
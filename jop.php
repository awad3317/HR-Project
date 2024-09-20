<?php 
include('DB/database.php');
include('DB/jop.php');
include('Validattion/Validator.php');
session_start();
unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
$jop=new jop($db);

if(isset($_GET['id'])){
$id= $_GET['id'];
$jop->delete($id);
}
if(isset($_POST['save'])){
    $data=[
    'name'=>$_POST['name'],
    ];
    $id=$jop->Create($data);
    $message = "تم إضافة الوظيفة بنجاح.";
}
$jops=$jop->All();
$count=0;
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
               <?php include('navbar.html') ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <ul  class="breadcrumb m-3">
                        <li class="breadcrumb-item"> <a href="home.php">الرئيسية</a></li> 
                        <li class="breadcrumb-item active">الوظائف  </li> 
                    </ul>
                    
                    <button id="add-job-btn" class="btn btn-primary">إضافة وظيفة</button>
                    <h3 class="mb-2 mt-3 text-gray-800">الوظائف</h3>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الوظيفة</th>
                                            <th>حذف</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($jops as $jop){ 
                                        $count++ ?>
                                        <tr>
                                            <td><?=$count?></td>
                                            <td><?=$jop['name']?></td>
                                            <td>
                                                <button class="btn btn-outline-danger delete-button" data-id="<?= $jop['id'] ?>">حذف</button>
                                            </td>
                                        </tr>
                                        <?php }?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
    <script>
       document.getElementById('add-job-btn').addEventListener('click', async () => {
    const { value: jobName } = await Swal.fire({
        title: 'إضافة اسم وظيفة',
        html: `
            <input id="swal-input" class="swal2-input" placeholder="اسم الوظيفة">
        `,
        focusConfirm: false,
        preConfirm: () => {
            return document.getElementById("swal-input").value;
        },
        confirmButtonText: 'إضافة', // تغيير نص الزر إلى "إضافة"
        customClass: {
            confirmButton: 'btn btn-success' // إضافة فئة Bootstrap
        }
    });

    if (jobName) {
        // إنشاء نموذج وإرساله
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href; // العودة إلى نفس الصفحة

        const inputName = document.createElement('input');
        inputName.type = 'hidden';
        inputName.name = 'name';
        inputName.value = jobName;
        form.appendChild(inputName);

        const inputSave = document.createElement('input');
        inputSave.type = 'hidden';
        inputSave.name = 'save';
        inputSave.value = 'true';
        form.appendChild(inputSave);

        document.body.appendChild(form);
        form.submit();
    }
});
    </script>
     <?php if (isset($message)): ?>
        <script>
             Swal.fire({
            title: '<?php echo isset($validationErrors) ? "فشل الإضافة!" : "تم الإضافة!"; ?>',
            text: '<?php echo isset($message) ? $message : ""; ?>',
            icon: '<?php echo isset($validationErrors) ? "error" : "success"; ?>',
            timer: 2000, 
            showConfirmButton: false
        });
        </script>
    <?php endif; ?>


</body>

</html>
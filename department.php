<?php 
include('DB/database.php');
include('DB/department.php');
include('Validattion/Validator.php');
session_start();
unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
$department=new department($db);

if(isset($_GET['id'])){
$id= $_GET['id'];
$department->delete($id);
}
if(isset($_POST['save'])){
    $data=[
    'name'=>$_POST['name'],
    'description'=>$_POST['description'],
    ];
    $id=$department->Create($data);
    $message = "تم إضافة القسم بنجاح.";
}
$departments=$department->All();
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
                        <li class="breadcrumb-item active">الاقسام  </li> 
                    </ul>  
                    <button id="add-department-btn" class="btn btn-primary">إضافة قسم</button>
                    <h1 class="h3 mb-2 mt-5 text-gray-800">الاقسام</h1>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>القسم</th>
                                        <th>تفاصيل</th>
                                        <th>حذف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($departments as $department){ 
                                    $count++ ?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td><?=$department['name']?></td>
                                        <td><?=$department['description']?></td>
                                        <td>
                                            <button class="btn btn-outline-danger delete-button" data-id="<?= $department['id'] ?>">حذف</button>
                                        </td>
                                    </tr>
                                    <?php }?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
                <!-- /.container-fluid -->

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
        document.getElementById('add-department-btn').addEventListener('click', async () => {
            const { value: formValues } = await Swal.fire({
                title: 'إضافة قسم',
                html: `
                    <input id="swal-input-name" class="swal2-input" placeholder="اسم القسم" required>
                    <input id="swal-input-description" class="swal2-input" placeholder="تفاصيل القسم">
                `,
                focusConfirm: false,
                preConfirm: () => {
            const name = document.getElementById("swal-input-name").value;
            if (!name) {
                Swal.showValidationMessage('يرجى إدخال اسم القسم');
                return false;
            }
            return [
                name,
                document.getElementById("swal-input-description").value
            ];
        },
                confirmButtonText: 'إضافة',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
            });

            if (formValues) {
                const [name, description] = formValues;
              
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const inputName = document.createElement('input');
                inputName.type = 'hidden';
                inputName.name = 'name';
                inputName.value = name;
                form.appendChild(inputName);

                const inputDetails = document.createElement('input');
                inputDetails.type = 'hidden';
                inputDetails.name = 'description';
                inputDetails.value = description;
                form.appendChild(inputDetails);

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

    <?php if (isset($message)){ ?>
        <script>
            Swal.fire({
                title: '<?php echo isset($validationErrors) ? "فشل الإضافة!" : "تم الإضافة!"; ?>',
                text: '<?php echo isset($message) ? $message : ""; ?>',
                icon: '<?php echo isset($validationErrors) ? "error" : "success"; ?>',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php } ; ?>


</body>

</html>
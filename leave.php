<?php 
include('DB/database.php');
include('DB/leave.php');
include('DB/leave_type.php');
include('DB/employee.php');

$database = new Database();
$db = $database->connect();
include("check_session.php");
$leave= new leave($db);
if(isset($_POST['save'])){
    $data=[
        'leave_type_id'=>$_POST['type'],
        'employee_id'=>$_POST['emp'],
        'end'=>$_POST['end'],
        'start'=>$_POST['start']
    ];
    $leave->Create($data);
    $message="تمت إضافة الإجازة بنجاح ";
}
if(isset($_POST['save_type'])){
    $data=[
        'type'=>$_POST['type']
    ];
    $type= new leave_type($db);
    $type->Create($data);
    $message="تمت إضافة  نوع الإجازة بنجاح ";
}
if(isset($_GET['leavestp'])){
    $filter=$_GET['leavestp'];
$leaves=$leave->select("SELECT * FROM leaves JOIN leave_type ON leaves.leave_type_id =  leave_type.id JOIN employees ON leaves.employee_id = employees.id WHERE leave_type.id= $filter");
}else{
    $leaves=$leave->select("SELECT * FROM leaves,leave_type , employees where leaves.employee_id = employees.id and leaves.leave_type_id = leave_type.id");
}


$leave_type= new leave_type($db);
$types=$leave_type->All();
$employee= new employee($db);
$employees= $employee->All();
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

                    <!-- Page Heading -->
                    <ul  class="breadcrumb m-3">
                        <li class="breadcrumb-item"> <a href="home.php" class='text-success'>الرئيسية</a></li> 
                        <li class="breadcrumb-item active">الاجازات </li> 
                    </ul>
                    <button id="add-leave-btn" class="btn btn-outline-success">إعطاء إجازة لموظف</button>
                    <div class="d-flex justify-content-between align-items-cente">
                        <h1 class="h3 mb-2 mt-3 text-gray-800">الإجازات</h1>
                        <button id="add-leave-type-btn" class="btn btn-outline-secondary" style="height: 50px;">إضافة نوع إجازة</button>
                    </div>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                    <div class="card-body">
                        <form class="mt-3" action="" method="get">
                            <select class="form-control" id="department" name="leavestp" >
                                <option value="">اختر سبب الاجازه</option>
                                <?php foreach($types as $type){?>
                                    <option value="<?=$type['id']?>"><?=$type['type']?></option>
                                <?php } ?>
                            </select> 
                            <button class="btn btn-secondary mt-2 mx-2" type="submit" name="filter" value="">فلتره</button>
                        </form>
                        
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="bg-gradient-success text-gray-100">#</th>
                                <th class="bg-gradient-success text-gray-100">الموظف</th>
                                <th class="bg-gradient-success text-gray-100">السبب</th>
                                <th class="bg-gradient-success text-gray-100">البدء</th>
                                <th class="bg-gradient-success text-gray-100">النهاية</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $count=0; foreach($leaves as $leave){ $count++?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td><?=htmlspecialchars($leave['name'])?></td>
                                        <td><?=htmlspecialchars($leave['type'])?></td>
                                        <td><?=htmlspecialchars($leave['start'])?></td>
                                        <td><?php
                                        $endDate = new DateTime($leave['end']);
                                        $currentDate = new DateTime();
                                        if ($endDate < $currentDate) {
                                            echo '<span class="text-gray-900">منتهية</span>';
                                        } else {
                                            echo  htmlspecialchars($leave['end']);
                                        } 
                                        ?>
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
    </div>
                <!-- /.container-fluid -->

            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("footer.html") ?>
            <!-- End of Footer -->

        
        <!-- End of Content Wrapper -->

   
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <?php include("Scroll.html")?>
    <!-- Logout Modal-->
    <?php include("Logout_model.html") ?>

    <!-- Bootstrap core JavaScript-->
    <?php include("script.html") ?>

    
    <script>
        document.getElementById('add-leave-btn').addEventListener('click', async () => {
            const typesOptions = `
        <?php foreach ($types as $type) { ?>
            <option value="<?= $type['id'] ?>"><?= $type['type'] ?></option>
        <?php } ?>
    `;
    const empOptions = `
        <?php foreach ($employees as $emp) { ?>
            <option value="<?= $emp['id'] ?>"><?= $emp['name'] ?></option>
        <?php } ?>
    `;
            const { value: formValues } = await Swal.fire({
                title: 'إضافة إجازة',
        html: `
            <label > سبب الإجازه  : </label>
            <select id="type-select" class="form-control mb-2">
                <option value="">اختر سبب الإجازة</option>
                ${typesOptions}
            </select>
            <label > الموظف   : </label>
            <select id="emp-select" class="form-control mb-2">
                <option value="">اختر  الموظف</option>
                ${empOptions}
            </select>
            <div class="form-group">
                <label > تاريخ بدء الاجازة: </label>
                <input id="start" type="date" class="form-control mb-2" placeholder="">
            </div>
            <div class="form-group">
                <label > تاريخ نهاية الاجازة: </label>
                <input id="end" type="date" class="form-control mb-2" placeholder="">
            </div>
        `,
        focusConfirm: false,
        preConfirm: () => {
            const type = document.getElementById("type-select").value;
            const emp = document.getElementById("emp-select").value;
            const start = document.getElementById("start").value;
            const end = document.getElementById("end").value;
            if (!type || !emp || !start || !end) {
                Swal.showValidationMessage('يرجى إدخال جميع الحقول ');
                return false;
        }
            return [type, emp, start, end];
        },
                confirmButtonText: 'إضافة',
                cancelButtonText: 'إلغاء',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton:  'btn btn-secondary',
                    title: ' text-success'
                },
                showCancelButton: true,
            });

            if (formValues) {
                const [type, emp, start, end] = formValues;
              
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const inputtype = document.createElement('input');
                inputtype.type = 'hidden';
                inputtype.name = 'type';
                inputtype.value = type;
                form.appendChild(inputtype);

                const inputemp = document.createElement('input');
                inputemp.type = 'hidden';
                inputemp.name = 'emp';
                inputemp.value = emp;
                form.appendChild(inputemp);

                const inputstart = document.createElement('input');
                inputstart.type = 'hidden';
                inputstart.name = 'start';
                inputstart.value = start;
                form.appendChild(inputstart);

                const inputend = document.createElement('input');
                inputend.type = 'hidden';
                inputend.name = 'end';
                inputend.value = end;
                form.appendChild(inputend);

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
    <script>
        document.getElementById('add-leave-type-btn').addEventListener('click', async () => {
            const { value: formValues } = await Swal.fire({
                title: 'إضافة نوع إجازة',
            html: `
            <div class="form-group">
                <label > نوع  الاجازة: </label>
                <input id="type" type="text" class="form-control mb-2" placeholder="">
            </div> 
        `,
        focusConfirm: false,
        preConfirm: () => {
            const type = document.getElementById("type").value;
            if (!type) {
                Swal.showValidationMessage('يرجى إدخال نوع الإجازة ');
                return false;
        }
            return [type];
        },
                confirmButtonText: 'إضافة',
                cancelButtonText: 'إلغاء',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton:  'btn btn-secondary',
                    title: ' text-success'
                },
                showCancelButton: true,
            });

            if (formValues) {
                const [type] = formValues;
              
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const inputtype = document.createElement('input');
                inputtype.type = 'hidden';
                inputtype.name = 'type';
                inputtype.value = type;
                form.appendChild(inputtype);

                const inputSave = document.createElement('input');
                inputSave.type = 'hidden';
                inputSave.name = 'save_type';
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
            timer: 2500, 
            showConfirmButton: false
        });
        </script>

    <?php endif; ?>

    
</body>

</html>
<?php
include('DB/database.php');
include('DB/jop.php');
include('DB/advance.php');
include('Validattion/Validator.php');
include('DB/employee.php');


$database = new Database();
$db = $database->connect();
include("check_session.php");
$advance=new advance($db);
$employee=new employee($db);
$employees=$employee->All();
if (isset($_POST['save'])) {
    $data=[
        'amount'=>$_POST['advance'],
        'date'=>date_format(date_create(),'Y-m-d'),
        'employee_id'=>$_POST['emp'],
    ];
    $advance_id=$advance->Create($data);
    $message='تم طلب السلفه بنجاح ';
    
}

$results=$advance->select("SELECT sum(amount) AS 'total',departments.name AS 'dep_name',employees.id AS 'emp_id', employees.name AS 'emp_name', employees.phone AS 'emp_phone' FROM advances JOIN employees ON advances.employee_id= employees.id JOIN departments ON employees.department_id = departments.id group by advances.employee_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>إضافة سلفة</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="css/sb-admin-2.css" rel="stylesheet">
</head>

<body id="page-top">

<div id="wrapper">
    <?php include('Sidebar.html') ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('navbar.php') ?>
            <div class="container-fluid">
                <ul class="breadcrumb m-3">
                    <li class="breadcrumb-item"><a href="home.php" class='text-success'>الرئيسية</a></li>
                    <li class="breadcrumb-item active"> السلف </li>
                </ul>
                <button type="submit" id="add-advance-btn" name="save" class="btn btn-outline-success">طلب سلفه </button>
                <h2 class="mt-3"> السلف</h2>
                <div class="card shadow mb-4">
                       
                <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="bg-gradient-success text-gray-100">#</th>
                        <th class="bg-gradient-success text-gray-100">الموظف</th>
                        <th class="bg-gradient-success text-gray-100">الاجمالي</th>
                        <th class="bg-gradient-success text-gray-100">رقم التواصل</th>
                        <th class="bg-gradient-success text-gray-100">القسم </th>
                        <th class="bg-gradient-success text-gray-100">التفاصيل</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 0; foreach ($results as $result): $count++; ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?= htmlspecialchars($result['emp_name']) ?></td>
                            <td><?= htmlspecialchars($result['total']) ?></td>
                            <td><?= htmlspecialchars($result['emp_phone']) ?></td>
                            <td><?= htmlspecialchars($result['dep_name']) ?></td>
                            <td><a href="advance_details.php?id=<?= $result['emp_id']?>"><button class="btn btn-outline-primary">التفاصيل</button></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div>
</div>
            </div>
            <?php include("footer.html") ?>
        </div>
    </div>
</div>

<!-- Scroll to Top Button-->
<?php include("Scroll.html") ?>

<!-- Logout Modal-->
<?php include("Logout_model.html") ?>

<!-- Bootstrap core JavaScript-->
<?php include("script.html") ?>
<script>
        document.getElementById('add-advance-btn').addEventListener('click', async () => {
    const empOptions = `
        <?php foreach ($employees as $emp) { ?>
            <option value="<?= $emp['id'] ?>"><?= $emp['name'] ?></option>
        <?php } ?>
    `;
            const { value: formValues } = await Swal.fire({
                title: 'طلب سلفه ',
        html: `
            <label >  الموظف  : </label>
            <select id="emp-select" class="form-control mb-2">
                <option value="">اختر  الموظف</option>
                ${empOptions}
            </select>
            <div class="form-group">
                <label > مقدار السلفه  : </label>
                <input id="advance" type="number" class="form-control mb-2" placeholder="">
            </div>
           
        `,
        focusConfirm: false,
        preConfirm: () => {
            const advance = document.getElementById("advance").value;
            const emp = document.getElementById("emp-select").value;
            if (!advance || !emp) {
                Swal.showValidationMessage('يرجى إدخال جميع الحقول ');
                return false;
        }
            return [advance, emp];
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
                const [advance, emp] = formValues;
              
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const inputadvance = document.createElement('input');
                inputadvance.type = 'hidden';
                inputadvance.name = 'advance';
                inputadvance.value = advance;
                form.appendChild(inputadvance);

                const inputemp = document.createElement('input');
                inputemp.type = 'hidden';
                inputemp.name = 'emp';
                inputemp.value = emp;
                form.appendChild(inputemp);

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

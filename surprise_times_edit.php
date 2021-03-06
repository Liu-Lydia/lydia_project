<?php
require __DIR__ . '/is_admins.php';
require __DIR__ . '/db_connect.php';

$title = '驚喜廚房場次修改';
$pageName = 'surprise_times_edit';

if (!isset($_GET['sid'])) {
    header('Location: surprise_times.php');
    exit;
}

$sid = intval($_GET['sid']);

$row = $pdo
    ->query("SELECT * FROM surprise_times WHERE sid=$sid")
    ->fetch();

if (empty($row)) {
    header('Location: surprise_times.php');
    exit;
}
?>

<?php include __DIR__ . "/parts/head.php"; ?>
<?php include __DIR__ . "/parts/navbar.php"; ?>

<style>
    form small.error-msg {
        color: red;
    }
</style>

<div class="container">

    <div class="row d-flex justify-content-center">
        <div class="col-lg-6">

            <div class="alert alert-danger" role="alert" id="info" style="display: none">
                錯誤
            </div>

            <div class="card mt-4">
                <div class="card-body pt-0 pb-0">
                    <h5 class="card-title text-center pt-4">編輯驚喜廚房場次</h5>

                    <form method="POST" name="form1" novalidate onsubmit="CheckForm(); return false;">
                        <input type="hidden" name="sid" value="<?= $sid ?>">

                        <div class="form-group mt-4">
                            <label for="ReservationTime">輸入場次時間&nbsp;&nbsp;ex : "11:30"</label>
                            <input type="text" class="form-control mt-2" id="ReservationTime" name="ReservationTime" value="<?= $row['ReservationTime'] ?>" required>
                            <small class="form-text error-msg" style="display:none;"></small>
                        </div>

                        <div class="d-flex justify-content-center mt-4 pt-4 pb-4">
                            <button type="submit" class="btn btn-primary">修改</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<?php include __DIR__ . "/parts/script.php"; ?>

<script>
    const info = document.querySelector('#info');
    const ReservationTime = document.querySelector('#ReservationTime');

    function CheckForm() {
        info.style.display = 'none';
        let isPass = true;

        ReservationTime.style.borderColor = '#CCCCCC';
        ReservationTime.nextElementSibling.style.display = 'none';

        if (ReservationTime.value.length === 0) {
            isPass = false;
            ReservationTime.style.borderColor = 'red';
            let small = ReservationTime.closest('.form-group').querySelector('small');
            small.innerText = "請輸入場次時間;"
            small.style.display = "block";
        }

        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('surprise_times_edit_api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        info.classList.remove('alert-danger');
                        info.classList.add('alert-success');
                        info.innerHTML = '修改成功';
                    } else {
                        info.classList.remove('alert-success');
                        info.classList.add('alert-danger');
                        info.innerHTML = obj.error || '修改失敗';
                    }
                    info.style.display = 'block';
                })
                .catch((err) => {
                    console.log('錯誤', err);
                });
        }
    }
</script>

<?php include __DIR__ . "/parts/foot.php"; ?>
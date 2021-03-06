<?php
require __DIR__ . '/db_connect.php';

$title = '驚喜廚房場次';
$pageName = 'surprise_times';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? ($_GET['search']) : '';
$params = [];

$where = 'WHERE 1';
if (!empty($search)) {
    $where .= sprintf(" AND `ReservationTime` LIKE %s", $pdo->quote('%' . $search . '%'));
    $params['search'] = $search;
}

$perPage = 3;
$t_sql = "SELECT COUNT(1) FROM surprise_times $where";
$totalRows = $pdo->query($t_sql)->fetch()['COUNT(1)'];
$totalPages = ceil($totalRows / $perPage);

if ($page > $totalPages) $page = $totalPages;
if ($page < 1) $page = 1;

$p_sql = sprintf("SELECT * FROM surprise_times %s ORDER BY sid ASC LIMIT %s ,%s", $where, ($page - 1) * $perPage, $perPage);

$stmt = $pdo->query($p_sql);
?>

<?php include __DIR__ . "/parts/head.php" ?>
<?php include __DIR__ . "/parts/navbar.php" ?>

<style>
    .remove-icon a i {
        color: #B9433B;
    }

    .edit-icon a i {
        color: #a2a3a5;
    }
</style>

<div class="container">

    <div class="row mt-2">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination m-0">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php
                                                    $params['page'] = 1;
                                                    echo http_build_query($params);
                                                    ?>">
                            <i class="fas fa-arrow-alt-circle-left page-color"></i>
                        </a>
                    </li>
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php $params['page'] = $page - 1;
                                                    echo http_build_query($params); ?>">
                            <i class="far fa-arrow-alt-circle-left page-color"></i>
                        </a>
                    </li>

                    <?php for ($i = $page - 3; $i <= $page + 3; $i++) :
                        if ($i >= 1 and $i <= $totalPages) : ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a class="page-link" href="?<?php $params['page'] = $i;
                                                            echo http_build_query($params); ?>">
                                    <?= $i ?></a>
                            </li>

                    <?php endif;
                    endfor; ?>

                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php $params['page'] = $page + 1;
                                                    echo http_build_query($params); ?>">
                            <i class="far fa-arrow-alt-circle-right page-color"></i>
                        </a>
                    </li>
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php $params['page'] = $totalPages;
                                                    echo http_build_query($params); ?>">
                            <i class="fas fa-arrow-alt-circle-right page-color"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="col d-flex flex-row-reverse bd-highlight">
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" name="search" value="<?= htmlentities($search) ?>" placeholder="ReservationTime" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col mt-4">

            <table class="separate table_style text-center">

                <thead class="head_style">
                    <tr>
                        <th class="trleft_style" scope="col">sid</th>
                        <th class="trright_style" scope="col">ReservationTime</th>
                    </tr>
                </thead>
                <tbody class="content_style">
                    <?php while ($r = $stmt->fetch()) : ?>
                        <tr>
                            <td><?= $r['sid'] ?></td>
                            <td><?= htmlentities($r['ReservationTime']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
            
        </div>
    </div>

</div>


<?php include __DIR__ . "/parts/script.php" ?>

<script>
    function del_it(sid) {
        if (confirm(`是否刪除 ${sid} 資料`)) {
            location.href = 'ad_delete.php?sid=' + sid;
        }
    }
</script>

<?php include __DIR__ . "/parts/foot.php" ?>
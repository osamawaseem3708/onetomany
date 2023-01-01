<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
require ('connection.php');
require ('functions.php');
?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <a href="index.php" class="btn btn-success"> ADD MORE</a>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Prodname</th>
                    <th>Production Date</th>
                    <th>Fininshed Qty</th>
                    <th>Made By</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $res = mysqli_query ($con, "SELECT * FROM `tblpreproductionhead`");
                while ($row = mysqli_fetch_assoc ($res)) {
                    ?>
                    <tr>
                        <td><?= $row['prodid']; ?></td>
                        <td><?= $row['predate']; ?></td>
                        <td><?= $row['finishedqty']; ?></td>
                        <td><?= $row['employeeid']; ?></td>
                        <td><a href="index.php?prehid=<?= $row['prehid']; ?>">Edit<i class="fa fa-edit"
                                                                                     style="font-size:36px"></i></a>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

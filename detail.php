<?php
require "function.php";
require "cek.php";

// mendapatkan id barang
$idbarang = $_GET["id"];

// mendapatkan informasi barang berdasarkan database
$get = mysqli_query($conn, "SELECT* FROM stock WHERE idbarang = '$idbarang'");
$fetch = mysqli_fetch_assoc($get);

// set variabel
$namabarang = $fetch["namabarang"];
$deskripsi = $fetch["deskripsi"];
$stock = $fetch["stock"];
$image = $fetch["image"];

// cek gambar ada atau tidak
$gambar = $fetch["image"]; // ambil gambar
if ($gambar == null) {
    // jika tidak ada gambar
    $img = "No image";

} else {
    // jika ada gambar
    $img = "<img src='images/".$gambar."' class='imgzoom'>";
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?=$namabarang;?></title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <style>
            .imgzoom {
                width: 100px;
            }

            .imgzoom:hover {
                transform: scale(2.5);
                transition: 0.3s ease;
            }

            a {
                text-decoration: none;
                color: black;
            }

            a:hover {
                text-decoration: none;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><span class="material-icons">menu</span></button>
            <a class="navbar-brand" href="index.php">Inventory Management</a>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><span class="material-icons">warehouse</span></div>
                                Stock Goods
                            </a>
                            <a class="nav-link" href="incoming.php">
                                <div class="sb-nav-link-icon"><span class="material-icons">add_shopping_cart</span></div>
                                Incoming Goods
                            </a>
                            <a class="nav-link" href="outcoming.php">
                                <div class="sb-nav-link-icon"><span class="material-icons">shopping_cart_checkout</span></div>
                                Outcoming Goods
                            </a>
                            <a class="nav-link" href="aboutme.php">
                                <div class="sb-nav-link-icon"><span class="material-icons">perm_identity</span></div>
                                About Me
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <strong><a class="nav nav-link text-center" href="logout.php">LOG OUT</a></strong>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4 mb-4"><?=$namabarang;?> - <?=$deskripsi;?></h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3>Incoming History</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Date, Time</th>
                                                <th>Receiver</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambildatamasuk = mysqli_query($conn, "SELECT * FROM masuk WHERE idbarang = '$idbarang'");
                                            $i = 1;
                                            while ($fetch = mysqli_fetch_array($ambildatamasuk)) {
                                                $tanggal = $fetch["tanggal"];
                                                $keterangan = $fetch["keterangan"];
                                                $qty = $fetch["qty"];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$i++;?></td>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$keterangan;?></td>
                                                <td class="text-right"><?=$qty;?></td>
                                            </tr>

                                            <?php
                                            };
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3>Outcoming History</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Date, Time</th>
                                                <th>Receiver</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambildatakeluar = mysqli_query($conn, "SELECT * FROM keluar WHERE idbarang = '$idbarang'");
                                            $i = 1;
                                            while ($fetch = mysqli_fetch_array($ambildatakeluar)) {
                                                $tanggal = $fetch["tanggal"];
                                                $penerima = $fetch["penerima"];
                                                $qty = $fetch["qty"];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$i++;?></td>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$penerima;?></td>
                                                <td class="text-right"><?=$qty;?></td>
                                            </tr>

                                            <?php
                                            };
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Yanda Agil 2021</div>
                        </div>
                    </div>
                </footer>
            </div>
            
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables.js"></script>
    </body>
    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Stock Goods</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="goodsname" placeholder="Name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="description" placeholder="Category" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="number" name="stock" placeholder="Stock" class="form-control" required>
                    </div>
                    <div class="custom-file">
                        <label class="custom-file-label">Choose image..</label>
                        <input type="file" name="file" class="custom-file-input">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="addnew">Add</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</html>

<?php
require "function.php";
require "cek.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Incoming Goods</title>
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
                        <h1 class="mt-4 mb-4">Incoming Goods</h1>
                        <div class="card mb-4">
                            <div class="row card-header">
                                <!-- Button to Open the Modal -->
                                <div class="col text-left">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Incoming Goods</button>
                                </div>
                                <form method="POST" class="form-inline">
                                    <div class="col text-right">
                                        <input type="date" name="tgl_mulai" class="form-control">
                                        <input type="date" name="tgl_selesai" class="form-control ml-2">
                                        <button type="submit" name="filter_tgl" class="btn btn-info ml-2">Filter</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Date, Time</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Receiver</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($_POST["filter_tgl"])) {
                                                $mulai = $_POST["tgl_mulai"];
                                                $selesai = $_POST["tgl_selesai"];

                                                if ($mulai != null || $selesai != null) {
                                                    $ambilsemuadatamasuk = mysqli_query($conn, "SELECT * FROM masuk m, stock s WHERE s.idbarang = m.idbarang AND tanggal BETWEEN '$mulai' AND DATE_ADD('$selesai', INTERVAL 1 DAY) ORDER BY idmasuk DESC");
                                                } else {
                                                    $ambilsemuadatamasuk = mysqli_query($conn, "SELECT * FROM masuk m, stock s WHERE s.idbarang = m.idbarang ORDER BY idmasuk DESC");
                                                }
                                                
                                            } else {
                                                $ambilsemuadatamasuk = mysqli_query($conn, "SELECT * FROM masuk m, stock s WHERE s.idbarang = m.idbarang ORDER BY idmasuk DESC");
                                            }

                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadatamasuk)) {
                                                $tanggal = $data["tanggal"];
                                                $namabarang = $data["namabarang"];
                                                $qty = $data["qty"];
                                                $keterangan = $data["keterangan"];
                                                $idb = $data["idbarang"];
                                                $idm = $data["idmasuk"];

                                                // cek gambar ada atau tidak
                                                $gambar = $data["image"]; // ambil gambar
                                                if ($gambar == null) {
                                                    // jika tidak ada gambar
                                                    $img = "No image";

                                                } else {
                                                    // jika ada gambar
                                                    $img = "<img src='images/".$gambar."' class='imgzoom'>";
                                                }
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$i++;?></td>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$img;?></td>
                                                <td><strong><a href="detail.php?id=<?=$idb;?>"><?=$namabarang;?></a></strong></td>
                                                <td class="text-right"><?=$qty;?></td>
                                                <td><?=$keterangan;?></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$idm;?>">Edit</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idm;?>">Delete</button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit<?=$idm;?>">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Incoming Goods</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    
                                                    <!-- Modal body -->
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="qty">Quantity</label>
                                                                <input type="number" name="qty" value="<?=$qty;?>" class="form-control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="receiver">Receiver</label>
                                                                <input type="text" name="receiver" value="<?=$keterangan;?>" class="form-control" required>
                                                            </div>
                                                            <input type="hidden" name="idb" value="<?=$idb;?>">
                                                            <input type="hidden" name="idm" value="<?=$idm;?>">
                                                        </div>
                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary" name="updatebarangmasuk">Apply</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?=$idm;?>">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Delete Incoming Goods</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    
                                                    <!-- Modal body -->
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            Are you sure you want to delete <?=$namabarang;?>?
                                                        </div>
                                                        <input type="hidden" name="idb" value="<?=$idb;?>">
                                                        <input type="hidden" name="idm" value="<?=$idm;?>">
                                                        <input type="hidden" name="qty" value="<?=$qty;?>">
                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                            <button type="submit" class="btn btn-danger" name="hapusbarangmasuk">Yes, Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>

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
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Incoming Goods</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <select name="goods" class="form-control">
                            <?php
                            $ambilsemuadata = mysqli_query($conn, "SELECT * FROM stock ORDER BY namabarang ASC");
                            while ($fetcharray = mysqli_fetch_array($ambilsemuadata)) {
                                $namabarang = $fetcharray["namabarang"];
                                $idbarang = $fetcharray["idbarang"];
                            ?>
                            <option value="<?=$idbarang;?>"><?=$namabarang;?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" name="qty" placeholder="Quantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="receiver" placeholder="Receiver" class="form-control" required>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="incominggoods">Add</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</html>
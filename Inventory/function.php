<?php
session_start();

// membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "inventory");

// menambah barang baru
if (isset($_POST["addnew"])) {
    $goodsname = $_POST["goodsname"];
    $description = $_POST["description"];
    $stock = $_POST["stock"];

    // upload gambar
    $allowed_extension = array("png", "jpg", "jpeg");
    $nama = $_FILES["file"]["name"]; // ambil nama gambar
    $dot = explode(".", $nama);
    $extension = strtolower(end($dot)); // ambil ekstensi
    $ukuran = $_FILES["file"]["size"]; // ambil size file
    $file_tmp = $_FILES["file"]["tmp_name"]; // ambil lokasi filenya

    // penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . "." .$extension; // menggabungkan nama file yang dienkripsi dengan ekstensinya

    // validasi nama barang sudah ada atau belum
    $cek = mysqli_query($conn, "SELECT * FROM stock WHERE namabarang = '$goodsname'");
    $hitung = mysqli_num_rows($cek);

    if ($hitung < 1) {
        // jika belum ada
        
        // proses upload gambar
        if (in_array($extension, $allowed_extension) === true) {
            // validasi ukuran filenya
            if ($ukuran < 15000000) {
                move_uploaded_file($file_tmp, "images/" . $image);

                $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock, image) VALUES('$goodsname', '$description', '$stock', '$image')");

                if ($addtotable) {
                    header("location:index.php");

                } else {
                    echo "gagal";
                    header("location:index.php");
                }

            } else {
                // jika file lebih dari 15MB
                echo "
                <script>
                    alert('Image size should be less than 15MB!');
                    windows.location.href='index.php';
                </script>";
            }

        } else {
            // jika ekstensi gambar bukan png, jpg, atau jpeg
            echo "
            <script>
                alert('Image extension should be png, jpg, or jpeg!');
                windows.location.href='index.php';
            </script>";
        }

    } else {
        // jika sudah ada
        echo "
        <script>
            alert('Goods already exist!');
            windows.location.href='index.php';
        </script>";
    }
}

// menambah barang masuk
if (isset($_POST["incominggoods"])) {
    $goods = $_POST["goods"];
    $receiver = $_POST["receiver"];
    $qty = $_POST["qty"];

    $cekstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$goods'");
    $ambildata = mysqli_fetch_array($cekstock);

    $stocksekarang = $ambildata["stock"];
    $tambahstock = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, qty) VALUES('$goods', '$receiver', '$qty')");
    $updatestock = mysqli_query($conn, "UPDATE stock SET stock = '$tambahstock' WHERE idbarang = '$goods'");

    if ($addtomasuk && $updatestock) {
        header("location:incoming.php");
    } else {
        echo "gagal";
        header("location:incoming.php");
    }
}

// menambah barang keluar
if (isset($_POST["outcominggoods"])) {
    $goods = $_POST["goods"];
    $receiver = $_POST["receiver"];
    $qty = $_POST["qty"];

    $cekstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$goods'");
    $ambildata = mysqli_fetch_array($cekstock);

    $stocksekarang = $ambildata["stock"];

    if ($stocksekarang >= $qty) {
        // jika stock barang mencukupi
        $kurangistock = $stocksekarang - $qty;

        $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) VALUES('$goods', '$receiver', '$qty')");
        $updatestock = mysqli_query($conn, "UPDATE stock SET stock = '$kurangistock' WHERE idbarang = '$goods'");

        if ($addtokeluar && $updatestock) {
            header("location:outcoming.php");
        } else {
            echo "gagal";
            header("location:outcoming.php");
        }
    } else {
        // jika stock barang tidak mencukupi
        echo "
        <script>
            alert('Stock Goods is not enough!');
            window.location.href='outcoming.php';
        </script>";
    }
}

// update stock barang
if (isset($_POST["updatebarang"])) {
    $idb = $_POST["idb"];
    $namabarang = $_POST["goodsname"];
    $deskripsi = $_POST["description"];

    // upload gambar
    $allowed_extension = array("png", "jpg", "jpeg");
    $nama = $_FILES["file"]["name"]; // ambil nama gambar
    $dot = explode(".", $nama);
    $extension = strtolower(end($dot)); // ambil ekstensi
    $ukuran = $_FILES["file"]["size"]; // ambil size file
    $file_tmp = $_FILES["file"]["tmp_name"]; // ambil lokasi filenya

    // penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . "." .$extension; // menggabungkan nama file yang dienkripsi dengan ekstensinya

    if ($ukuran == 0) {
        // jika tidak ingin upload
        $update = mysqli_query($conn, "UPDATE stock SET namabarang = '$namabarang', deskripsi = '$deskripsi' WHERE idbarang = '$idb'");

        if ($update) {
            header("location:index.php");
        } else {
            echo "gagal";
            header("location:index.php");
        }

    } else {
        // jika ingin upload
        move_uploaded_file($file_tmp, "images/" . $image);
        $update = mysqli_query($conn, "UPDATE stock SET namabarang = '$namabarang', deskripsi = '$deskripsi', image = '$image' WHERE idbarang = '$idb'");

        if ($update) {
            header("location:index.php");
        } else {
            echo "gagal";
            header("location:index.php");
        }
    }
}

// hapus stock barang
if (isset($_POST["hapusbarang"])) {
    $idb = $_POST["idb"];

    $gambar = mysqli_query($conn, "SELECT* FROM stock WHERE idbarang = '$idb'");
    $get = mysqli_fetch_array($gambar);
    $img = "images/" . $get["image"];
    unlink($img);

    $hapus = mysqli_query($conn, "DELETE FROM stock WHERE idbarang = '$idb'");

    if ($hapus) {
        header("location:index.php");
    } else {
        echo "gagal";
        header("location:index.php");
    }
}

// edit barang masuk
if (isset($_POST["updatebarangmasuk"])) {
    $idb = $_POST["idb"];
    $idm = $_POST["idm"];
    $qty = $_POST["qty"];
    $receiver = $_POST["receiver"];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya["stock"];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk = '$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya["qty"];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangi = $stockskrg + $selisih;
        $kurangistock =mysqli_query($conn, "UPDATE stock SET stock ='$kurangi' WHERE idbarang = '$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty = '$qty', keterangan = '$receiver' WHERE idmasuk = '$idm'");

        if ($kurangistock && $updatenya) {
            header("location:incoming.php");
        } else {
            echo "gagal";
            header("location:incoming.php");
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangi = $stockskrg - $selisih;
        $kurangistock =mysqli_query($conn, "UPDATE stock SET stock ='$kurangi' WHERE idbarang = '$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty = '$qty', keterangan = '$receiver' WHERE idmasuk = '$idm'");

        if ($kurangistock && $updatenya) {
            header("location:incoming.php");
        } else {
            echo "gagal";
            header("location:incoming.php");
        }
    }
}

// hapus barang masuk
if (isset($_POST["hapusbarangmasuk"])) {
    $idb = $_POST["idb"];
    $qty = $_POST["qty"];
    $idm =$_POST["idm"];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data["stock"];

    $selisih = $stock - $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock = '$selisih' WHERE idbarang = '$idb'");
    $hapusdata =mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk = '$idm'");

    if ($update && $hapusdata) {
        header("location:incoming.php");
    }
}

// edit barang keluar
if (isset($_POST["updatebarangkeluar"])) {
    $idb = $_POST["idb"];
    $idk = $_POST["idk"];
    $qty = $_POST["qty"];
    $receiver = $_POST["receiver"];

    // ambil stock barang saat ini
    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya["stock"];

    // quantity barang keluar saat ini
    $qtyskrg = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar = '$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya["qty"];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangi = $stockskrg - $selisih;

        if ($selisih <= $stockskrg) {
            $kurangistock =mysqli_query($conn, "UPDATE stock SET stock ='$kurangi' WHERE idbarang = '$idb'");
            $updatenya = mysqli_query($conn, "UPDATE keluar SET qty = '$qty', penerima = '$receiver' WHERE idkeluar = '$idk'");

            if ($kurangistock && $updatenya) {
                header("location:outcoming.php");
            } else {
                echo "gagal";
                header("location:outcoming.php");
            }
        } else {
            echo "
            <script>
            alert('Stock is not enough!');
            window.location.href = 'outcoming.php';
            </script>";
        }
        
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangi = $stockskrg + $selisih;
        $kurangistock =mysqli_query($conn, "UPDATE stock SET stock ='$kurangi' WHERE idbarang = '$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty = '$qty', penerima = '$receiver' WHERE idkeluar = '$idk'");

        if ($kurangistock && $updatenya) {
            header("location:outcoming.php");
        } else {
            echo "gagal";
            header("location:outcoming.php");
        }
    }
}

// hapus barang keluar
if (isset($_POST["hapusbarangkeluar"])) {
    $idb = $_POST["idb"];
    $qty = $_POST["qty"];
    $idk =$_POST["idk"];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data["stock"];

    $selisih = $stock + $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock = '$selisih' WHERE idbarang = '$idb'");
    $hapusdata =mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar = '$idk'");

    if ($update && $hapusdata) {
        header("location:outcoming.php");
    }
}
?>
<?php
// Include config file
require_once "config.php";

//Mendefinisikan variabel dengan value kosong
$produk = $jumlah = "";
$produk_err = $jumlah_err = "";

//Kode ketika button Submit ditekan, maka isi dari form akan diproses ke database
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Validasi apakah nama barang sudah dimasukkan dengan format yang benar
    $input_produk = trim($_POST["produk"]);
    if(empty($input_produk)){
        $produk_err = "Please enter a produk.";
    } elseif(!filter_var($input_produk, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $produk_err = "Please enter a valid produk.";
    } else{
        $produk = $input_produk;
    }

    //Validasi apakah jumlah sudah dimasukkan dengan format yang benar
    $input_jumlah = trim($_POST["jumlah"]);
    if(empty($input_jumlah)){
        $jumlah_err = "Please enter the jumlah amount.";
    } elseif(!ctype_digit($input_jumlah)){
        $jumlah_err = "Please enter a positive integer value.";
    } else{
        $jumlah = $input_jumlah;
    }

    //Memastikan apakah ada error sebelum memasukkan record ke database
    if(empty($produk_err) && empty($jumlah_err)){
        //Mempersiapkan query untuk insert
        $sql = "INSERT INTO stok_barang (produk, jumlah) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            //Memasukkan variable ke statement
            mysqli_stmt_bind_param($stmt, "ss", $param_produk, $param_jumlah);

            //mengatur parameter
            $param_produk = $produk;
            $param_jumlah = $jumlah;

            //Percobaan untuk eksekusi statement
            if(mysqli_stmt_execute($stmt)){
                //Ketika record berhasil ditambahkan maka akan kembali ke index.php
                header("location: index.php");
                exit();
            } else{ //Pesan ketika record gagal dimasukkan
                echo "Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
    }

    //Menutup Koneksi
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title> <!--Judul Halaman-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Tambah Record</h2>
                    </div>
                    <p class="alert alert-danger fade in">PASTIKAN PRODUK YANG DITAMBAHKAN ADALAH PRODUK BARU (BELUM BERADA DI DATABASE)</p> <!--Pesan untuk pengguna-->
                    <p class="alert alert-danger fade in">JIKA PRODUK SUDAH ADA SILAHKAN PILIH PRODUK DAN UPDATE PADA HALAMAN UTAMA</p> <!--Pesan untuk pengguna-->
                    <p>Masukkan nama barang dan jumlahnya pada field di bawah ini, kemudian tekan submit untuk menambahkan.</p> <!--Pesan untuk pengguna-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                        <div class="form-group <?php echo (!empty($produk_err)) ? 'has-error' : ''; ?>">
                            <label>Produk</label>
                            <input type="text" name="produk" class="form-control" value="<?php echo $produk; ?>">
                            <span class="help-block"><?php echo $produk_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($jumlah_err)) ? 'has-error' : ''; ?>">
                            <label>Jumlah</label>
                            <input type="text" name="jumlah" class="form-control" value="<?php echo $jumlah; ?>">
                            <span class="help-block"><?php echo $jumlah_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

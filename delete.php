<?php
//Proses yang dilakukan ketika pengguna sudah mengkonfirmasi untuk menghapus
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Include config file
    require_once "config.php";
    
    //Query untuk delete record dari tabel stok_barang
    $sql = "DELETE FROM stok_barang WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        //Memasukkan variable ke statement
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        //Mengatur parameter
        $param_id = trim($_POST["id"]);
        
        //Perobaan eksekusi statement
        if(mysqli_stmt_execute($stmt)){
            //jika statement berhasil dieksekusi maka pengguna akan diarahkan kembali ke halaman index.php
            header("location: index.php");
            exit();
        } else{ //jika gagal maka akan ditampilkan pesan berikut
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    mysqli_stmt_close($stmt);
    
    //Menutup koneksi
    mysqli_close($link);
} else{
    //Mengecek apakah id ada
    if(empty(trim($_GET["id"]))){
        //jika tidak ditemukan id yang valid maka pengguna akan diarahkan ke halaman error.php
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                        <h1>Delete Record</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this record?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
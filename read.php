<?php
//Melakukan pengecekan apakah id yang dimasukkan valid atau tidak
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";
    
    //Query untuk select record pada table stok barang dengan id sesuai request pengguna
    $sql = "SELECT * FROM stok_barang WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        //Menghubungkan variable ke statement sebagai parameter
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        //Atur parameter
        $param_id = trim($_GET["id"]);
        
        //Kondisi untuk percobaan eksekusi statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                //untuk menerima value dari setiap field
                $produk = $row["produk"];
                $jumlah = $row["jumlah"];
            } else{
                //Ketika id yang direquest invalid maka akan pengguna diantar ke halaman error.php
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later."; //Jika gagal dalam menghubungkan variabel ke statement
        }
    }
     
    mysqli_stmt_close($stmt);
    
    //menutup koneksi
    mysqli_close($link);
} else{
    //Ketika URL tidak terdapat id yang valid maka akan pengguna diantar ke halaman error.php
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> <!--import css dari source eksternal-->
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
                        <h1>View Record</h1> <!--Judul halaman dengan heading 1-->
                    </div>
                    <div class="form-group">
                        <label>Produk</label> <!--Menampilkan nama produk-->
                        <p class="form-control-static"><?php echo $row["produk"]; ?></p> <!--mengambil data dari record-->
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label> <!--Menampilkan jummlah produk-->
                        <p class="form-control-static"><?php echo $row["jumlah"]; ?></p> <!--Mengambil data dari record-->
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p> <!--Button back, ketika ditekan maka pengguna akan kembali ke halaman index.php(halaman utama)-->
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
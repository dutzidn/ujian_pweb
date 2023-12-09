<?php
// Include config file
require_once "config.php";
 
//mendefinisikan variabel
$produk = $jumlah = "";
$produk_err = $jumlah_err = "";
 
//Memproses data ketika pengguna menekan submit
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    //Validasi data
    $input_produk = trim($_POST["produk"]);
    //Validasi apakah nama barang sudah dimasukkan dengan format yang benar
    if(empty($input_produk)){
        $produk_err = "Please enter a produk.";
    } elseif(!filter_var($input_produk, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $produk_err = "Please enter a valid produk.";
    } else{
        $produk = $input_produk;
    }
    
    //Validasi apakah nama barang sudah dimasukkan dengan format yang benar
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
        //Mempersiapkan query untuk update
        $sql = "UPDATE stok_barang SET produk=?, jumlah=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            //Memasukkan variable ke statement
            mysqli_stmt_bind_param($stmt, "ssi", $param_produk, $param_jumlah, $param_id);
            
            //mengatur parameter
            $param_produk = $produk;
            $param_jumlah = $jumlah;
            $param_id = $id;
            
            //Percobaan untuk eksekusi statement
            if(mysqli_stmt_execute($stmt)){
                //Ketika record berhasil ditambahkan maka akan kembali ke index.php
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later."; //Pesan ketika record gagal dimasukkan
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    //menutup koneksi
    mysqli_close($link);
} else{
    //Melakukan pengecekan apakah id yang dimasukkan valid atau tidak
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        //Query untuk select record pada table stok barang dengan id sesuai request pengguna
        $sql = "SELECT * FROM stok_barang WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            //Memasukkan variable ke statement
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            //mengatur parameter
            $param_id = $id;
            
            //Percobaan untuk eksekusi statement
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
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
        
        //menutup koneksi
        mysqli_close($link);
    }  else{
        //Ketika URL tidak terdapat id yang valid maka akan pengguna diantar ke halaman error.php
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
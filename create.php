<?php
$koneksi = mysqli_connect("localhost", "root", "", "sistem_pakar");

// if ($koneksi) {
//     echo "koneksi database berhasil.<br/>";
// } else {
//     echo "koneksi gagal.<br/>";
// }

$nama_lengkap = $_POST["nama_lengkap"];
$email = $_POST['email'];
$hp = $_POST['hp'];
$alamat = $_POST['alamat'];
$password = $_POST['password'];
$query = mysqli_query($koneksi, "INSERT INTO login set nama_lengkap='$nama_lengkap', email='$email', hp='$hp', alamat='$alamat', password='$password' ");
if ($query) {
    echo json_encode([
        'pesan' => 'Sukses'
    ]);
} else {
    echo json_encode([
        'pesan' => 'GAGAL'
    ]);
}

echo json_encode($data);

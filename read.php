<!-- JSON READ DATA -->

<?php
$koneksi = mysqli_connect("localhost", "root", "", "sistem_pakar");

// if ($koneksi) {
//     echo "koneksi database berhasil.<br/>";
// } else {
//     echo "koneksi gagal.<br/>";
// }
$query = mysqli_query($koneksi, "SELECT * FROM login");
$data = mysqli_fetch_all($query, MYSQLI_ASSOC);
echo json_encode($data);

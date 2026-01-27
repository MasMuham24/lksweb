<?php
header("Content-Type: application/json");

$file = "../data/messages.json";

// ambil data POST
$dataBaru = [
  "nama"  => $_POST["nama"] ?? "",
  "email" => $_POST["email"] ?? "",
  "phone" => $_POST["phone"] ?? "",
  "kota"  => $_POST["kota"] ?? "",
  "pesan" => $_POST["pesan"] ?? "",
  "waktu" => date("Y-m-d H:i:s")
];

// kalau file belum ada, buat array kosong
if (!file_exists($file)) {
  file_put_contents($file, json_encode([]));
}

// baca isi JSON lama
$dataLama = json_decode(file_get_contents($file), true);

// tambah data baru
$dataLama[] = $dataBaru;

// simpan lagi ke JSON
if (file_put_contents($file, json_encode($dataLama, JSON_PRETTY_PRINT))) {
  echo json_encode(["status" => "success"]);
} else {
  echo json_encode(["status" => "error"]);
}

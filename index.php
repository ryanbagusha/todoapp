<?php
// variabel untuk menyimpan data kegiatan
$todos = [];

// fungsi untuk menyimpan data ke todo.txt
// paramater array
function simpanData($data) {
    // generate dari array ke bentuk serialize 
    $list = serialize($data);
    // memasukkan data ke todo.txt
    file_put_contents('./data/data.txt', $list);
    // redirect ke halaman index.php
    header('location: index.php');
}

// mengecek apakah todo.txt ada di direktori
if (file_exists('./data/data.txt')) {
    // menambil data di todo.txt
    $file = file_get_contents('./data/data.txt');
    // generate data ke dalam bentuk array
    $todos = unserialize($file);
}

// mengecek apakah tombol submit ditekan 
if (isset($_POST['submit'])) {
    // mengambil data yang dikirim
    $data = $_POST['todo'];
    $todos[] = [
        'todo' => $data,
        'status' => false
    ];

    // memanggil fungsi
    simpanData($todos);
}

// mengecek apakah ada yang mengirimkan data edit melalui link
if (isset($_GET['edit'])) {
    // mengambil data
    $key = $_GET['key'];

    // invert status (jika true jadi false dan kebalikannya)
    $todos[$key]['status'] = !$todos[$key]['status'];

    // memanggil fungsi
    simpanData($todos);
}

// mengecek apakah ada yang mengirimkan data hapus melalui link
if (isset($_GET['hapus'])) {
    // mengambil data
    $key = $_GET['key'];

    // menghapus data sesuai dengan key
    unset($todos[$key]);

    // memanggil fungsi
    simpanData($todos);
}

var_dump($todos);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDoApp</title>
</head>

<body>
    <h2>To Do List</h2>
    <form method="POST" action="">
        <label for="todo">Kegiatan</label>
        <input type="text" name="todo" id="todo">
        <button type="submit" name="submit">Simpan</button>
    </form>

    <ul>
        <!-- mengecek apakah $todos memiliki data -->
        <?php if ($todos != NULL): ?>
            <!-- menampilkan data -->
            <?php foreach ($todos as $key => $value): ?>
                <li>
                    <input type="checkbox" name="status" onclick="window.location.href='index.php?edit=1&key=<?= $key; ?>'" <?= ($value['status'] == 1) ?  'checked' :  '' ?>>
                    <label>
                        <?= ($value['status'] == 1) ? "<del>" . $value['todo'] . "</del>" : $value['todo'] ?>
                    </label>
                    <a href="index.php?hapus=1&key=<?= $key ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</a>
                </li>
            <?php endforeach; ?>
        <!-- jika data kosong -->
        <?php else: ?>
            <p>Data tidak ditemukan</p>
        <?php endif; ?>
    </ul>
</body>

</html>
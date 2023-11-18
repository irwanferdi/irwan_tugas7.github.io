<?php
$servername = "localhost";
$username = "root";
$dbname = "tugas7";

$conn = new mysqli($servername, $username, '', $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tambah Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tambah"])) {
    $nim = $_POST["nim"];
    $nama = $_POST["nama"];
    $program_studi = $_POST["program_studi"];

    $sql = "INSERT INTO mahasiswa (nim, nama, program_studi) VALUES ('$nim', '$nama', '$program_studi')";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil ditambahkan";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Hapus Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hapus"])) {
    $nim = $_POST["nim"];

    $sql = "DELETE FROM mahasiswa WHERE nim='$nim'";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil dihapus";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

//Edit Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
    $nim_edit = $_POST["nim_edit"];
    $nama_edit = $_POST["nama_edit"];
    $program_studi_edit = $_POST["program_studi_edit"];

    $sql = "UPDATE mahasiswa SET nama='$nama_edit', program_studi='$program_studi_edit' WHERE nim='$nim_edit'";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diupdate";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Tampilkan Data
$sql = "SELECT * FROM mahasiswa";
$result = $conn->query($sql);

// Tampilkan Data Untuk Search
$search_prodi = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_prodi_submit"])) {
    $search_prodi = $_POST["search_prodi"];
}

$sql = "SELECT * FROM mahasiswa";

// Tambahkan kondisi pencarian jika search_prodi tidak kosong
if (!empty($search_prodi)) {
    $sql .= " WHERE program_studi LIKE '%$search_prodi%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Mahasiswa</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2 {
            color: #3498db;
            text-align: center;
            padding: 20px 0;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: #ecf0f1; /* Light Grayish Blue */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        th, td {
            border: 1px solid #bdc3c7; /* Silver */
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        input[type=text] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        input[type=submit] {
            background-color: #e74c3c; /* Alizarin Red */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type=submit]:hover {
            background-color: #c0392b; /* Pomegranate Red */
        }

        .form-container {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            width: 50%;
        }
        .form-container h3 {
            text-align: center;
            color: #3498db;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container input,
        .form-container button {
            margin: 10px 0;
        }

        /* Add a confirmation style for the delete button */
        .confirmation {
            color: #e74c3c; /* Alizarin Red */
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Data Mahasiswa</h2>

    <!-- Formulir Tambah Data -->
    <div class="form-container">
        <h3>Formulir Tambah Data Mahasiswa</h3>
        <form action="index.php" method="post">
            <label for="nim">NIM:</label>
            <input type="text" name="nim" required>
            
            <label for="nama">Nama:</label>
            <input type="text" name="nama" required>
            
            <label for="program_studi">Program Studi:</label>
            <input type="text" name="program_studi" required>
            
            <input type="submit" name="tambah" value="Tambah Data">
        </form>
    </div>

    <!-- Tabel Tampilkan Data -->
    <table>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Aksi</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["nim"] . "</td>
                        <td>" . $row["nama"] . "</td>
                        <td>" . $row["program_studi"] . "</td>
                        <td>
                            <form action='index.php' method='post'>
                                <input type='hidden' name='nim' value='" . $row["nim"] . "'>
                                <input type='submit' name='hapus' value='Hapus' onclick=\"return confirm('Anda yakin ingin menghapus data?');\" class='confirmation'>
                            </form>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data mahasiswa</td></tr>";
        }
        ?>
    </table>

    <!-- Formulir Edit Data -->
    <div class="form-container">
        <h3>Formulir Edit Data Mahasiswa</h3>
        <form action="index.php" method="post">
            <label for="nim_edit">NIM:</label>
            <input type="text" name="nim_edit" value="<?php echo isset($_POST['nim']) ? $_POST['nim'] : ''; ?>" required>
            
            <label for="nama_edit">Nama:</label>
            <input type="text" name="nama_edit" value="<?php echo isset($_POST['nama']) ? $_POST['nama'] : ''; ?>" required>
            
            <label for="program_studi_edit">Program Studi:</label>
            <input type="text" name="program_studi_edit" value="<?php echo isset($_POST['program_studi']) ? $_POST['program_studi'] : ''; ?>" required>
            
            <input type="submit" name="edit" value="Simpan Perubahan">
        </form>
    </div>

    <!-- Formulir Pencarian -->
    <div class="form-container">
        <h3>Pencarian Data Mahasiswa</h3>
        <form action="index.php" method="post">
            <label for="search_prodi">Cari berdasarkan Program Studi:</label>
            <input type="text" name="search_prodi" value="<?php echo $search_prodi; ?>">
            <input type="submit" name="search_prodi_submit" value="Cari">
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>
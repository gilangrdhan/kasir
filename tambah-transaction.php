<?php
session_start();
session_regenerate_id(true);
date_default_timezone_set("Asia/Jakarta");
require_once "config/koneksi.php";



// Waktu :
$currentTime = date('Y-m-d');

// generate function (method)
function generateTransactionCode()
{
    $kode = date('dmyHis');

    return $kode;
}
// click count
if (empty($_SESSION['click_count'])) {
    $_SESSION['click_count'] = 0;
}

//Jika session nya isi, maka melempar ke dashboard.php
// if(isset($_SESSION['NAMALENGKAPNYA'])){
//     header("Location: kasir.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <title>Cashier</title>
</head>

<body style="background-image: url(image/lofi-japanese-3840x2160-14884.jpg); background-size:cover">
    <nav class="navbar navbar-expand-lg  sticky-top bg-transparent" style="backdrop-filter: blur(10px); z-index: 1000;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAltMarkup"
                aria-controls="navAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                </span>
            </button>

            <div class="collapse navbar-collapse" id="navAltMarkup">
                <div class="navbar-nav mt-2 mb-2">
                    <a href=" index.php" class="nav-link text-white">Home</a>
                    <a href="manageaccounts.php" class="nav-link text-white">Manage Accounts</a>
                    <a href="managebooks.php" class="nav-link text-white">Manage Cashier</a>
                </div>
            </div>
            <a style="border: 2px;" class="btn btn-outline-primary rounded-button"
                onclick="return confirm('Apakah Anda Yakin untuk Log-Out?')" href="controller/logout.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Garis vertikal untuk simbol power -->
                    <line x1="12" y1="2" x2="12" y2="12"></line>
                    <!-- Lingkaran di sekitar garis -->
                    <path d="M16.24 7.76a6 6 0 1 1-8.48 0"></path>
                </svg>
            </a>
        </div>
    </nav>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-4 shadow-lg" style="width: 70%; max-width: 900px;">
            <div class="card-header bg-primary opacity-50 text-center">
                <h1 class="fw-bold text-light">Add Transaction</h1>
            </div>
            <div class="card-body bg-transparent" style="backdrop-filter: blur(10px);">
                <form action="controller/transaksi-store.php" method="post">
                    <div class="mb-3">
                        <label for="kode_transaksi" class="form-label text-white">No. Transaksi</label>
                        <input style="border-radius: 20px;" class="form-control" name="kode_transaksi"
                            id="kode_transaksi" type="text" value="<?php echo 'TR-' . generateTransactionCode(); ?>"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_transaksi" class="form-label text-white">Tanggal Transaksi</label>
                        <input style="border-radius: 20px;" class="form-control" name="tanggal_transaksi"
                            id="tanggal_transaksi" type="date" value="<?php echo $currentTime; ?>" readonly>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <button style="border-radius: 20px;" class="btn btn-primary me-3" type="button"
                            id="counterBtn">Tambah</button>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Kategori</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Sisa Produk</th>
                                    <th>Harga</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <!-- Data ditambah disini -->
                            </tbody>
                            <tfoot class="text-center">
                                <tr>
                                    <th colspan="5">Total Harga</th>
                                    <td><input type="number" id="total_harga_keseluruhan" name="total_harga"
                                            class="form-control" readonly></td>
                                </tr>
                                <tr>
                                    <th colspan="5">Nominal Bayar</th>
                                    <td><input type="number" id="nominal_bayar_keseluruhan" name="nominal_bayar"
                                            class="form-control" required></td>
                                </tr>
                                <tr>
                                    <th colspan="5">Kembalian</th>
                                    <td><input type="number" class="form-control" id="kembalian_keseluruhan"
                                            name="kembalian" readonly></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="text-center mt-4">
                        <input type="submit" class="btn btn-primary" name="simpan" value="Hitung">
                        <a class="btn btn-danger" href="kasir.php">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    $query = mysqli_query($koneksi, "SELECT * from kategori_barang");
    $categories = mysqli_fetch_all($query, MYSQLI_ASSOC);
    ?>
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                //fungsi tambah baris
                const button = document.getElementById('counterBtn');
                const countDisplay = document.getElementById('countDisplay');
                const tbody = document.getElementById('tbody');
                const table = document.getElementById('table');
                let no = 0;

                button.addEventListener('click', function() {
                    no++

                    //fungsi tambah td
                    let newRow = "<tr>";
                    newRow += "<td>" + no + "</td>";
                    newRow += "<td><select class='form-control category-select' name='id_kategori[]' required>";
                    newRow += "<option value=''>--Pilih Kategori--</option>";
                    <?php foreach ($categories as $category) { ?>
                        newRow += "<option value='<?php echo $category['id'] ?>'><?php echo $category['nama_kategori'] ?></option>";
                    <?php
                    } ?>
                    newRow += "</select></td>";
                    newRow += "<td><select class='form-control item-select' name='id_barang[]' required>";
                    newRow += "<option value=''>--Pilih Barang--</option>";
                    newRow += "</select></td>";
                    newRow += "<td><input type='number' name='jumlah[]' class='form-control jumlah-input' value='0' required></td>";
                    newRow += "<td><input type='number' name='sisa_produk[]' class='form-control' readonly></td>";
                    newRow += "<td><input type='number' name='harga[]' class='form-control' readonly></td>";
                    newRow += "<td><input type='number' name='sub_total[]' class='form-control sub-total' readonly></td>";
                    newRow += "</tr>";
                    tbody.insertAdjacentHTML('beforeend', newRow);

                    attachCategoryChangeListener();
                    attachItemChangeListener();
                    attachJumlahChangeListener();

                   
                    
                });

                //fungsi menampilkan barang based on kategori barang
                function attachCategoryChangeListener(){
                    const categorySelects = document.querySelectorAll('.category-select');
                    categorySelects.forEach(select => {
                        select.addEventListener('change', function(){
                            const categoryId = this.value;
                            const itemSelect = this.closest('tr').querySelector('.item-select');
                                if (categoryId) {
                                fetch(`controller/get-product-dari-category.php?id_kategori=${categoryId}`)
                                .then(response => response.json())
                                .then(data => {
                                    itemSelect.innerHTML = "<option value=''>--Pilih Barang--</option>";
                                    data.forEach(item => {
                                        itemSelect.innerHTML += `<option value='${item.id}'>${item.nama_barang}</option>`;
                                    });
                                });
                                } else {
                                    itemSelect.innerHTML = "<option value=''>--Pilih Barang--</option>";
                                }
                        });
                    });
                }


                //untuk menampilkan qty dan harga
                function attachItemChangeListener() {
                    const itemSelects = document.querySelectorAll('.item-select');
                    itemSelects.forEach(select => {
                        select.addEventListener('change', function(){
                            const itemId = this.value;
                            const row = this.closest('tr');
                            const sisaProdukInput = row.querySelector('input[name="sisa_produk[]"]');
                            const hargaInput = row.querySelector('input[name="harga[]"]');

                            if (itemId) {
                                fetch('controller/get-barang-details.php?id_barang=' + itemId)
                                    .then(response => response.json())
                                    .then(data => {
                                        sisaProdukInput.value = data.qty;
                                        hargaInput.value = data.harga;
                                    })
                            } else {
                                sisaProdukInput.value = '';
                                hargaInput.value = '';
                            }
                        });
                    });
                }


                const totalHargaKeseluruhan = document.getElementById('total_harga_keseluruhan');
                // const subTotal = document.querySelectorAll('.sub-total');
                const nominalBayarKeseluruhanInput = document.getElementById('nominal_bayar_keseluruhan');
                const kembalianKeseluruhanInput = document.getElementById('kembalian_keseluruhan');


                function attachJumlahChangeListener(){
                    const jumlahInputs = document.querySelectorAll('.jumlah-input');
                    jumlahInputs.forEach(input => {
                        input.addEventListener('input', function() {
                            const row = this.closest('tr');
                            const sisaProdukInput = row.querySelector('input[name="sisa_produk[]"]');
                            const hargaInput = row.querySelector('input[name="harga[]"]');
                            const nominalBayarInput = document.getElementById('nominal_bayar_keseluruhan');
                            const kembalianInput = document.getElementById('kembalian_keseluruhan');

                            const jumlah = parseInt(this.value) || 0;
                            const sisaProduk = parseInt(sisaProdukInput.value) || 0;
                            const harga = parseFloat(hargaInput.value) || 0;

                            if (jumlah > sisaProduk) {
                                alert("Jumlah tidak boleh melebihi sisa produk");
                                this.value = sisaProduk;
                                return;
                            }
                            updateTotalKeseluruhan();
                        });
                    });

                    
                }

                function updateTotalKeseluruhan() {
                    let total = 0;
                    let totalKeseluruhan = 0;
                    const jumlahInput = document.querySelectorAll('.jumlah-input');
                    jumlahInput.forEach(input =>{
                        const row = input.closest('tr');
                        const hargaInput = row.querySelector('input[name="harga[]"]');
                        const harga = parseFloat(hargaInput.value) || 0;
                        const jumlah = parseInt(input.value) || 0;

                        const subTotal = row.querySelector('.sub-total');
                        total = jumlah * harga;
                        subTotal.value = total;

                    });

                    const subTotal = document.querySelectorAll('.sub-total');
                    subTotal.forEach(totalItem => {
                        let subTotal = parseFloat(totalItem.value) || 0;
                        totalKeseluruhan += subTotal;
                    })

                    totalHargaKeseluruhan.value = totalKeseluruhan;
                    
                }

                nominalBayarKeseluruhanInput.addEventListener('input', function(){
                    const nominalBayar = parseFloat(this.value) || 0;
                    const totalHarga = parseFloat(totalHargaKeseluruhan.value) || 0;
                    
                    if(nominalBayar >=totalHarga){
                        let kembalian = nominalBayar - totalHarga;
                        kembalianKeseluruhanInput.value = kembalian;
                    } else if (nominalBayar ==0){
                        kembalianKeseluruhanInput.value = 0;
                    }
                });

                
            
            });
        </script>
    <footer class=" text-center border-top border-dark border-2 fixed-bottom p-3 bg-transparent text-black" style="backdrop-filter:blur(10px);">
        Copyright &copy; 2024 Lawson - Jakarta Pusat.
    </footer>

</body>

</html>
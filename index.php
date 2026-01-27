<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Toko Online</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <nav class="navbar">
        <a href="#" class="logo"><strong>TOKO</strong>ONLINE</a>
        <ul class="nav-link">
            <li><a href="index.php">Home</a></li>
            <li><a href="#Promo">Promo</a></li>
            <li><a href="#produk">Produk</a></li>
            <li><a href="#contact">Hubungi Kami</a></li>
        </ul>
    </nav>

    <div class="content" id="Promo">
        <div class="content-text">
            <h1>PROMO AWAL TAHUN DISKON Hingga 50%</h1>
            <p>
                <span class="red">*</span> Selama periode 1 Januari 2026 s/d 28
                Febuari 2026
            </p>
            <button class="button btn">
                <a href="#produk" style="color: white; text-decoration: none">Lihat Produk</a>
            </button>
        </div>
        <div class="content-image">
            <img src="img/tas.png" alt="Promo Products" />
        </div>
    </div>

    <section id="produk">
        <h2 class="produk-title">Produk Kami</h2>
        <div class="products">
            <div class="card">
                <img src="img/produk.jpg" alt="Produk" />
                <div class="card-body">
                    <h3>Informasi Nama Produk</h3>
                    <p class="price-old">Rp 40.000</p>
                    <p class="price-new">Rp 20.000</p>
                </div>
            </div>
            <div class="card">
                <img src="img/produk.jpg" alt="Produk" />
                <div class="card-body">
                    <h3>Informasi Nama Produk</h3>
                    <p class="price-old">Rp 40.000</p>
                    <p class="price-new">Rp 20.000</p>
                </div>
            </div>
            <div class="card">
                <img src="img/produk.jpg" alt="Produk" />
                <div class="card-body">
                    <h3>Informasi Nama Produk</h3>
                    <p class="price-old">Rp 40.000</p>
                    <p class="price-new">Rp 20.000</p>
                </div>
            </div>
            <div class="card">
                <img src="img/produk.jpg" alt="Produk" />
                <div class="card-body">
                    <h3>Informasi Nama Produk</h3>
                    <p class="price-old">Rp 40.000</p>
                    <p class="price-new">Rp 20.000</p>
                </div>
            </div>
            <div class="card">
                <img src="img/produk.jpg" alt="Produk" />
                <div class="card-body">
                    <h3>Informasi Nama Produk</h3>
                    <p class="price-old">Rp 40.000</p>
                    <p class="price-new">Rp 20.000</p>
                </div>
            </div>
            <div class="card">
                <img src="img/produk.jpg" alt="Produk" />
                <div class="card-body">
                    <h3>Informasi Nama Produk</h3>
                    <p class="price-old">Rp 40.000</p>
                    <p class="price-new">Rp 20.000</p>
                </div>
            </div>
            <div class="card">
                <img src="img/produk.jpg" alt="Produk" />
                <div class="card-body">
                    <h3>Informasi Nama Produk</h3>
                    <p class="price-old">Rp 40.000</p>
                    <p class="price-new">Rp 20.000</p>
                </div>
            </div>
            <div class="card">
                <img src="img/produk.jpg" alt="Produk" />
                <div class="card-body">
                    <h3>Informasi Nama Produk</h3>
                    <p class="price-old">Rp 40.000</p>
                    <p class="price-new">Rp 20.000</p>
                </div>
            </div>
        </div>
    </section>

    <div class="popup-overlay" id="popupNotification">
        <div class="popup-content">
            <div class="popup-icon">✓</div>
            <h3>Pesan Terkirim!</h3>
            <p>
                Terima kasih telah menghubungi kami. Kami akan segera merespons pesan Anda.
            </p>
            <button class="popup-close-btn" onclick="closePopup()">OK</button>
        </div>
    </div>

    <div class="contact-footer" id="contact">
        <h2>HUBUNGI KAMI</h2>

        <form id="contactForm">
            <div class="form-container">

                <div class="form-group">
                    <label for="nama">NAMA LENGKAP</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="NAMA LENGKAP"
                        required />
                </div>

                <div class="form-group">
                    <label for="email">ALAMAT EMAIL</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="ALAMAT EMAIL"
                        required />
                </div>

                <div class="form-group">
                    <label for="phone">NO. HANDPHONE</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        placeholder="NO. HANDPHONE"
                        required />
                </div>

                <div class="form-group">
                    <label for="kota">ASAL KOTA</label>
                    <input
                        type="text"
                        id="kota"
                        name="kota"
                        placeholder="ASAL KOTA"
                        required />
                </div>

                <div class="form-group full-width">
                    <label for="pesan">ISI PESAN</label>
                    <textarea
                        id="pesan"
                        name="pesan"
                        placeholder="ISI PESAN"
                        required></textarea>
                </div>

            </div>

            <div class="button-container">
                <button type="submit" class="submit-btn">KIRIM PESAN</button>
                <button type="button" class="top-btn" onclick="window.scrollTo({top:0, behavior:'smooth'})">
                    TOP
                </button>
            </div>
        </form>
    </div>


    <script>
        const form = document.getElementById("contactForm");
        const popup = document.getElementById("popupNotification");

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            fetch("backend/save_message.php", {
                    method: "POST",
                    body: new FormData(form),
                })
                .then((res) => res.json())
                .then((data) => {
                    if (data.status === "success") {
                        showPopup();
                        form.reset();
                    } else {
                        alert("Gagal mengirim pesan");
                    }
                })
                .catch((err) => {
                    alert("Error koneksi");
                    console.error(err);
                });
        });

        function showPopup() {
            popup.style.display = "flex";
        }

        function closePopup() {
            popup.style.display = "none";
        }
    </script>


</body>

</html>
TUGAS RUTIN 7
SISTEM LOGIN DAN REGISTRASI MENGGUNAKAN PHP NATIVE DAN JSON

==================================================
1. DESKRIPSI PROYEK
==================================================

Proyek ini merupakan aplikasi web sederhana untuk mengelola
registrasi akun, login pengguna, dan profil pengguna menggunakan
PHP Native serta JSON sebagai media penyimpanan data.

Aplikasi dijalankan melalui server lokal XAMPP dan menggunakan
HTML serta CSS untuk tampilan antarmuka.

==================================================
2. TUJUAN
==================================================

1. Memahami penggunaan PHP Native dalam pengembangan web.
2. Membuat sistem registrasi dan login pengguna.
3. Menerapkan session untuk mengelola status login.
4. Menggunakan file JSON untuk menyimpan data pengguna.
5. Menerapkan hashing password untuk melindungi kata sandi.
6. Membuat fitur edit profil dan logout.
7. Menerapkan fitur Remember Me menggunakan cookie dan token.

==================================================
3. TEKNOLOGI YANG DIGUNAKAN
==================================================

- PHP Native
- HTML
- CSS
- JSON
- XAMPP
- Apache
- Visual Studio Code

==================================================
4. FITUR APLIKASI
==================================================

1. Halaman awal
   Menampilkan halaman utama dan navigasi aplikasi.

2. Registrasi
   Pengguna dapat membuat akun baru dengan nama, email, dan
   password. Sistem memvalidasi data dan mencegah email duplikat.

3. Login
   Pengguna masuk menggunakan email dan password yang terdaftar.

4. Dashboard
   Menampilkan informasi pengguna yang sedang login.

5. Edit profil
   Pengguna dapat memperbarui informasi profil sesuai fitur
   yang tersedia pada aplikasi.

6. Logout
   Mengakhiri sesi login pengguna.

7. Remember Me
   Memungkinkan pengguna tetap login ketika browser dibuka
   kembali selama cookie dan token masih valid.

8. Validasi dan keamanan
   Sistem menggunakan password hashing, session, validasi email,
   serta pengamanan output HTML.

==================================================
5. STRUKTUR FOLDER
==================================================

tugas_rutin7/
|
|-- index.php
|-- login.php
|-- register.php
|-- dashboard.php
|-- edit_profile.php
|-- logout.php
|-- auth.php
|-- style.css
|-- README.txt
|
|-- data/
    |-- users.json

==================================================
6. CARA MENJALANKAN APLIKASI
==================================================

1. Instal dan buka XAMPP.
2. Jalankan Apache melalui XAMPP Control Panel.
3. Letakkan folder tugas_rutin7 di dalam folder htdocs.
4. Pastikan folder data tersedia dan file users.json dapat
   dibaca serta ditulis oleh PHP.
5. Buka browser.
6. Masukkan alamat berikut:

   http://localhost/tugas_rutin7/

7. Daftar akun melalui halaman registrasi.
8. Login menggunakan akun yang telah dibuat.
9. Gunakan dashboard untuk melihat profil dan mencoba fitur
   edit profil, Remember Me, serta logout.

==================================================
7. PENYIMPANAN DATA
==================================================

Data akun disimpan dalam file:

data/users.json

File ini digunakan untuk membaca dan menyimpan informasi pengguna.
Password disimpan dalam bentuk hash, bukan sebagai teks biasa.

Jangan menghapus atau mengubah isi file users.json secara
sembarangan karena dapat memengaruhi akun yang sudah terdaftar.

==================================================
8. PENGUJIAN APLIKASI
==================================================

Pengujian yang dilakukan meliputi:

1. Membuka halaman awal.
2. Mendaftarkan akun baru.
3. Memeriksa validasi email.
4. Memastikan email duplikat ditolak.
5. Memastikan password tersimpan dalam bentuk hash.
6. Login dengan data yang benar.
7. Memastikan login dengan password salah ditolak.
8. Memastikan dashboard terlindungi dari akses tanpa login.
9. Mengubah profil pengguna.
10. Melakukan logout.
11. Menguji fitur Remember Me.

Status akhir setiap pengujian dicatat berdasarkan hasil
pengujian langsung pada aplikasi.

==================================================
9. CATATAN
==================================================

- Apache harus berjalan ketika aplikasi digunakan melalui XAMPP.
- Folder data harus dapat ditulis oleh PHP.
- Jangan membagikan data akun atau token autentikasi.
- Remember Me sebaiknya diuji kembali setelah perubahan kode.
- Aplikasi ini merupakan proyek pembelajaran dan perlu
  pemeriksaan keamanan tambahan sebelum digunakan secara publik.

==================================================
AKHIR README
==================================================

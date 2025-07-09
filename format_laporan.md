# UAS Pengembangan Web – Debug REST API CI4

# Nama : Furqon Hakiki

# NIM : 231080200005

# Kelas : B1

# Semester : 4

## Tugas:

- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug

| No  | File                               | Baris | Bug                                    | Solusi                                        |
| --- | ---------------------------------- | ----- | -------------------------------------- | --------------------------------------------- |
| 1 | app/Controllers/AuthController.php | 21    | Tidak ada validasi input saat register | Tambahkan validasi `isset()` dan format email |
| 2 | app/Controllers/AuthController.php | 27 | Password tidak di-hash | Gunakan `password_hash($data['password'], PASSWORD_DEFAULT)'|
| 3 | app/Controllers/AuthController.php | 32 | Mengembalikan password dalam response | Jangan tampilkan password, hanya kirim `id`,`name`,`email`|
| 4 | app/Controllers/AuthController.php | 39–40 | Tidak ada validasi input saat login | Tambahkan pengecekan `if (!$email || !$password)`|
| 5 | app/Controllers/AuthController.php | 45 | Perbandingan password plain text | Ganti dengan `password_verify($password, $user['password'])`|
| 6 | app/Controllers/AuthController.php | 60 | Fungsi refresh belum diimplementasikan | Tambahkan parsing token dan generate token baru|
| 7 | app/Libraries/JWTLibrary.php | 6 | Hardcoded secret key | Ambil secret key dari file `.env` menggunakan `getenv('JWT_SECRET')` |
| 8 | app/Libraries/JWTLibrary.php | 20 | Tidak ada validasi struktur token | Tambahkan pengecekan jumlah bagian token (`count($parts) !== 3`) |
| 9 | app/Libraries/JWTLibrary.php | 27 | Tidak ada verifikasi signature | Hitung ulang signature dan cocokkan dengan yang dikirim |
| 10 | app/Filters/JWTAuthFilter.php | 17 | Salah parsing format token | Gunakan regex `preg_match('/Bearer\s(\S+)/', ...)` untuk ambil token |
| 11 | app/Filters/JWTAuthFilter.php | 26 | Tidak menyimpan data user ke request | Tambahkan `$request->user = $decoded;` setelah token berhasil didecode |

## Uji dengan Postman:

- POST /login
- POST /register
- GET /users (token diperlukan)

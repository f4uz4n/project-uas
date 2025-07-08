# UAS Pengembangan Web – Debug REST API CI4

## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug

| No | File                        | Baris | Bug                                         | Solusi                                                       |
|----|-----------------------------|-------|----------------------------------------------|---------------------------------------------------------------|
| 1  | app/Config/Routes.php       | 13    | Missing auth filter pada refresh endpoint    | Tambahkan filter `'jwt'` pada route `/refresh`               |
| 2  | app/Config/Routes.php       | 17    | Inconsistent API prefix                      | Ganti prefix menjadi `api/users` agar konsisten               |
| 3  | app/Config/Routes.php       | 34    | Wrong filter name untuk tasks                | Ganti filter dari `'auth'` menjadi `'jwt'`                    |
| 4  | app/Config/Database.php     | 20    | Database might not exist                     | Sudah pakai database `task_management` dengan user `root`    |
| 6  | app/Controllers/AuthController.php | 28 | No input validation pada register            | Tambahkan validasi `isset()` untuk name, email, password     |
| 7  | app/Controllers/AuthController.php | 32 | Password tidak di-hash                       | Gunakan `password_hash()` saat menyimpan password            |
| 8  | app/Controllers/AuthController.php | 40 | Mengembalikan password dalam response        | Hilangkan password dari data response register               |
| 9  | app/Controllers/AuthController.php | 48 | No input validation pada login               | Tambahkan validasi `isset()` untuk email dan password        |
| 10 | app/Controllers/AuthController.php | 55 | Plain text password comparison               | Ganti ke `password_verify()` untuk autentikasi login         |
| 29 | app/Models/UserModel.php   | 20    | No validation rules                          | Tambahkan `$validationRules` untuk name, email, password     |
| 30 | app/Models/UserModel.php   | 23    | No timestamp handling                        | Tambahkan `protected $useTimestamps = true;`                 |
| 31 | app/Models/UserModel.php   | 26    | Weak password hashing (MD5)                  | Hapus `beforeInsert`/`hashPassword()` MD5 callback lama       |

## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)
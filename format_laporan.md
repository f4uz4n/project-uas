# UAS Pengembangan Web – Debug REST API CI4
Nama : M Rafly Rayhan Maulana
Nim : 231080200178
## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug
| No | File                               | Baris | Bug                                       | Solusi                                                  |
| -- | ---------------------------------- | ----- | ----------------------------------------- | ------------------------------------------------------- |
| 1  | app/Config/Routes.php              | 10    | Missing auth filter pada refresh endpoint | Tambahkan `['filter' => 'jwt']` di route refresh        |
| 2  | app/Config/Routes.php              | 5     | Inconsistent API prefix                   | Gunakan prefix `api/` secara konsisten                  |
| 3  | app/Config/Routes.php              | 15    | Wrong filter name untuk tasks             | Ganti filter dari `auth` ke `jwt`                       |
| 4  | .env                               | 7     | `JWT_SECRET` kosong                       | Tambahkan `JWT_SECRET=abc123`                           |
| 5  | app/Controllers/AuthController.php | 22    | No input validation pada register         | Gunakan `$this->validate()` sebelum insert              |
| 6  | app/Controllers/AuthController.php | 28    | Password tidak di-hash                    | Gunakan `password_hash()`                               |
| 7  | app/Controllers/AuthController.php | 33    | Mengembalikan password dalam response     | Hapus/`unset($userData['password'])` sebelum kirim      |
| 8  | app/Controllers/AuthController.php | 41    | No input validation pada login            | Tambahkan validasi `email`, `password`                  |
| 9  | app/Controllers/AuthController.php | 48    | Plain text password comparison            | Ganti dengan `password_verify()`                        |
| 10 | app/Controllers/AuthController.php | 57    | Missing refresh implementation            | Implementasi decoding dan re-encoding JWT               |
| 11 | app/Models/UserModel.php           | 13    | No validation rules                       | Tambahkan properti `$validationRules`                   |
| 12 | app/Models/UserModel.php           | 9     | No timestamp handling                     | Aktifkan `$useTimestamps = true`                        |
| 13 | app/Models/UserModel.php           | -     | Weak password hashing (MD5)               | Gunakan `password_hash()` di controller                 |
| 14 | app/Filters/JWT.php                | 15    | Wrong token format handling               | Tangani format token Bearer                             |
| 15 | app/Filters/JWT.php                | 20    | Not setting user data in request          | Set user info ke dalam request (misal `$request->user`) |
| 16 | app/Libraries/JWTLibrary.php       | 5     | Hardcoded secret key                      | Ambil dari `.env` dengan `getenv()`                     |
| 17 | app/Libraries/JWTLibrary.php       | 20    | No proper token validation                | Validasi expiry (`exp`) dan struktur token              |
| 18 | app/Libraries/JWTLibrary.php       | 25    | No signature verification                 | Verifikasi signature JWT                                |
| 19 | database/Migrations                | 30    | Missing FK constraint pada projects       | Tambahkan `foreign key (user_id)`                       |
| 20 | database/Migrations                | 45    | Missing FK constraint pada tasks          | Tambahkan `foreign key (project_id, user_id)`           |

## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)

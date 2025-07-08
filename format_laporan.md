# UAS Pengembangan Web – Debug REST API CI4

## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug
| No | File                     | Baris | Bug                           | Solusi                          |
|----|--------------------------|-------|-------------------------------|----------------------------------|
| 1  | app/Controllers/Auth.php | 22    | Salah nama helper             | Tambah `helper('jwt')`            |
| 2  | .env                     | 7     | `JWT_SECRET` kosong           | Tambahkan `JWT_SECRET=abc123`     |
| 3  | app/Controllers/Auth.php | 31    | Tidak ada hash password       | Tambahkan Hash Password           |
| 4  | app/Config/Database.php  | 32    | Tidak ada Username pada DB    | Tambahkan Username DB             |
| 5  | app/Config/Database.php  | 85    | Missing test database config  | Tambahkan DB config dari .env     |
| 6  | app/Models/UserModel.php | 17    | No date handling              | Tambahkan date timestamp          |
| 7  | app/Models/UserModel.php | 26    | Weak password hashing         | Perubahan metode hash             |
| 8  | Controllers/ProjectController.php | 17 | Shows all projects instead of user's only | Tambahkan variabel mengambil user id |
| 9  | app/Config/Routes.php    | 34    | Wrong filter name untuk tasks | Perubahan filter name menjadi jwt |
| 10 | app/Config/Routes.php    | 17    | Inconsistent API prefix       | Penambahan Api/ pada routes       |
| 11 | app/Config/Routes.php    | 13    | Missing auth filter pada refresh endpoint | Penambahan filter     |
| 12 | app/Controllers/AuthController.php | 28 | No input validation pada register | Penambahan validasi    |
| 13 | app/Library/JWTLibrary.php | 7 | Hardcoded secret key | Penambahan keamanan agar mengambil dari .env |
| 14 | app/Controllers/UserController.php | 23 | No input validation for ID | Penambahan validasi ID|
| 15 | app/Controllers/UserController.php | 34 | Returning sensitive data | Hapus data sensitif dari output |




## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)
# UAS Pengembangan Web – Debug REST API CI4

## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug
No  | File                                 | Baris | Bug                                          | Solusi
----|--------------------------------------|-------|----------------------------------------------|--------------------------------------------------------------
1   | app/Config/Routes.php                | 10    | Missing auth filter pada route `refresh`     | Tambahkan ['filter' => 'jwt'] di route `refresh`

2   | app/Config/Routes.php                | 15    | Prefix `users` tidak konsisten dengan `api/` | Ubah menjadi `api/users`

3   | app/Config/Routes.php                | 25    | Filter `auth` salah pada group `tasks`       | Ubah jadi ['filter' => 'jwt']

4   | app/Controllers/AuthController.php   | 21    | Input tidak divalidasi saat register         | Tambahkan validasi $this->validate([...])

5   | app/Controllers/AuthController.php   | 26    | Password tidak di-hash                       | Gunakan password_hash() sebelum insert

6   | app/Controllers/AuthController.php   | 31    | Password dikembalikan di response            | unset($userData['password']) sebelum return

7   | app/Controllers/AuthController.php   | 40    | Login tidak validasi input                   | Tambahkan validasi email dan password

8   | app/Controllers/AuthController.php   | 44    | Pembandingan password plaintext              | Gunakan password_verify()

9   | app/Models/UserModel.php             | 14    | Tidak ada validation rules                   | Tambahkan $validationRules

10  | app/Models/UserModel.php             | 17    | Timestamps tidak diaktifkan                  | protected $useTimestamps = true

11  | app/Models/UserModel.php             | 23    | Hashing password pakai md5                   | Ganti dengan password_hash()

12  | app/Controllers/UserController.php   | 12    | Tidak ada pagination di index()              | Gunakan findAll($limit, $offset) dengan param dari request

13  | app/Controllers/UserController.php   | 18    | Tidak validasi ID user                       | Tambahkan is_numeric($id)

14  | app/Controllers/UserController.php   | 23    | Mengembalikan password dalam response        | unset($user['password'])

15  | app/Controllers/UserController.php   | 31    | Tidak ada authorization update user lain     | Cek user_id dari JWT sebelum update

16  | app/Controllers/UserController.php   | 36    | Tidak validasi input update user             | Tambahkan $this->validate([...])

17  | app/Controllers/UserController.php   | 46    | Tidak ada authorization saat delete          | Cek user_id dari JWT sebelum hapus

18  | app/Filters/JWTAuthFilter.php        | 18    | Format token salah ditangani                 | Gunakan str_replace('Bearer ', '', $header->getValue())

19  | app/Filters/JWTAuthFilter.php        | 24    | Tidak set data user ke request               | Tambahkan $request->user = $decoded

20  | app/Libraries/JWTLibrary.php         | 6     | Secret key ditulis langsung (hardcoded)      | Ambil dari getenv('JWT_SECRET')

21  | app/Libraries/JWTLibrary.php         | 21    | Tidak validasi jumlah part JWT               | Cek jumlah part harus 3

22  | app/Libraries/JWTLibrary.php         | 27    | Signature token tidak diverifikasi           | Tambahkan validasi signature dengan hash_equals()
## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)
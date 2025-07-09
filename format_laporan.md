# UAS Pengembangan Web – Debug REST API CI4


NAMA: BAGAS FAIS RIZKI BEGI
NIM: 231080200013
Kelas: 4B1
## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug
| No | File                              | Baris | Bug                                                 | Solusi                                                        -|
|----|-----------------------------------|-------|-----------------------------------------------------|----------------------------------------------------------------|
| 1  | app/Config/Routes.php             | 13    | Bug #1 Tidak terdapat authentication filter         | Tambah ['filter' => 'jwt']                                     |
| 2  | app/Config/Routes.php             | 20    | Bug #2 Tidak ada api/ di route ini                  | Tambah 'api/users'                                             |
| 3  | app/Config/Routes.php             | 40    | Bug #3 Salah Nama filter                            | Ganti dari auth jadi 'jwt'                                     |
| 4  | app/Controllers/AuthController.php| 25    | Bug #6 Tidak ada validasi input saat register       | Tambah $this->failValidationErrors 'Name, email, and password' |
| 5  | app/Controllers/AuthController.php| 31    | Bug #7 Password Tidak dihash                        | Tambah password_hash($data['password'], PASSWORD_BCRYPT)      |
| 6  | app/Controllers/AuthController.php| 40    | Bug #8 Mengembalikan Password di respon tidak aman  | Ubah jadi password unset($userData['password']);              |
| 7  | app/Controllers/AuthController.php| 70-76 | Bug #9 Tidak ada validasi input saat login          | Tambah return $this->failValidationErrors                      |
| 8  | app/Controllers/AuthController.php| 70-76 | Bug #10 membandingkan password secara langsung      | Tambah atau gunakan password_verify()                         |
| 9  | app/Controllers/ProjectController.php| 16 | Bug #18 Menampilkan semua proyek tanpa filter user  | Tambah $userId = $this->getUserIdFromToken();()  ambil user dari token jwt|
| 10  | app/Controllers/ProjectController.php| 28 | Bug #20 Not setting user_id from JWT token    | Tambahkan user_id dari JWT $data['user_id'] = $this->getUserIdFromToken();|

Menampilkan semua proyek tanpa filter user
## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)
# UAS Pengembangan Web – Debug REST API CI4

# Nama : LONY JULYS NABAWI
# NIM  : 231080200058
# KELAS: 4 B1

## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug
| No | File                     | Baris | Bug                        | Solusi                          |
|----|--------------------------|-------|----------------------------|----------------------------------|
| 1  | app/Controllers/Auth.php | 22    | Salah nama helper          | Tambah `helper('jwt')`          |
| 2  | .env                     | 7     | `JWT_SECRET` kosong        | Tambahkan `JWT_SECRET=abc123`   |
| 3  | app/Config/Routes.php    | 13    | Kehilangan Filter authentikasi rute api/auth/refresh | Tambahkan 'JWT' rute refresh|
|4   | app/Config/Routes.php    |  19   |Tidak ada API prefix untuk rute users (tidak ada / api) | Pindahkan users  dalam grup api|
|5   | app/Config/Routes.php    | 38    |Salah nama api/task          | Ganti nama 'auth' menjadi 'JWT' |
|6   | app/Controllers/TaskController.php | 15  |    Tidak ada filter pengguna pada metode index() | Mengimplementasikan logika filter tugas berdasarkan ID |
|7   | app/Controllers/TaskController.php | 24  |Tidak ada validasi untuk kolom wajib  pada metode create() | Tambahkan aturan validasi|
|8   | app/Controllers/TaskController.php | 24  |Tidak ada validasi kepemilikan proyek pada metode create(). | Tambahkan logika untuk memverifikasi akses ke proyek|
|9   | app/Controllers/TaskController.php | 51  | Tidak ada validasi untuk pembaruan status pada metode update($id) |Tambahkan validasi input untuk memastikan data pembaruan status|
|10  | app/Config/Routes.php     |  13   | Rute /api/auth/refresh kehilangan filter autentikasi JWT | Tambahkan filter 'filter' => 'jwt' pada rute api/auth/refresh. |
|11  |  app/Config/Routes.php  | Grup Users |  prefix API: Grup rute users tidak menggunakan prefix /api/. | Pindahkan rute users ke dalam grup /api/ untuk konsistensi |
|12  | app/Config/Routes.php   | Baris grup 'api/tasks' |  Nama salah ('auth') | Mengganti nama filter dari 'auth' menjadi 'jwt' |


## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)
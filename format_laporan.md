# UAS Pengembangan Web – Debug REST API CI4

## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug
| No | File                     | Baris | Bug                        | Solusi                          |
|----|--------------------------|-------|-----------------------------|----------------------------------|
| 1 | app/Controller/routes.php| 10    | Missing auth filter pada refresh endpoint|  tambahkan "$routes->post('auth/refresh', 'AuthController::refresh', ['filter' => 'auth']);"                                |
| 2  |app/Controller/routes.php| 17     | Inconsistent API prefix | Tambahkan semua endpoint API menggunakan prefix yang konsisten, misal api/   |
| 3 |app/Controller/routes.php| 34     |Wrong filter name untuk tasks|Pastikan filter yang digunakan pada route tasks sesuai dengan nama filter yang ada,'auth'|
| 4 |database.php| 78     |Database might not exist|Tambahkan  parent::__construct();
    if (!@mysqli_connect($this->default['hostname'], $this->default['username'], $this->default['password'], $this->default['database']))|
| 5 |adatabase.php| 56-76     |Missing test database config|tambahkan konfigurasi untuk testing|
| 6 |AuthController.php| 34     |No input validation pada register|Tambahkan validasi input pada method register:|
| 7 |aAuthController.php| 46    | Password tidak di-hash|tambahkan 'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),|
| 8 |aAuthController.php| 56    | Mengembalikan password dalam response|tambahkan  unset($userData['password']);|
| 9 |aAuthController.php| 73    | No input validation pada login|tambahkan  $validation = \Config\Services::validation();|
| 10 |aAuthController.php| 86    | Plain text password comparison|tambahkan  if ($user && password_verify($password, $user['password']))|

## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)
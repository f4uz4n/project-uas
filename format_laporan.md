# UAS Pengembangan Web – Debug REST API CI4

## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug
| No | File                     | Baris | Bug                        | Solusi                          |
|----|--------------------------|-------|-----------------------------|----------------------------------|
| 1  | app/Controllers/Auth.php | 22    | Salah nama helper          | Tambah `helper('jwt')`          |
| 2  | .env                     | 7     | `JWT_SECRET` kosong        | Tambahkan `JWT_SECRET=abc123`   |
| …  | …                        | …     | …                           | …                                |

## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)
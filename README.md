# Rest Antrian Poli

Requiredment
_____
- PHP 7.1++
- CodeIgniter 3.1.10

## Documentation
____
  **{Url}/api/ruangan**
----
- **POST** : untuk insert Ruangan
  | Param | tipe | 
  | ----|----|
  |  ruangan_nama   | varchar |
  ----
- **GET** : untuk cari Ruangan 

  | Param | tipe |
  | ----|----|
  |  ruangan_nama | varchar | 
  |  id_ruangan   | varchar |
  |  is_ready     | "n" ="ruangan dipakai" , "y" = "ruangan tersedia" varchar |
  -----

- **PUT** : untuk update Ruangan
  | Param | tipe |
  | ---- |----|
  |  ruangan_nama | varchar | 
  |  id_ruangan   | varchar |
  |  is_ready     | "n" ="ruangan dipakai" , "y" = "ruangan tersedia" varchar |
  -----

- **Delete** : untuk update Ruangan 
  | Param  | tipe |
  | ----|---- |
  |  id_ruangan  | varchar |
  ----
**{Url}/api/jadwal_dokter**
----
- **POST** : untuk insert jadwal_dokter
  | Param | tipe |
  | ----|----|
  |  hari   | int |
  |  id_poli   | varchar |
  |  id_dokter   | varchar |
  |  jam_mulai   | varchar |
  |  jam_selesai   | varchar |
  |  jam_selesai   | varchar |
  |  id_ruangan  | varchar |
  ----
- **GET** : untuk cari jadwal_dokter 

  | Param | tipe |
  | ----|----|
  |  hari   | int |
  |  id_poli   | varchar |
  ----- 

- **PUT** : untuk update jadwal_dokter
  | Param | tipe |
  | ---- |----|
  |  hari   | int |
  |  id_jadwal_dokter   | varchar |
  |  id_poli   | varchar |
  |  id_dokter   | varchar |
  |  jam_mulai   | varchar |
  |  jam_selesai   | varchar |
  |  jam_selesai   | varchar |
  |  id_ruangan  | varchar |
  
  -----

- **Delete** : untuk update jadwal_dokter 
  | Param  | tipe |
  | ----|---- |
  |  id_jadwal_dokter  | varchar |
  ----

**{Url}/api/jadwal_dokter/hari**
----
- **GET** : untuk cari hari 
  -----
**{Url}/api/jadwal_dokter/poli**
----
- **GET** : untuk cari data poli 
-----
**{Url}/api/jadwal_dokter/dokter**
  ----
- **GET** : untuk cari data dokter 
| Param | tipe |
  | ---- |----|
  |  hari   | int |
  |  id_poli   | varchar |
-----
**{Url}/api/monitor/pakai_ruangan**
  ----
- **PUT** : untuk  setup ruangan mana yang mau di pakai 
| Param | tipe |
  | ---- |----|
  |  hari   | int |
  |  id_poli   | varchar |
  |  id_dokter   | varchar |
  |  id_ruangan  | varchar |
----
**{Url}/api/monitor/kosongkan_ruangan**
  ----
- **PUT** : untuk  setup ruangan mana yang mau di kosongkan  
| Param | tipe |
  | ---- |----|
  |  hari   | int |
  |  id_poli   | varchar |
  |  id_dokter   | varchar |
  |  id_ruangan  | varchar |
----
**{Url}/api/monitor/antrian_all**
  ----
- **GET** : untuk mengambil semua antrian   













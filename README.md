# Tugas Besar 3 Strategi Algoritma IF2211
> Penerapan String Matching dan Regular Expression dalam Pembangunan Deadline Reminder Assistant

## Daftar Isi
* [Kontributor](#kontributor)
* [Penjelasan](#penjelasan)
* [Spesifikasi](#spesifikasi)
* [Daftar Kata Penting](#daftar-kata-penting)
* [Cara Pengunaan](#cara_penggunaan)

## Kontributor
* Arsa Daris Gintara (13519037)
* Dwianditya Hanif Raharjanto (13519046)
* Fabian Savero Diaz Pranoto (13519140)

## Penjelasan
Dalam tugas besar ini, Anda akan diminta untuk membangun sebuah chatbot sederhana yang
berfungsi untuk membantu mengingat berbagai deadline, tanggal penting, dan task-task
tertentu kepada user yang menggunakannya. Dengan memanfaatkan algoritma String Matching
dan Regular Expression, Anda dapat membangun sebuah chatbot interaktif sederhana
layaknya Google Assistant yang akan menjawab segala pertanyaan Anda terkait informasi
deadline tugas-tugas yang ada.

## Spesifikasi
Aplikasi memiliki fitur-fitur sebagai berikut:
- Fitur Register
- Fitur Login
- Fitur menambahkan task
- Fitur melihat task yang tersedia
- Fitur menampilkan deadline untuk task tertentu
- Fitur mengupdate deadline dari suatu task tertentu
- Fitur menandai task selesai dikerjakan
- Fitur menampilkan opsi help

## Daftar Kata Penting
* Tubes
* Tucil
* Kuis
* Ujian
* Praktikum

## Cara Penggunaan
Clone repository ini dan masukkan command sebagai berikut:
```bash
cd src/234bot
composer install
```
Salin file .env dan masukkan kredensial database sql beserta generate app key dan melakukan migrasi database melalui command di bawah:
```bash
php artisan key:generate
php artisan migrate
```
Jalankan aplikasi melalui command
```bash
php artisan serve
```

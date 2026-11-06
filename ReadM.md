# 🚀 HaloBK - Aplikasi Bimbingan Konseling Digital

HaloBK adalah platform Bimbingan Konseling (BK) online berbasis web yang memfasilitasi pendistribusian materi bimbingan, pelaksanaan tes/kuesioner online (psikotes & minat bakat), serta pencatatan rekapitulasi nilai dan histori siswa secara terstruktur.

---

## 🤖 IMPORTANT RULES FOR AI AGENT (STRICT GUIDELINES)

> **PETUNJUK UTAMA UNTUK AGENT / AI ASSISTANT:**
> 1. **DILARANG HALUSINASI TABEL / COLUMNS:** Jangan pernah menambah, mengubah, atau menghapus nama tabel maupun nama kolom di luar skema ERD resmi di bawah ini.
> 2. **3NF & FOREIGN KEY STRICT:** Pastikan setiap *query* atau *migration* mematuhi batasan Primary Key (PK) dan Foreign Key (FK).
> 3. **NO CIRCULAR DEPENDENCIES:** Ikuti hirarki relasi database yang sudah ditentukan.
> 4. **NAMING CONVENTION:** 
>    - Kolom tipe Boolean WAJIB diawali dengan prefix `is_` (contoh: `is_benar`, `is_ragu_ragu`).
>    - Gunakan format `snake_case` untuk nama tabel dan kolom database.
> 5. **VERIFIKASI LOGIKA AKTOR:** Selalu terapkan *middleware* pembatas hak akses berbasis 3 role (`Admin`, `Guru BK`, `Siswa`).

---

## 👥 Hak Akses & Role Pengguna

1. **Admin**
   - Mengelola data Guru BK, Siswa, dan Kelas.
   - Hak akses penuh ke seluruh *master data*.
2. **Guru BK**
   - Mengelola Kelas yang diampu.
   - Membuat Kategori, Paket Tes, Bank Soal (PG & Esai), dan Opsi Jawaban.
   - Mengunggah Materi Bimbingan (PDF/Modul).
   - Menilai jawaban Esai siswa dan melihat rekapitulasi nilai.
3. **Siswa**
   - Mengakses Dashboard Siswa.
   - Membaca/mengunduh Materi Bimbingan.
   - Mengerjakan Tes/Kuesioner online (*real-time timer*, navigasi soal, acak soal).
   - Melihat hasil dan histori tes.

---

## 🗄️ Skema Database & Struktur Tabel (Final ERD)

### 1. `Admin`
- `id_admin` (PK)
- `nama_admin`
- `username`
- `password`

### 2. `Guru BK`
- `id_guru` (PK)
- `nama_guru`
- `nip`
- `password`

### 3. `Kelas`
- `id_kelas` (PK)
- `id_guru` (FK -> `Guru BK.id_guru`)
- `nama_kelas`
- `kode_unik_kelas`

### 4. `Siswa`
- `id_siswa` (PK)
- `id_kelas` (FK -> `Kelas.id_kelas`)
- `id_admin` (FK -> `Admin.id_admin`)
- `nama_siswa`
- `nisn`
- `password`

### 5. `Kategori`
- `id_kategori` (PK)
- `nama_kategori`

### 6. `materi`
- `id_materi` (PK)
- `id_guru` (FK -> `Guru BK.id_guru`)
- `id_kategori` (FK -> `Kategori.id_kategori`)
- `id_kelas` (FK -> `Kelas.id_kelas`, *Nullable*)
- `judul_materi`
- `file_path`
- `deskripsi`
- `warna` (Opsi tema warna: *biru*, *hijau*, *ungu*, *kuning*, *merah*, *slate*)

### 7. `tes`
- `id_tes` (PK)
- `id_kelas` (FK -> `Kelas.id_kelas`)
- `id_kategori` (FK -> `Kategori.id_kategori`)
- `id_guru` (FK -> `Guru BK.id_guru`)
- `judul`
- `tipe` (`tes` / `form` - *Default: 'tes'*)
- `waktu_mulai`
- `waktu_selesai`
- `durasi_menit`
- `acak_soal`
- `status` (`draft` / `aktif` / `selesai`)

### 8. `soal`
- `id_soal` (PK)
- `id_tes` (FK -> `tes.id_tes`)
- `pertanyaan`
- `tipe_soal` (`PG` / `Esai`)
- `bobot_nilai`
- `timer_per_soal`
- `gambar_lampiran`

### 9. `pilihan_jawaban`
- `id_pilihan` (PK)
- `id_soal` (FK -> `soal.id_soal`)
- `teks_pilihan`
- `is_benar` (Boolean)

### 10. `sesi_tes_siswa`
- `id_sesi` (PK)
- `id_tes` (FK -> `tes.id_tes`)
- `id_siswa` (FK -> `Siswa.id_siswa`)
- `waktu_mulai_pengerjaan`
- `waktu_submit`
- `total_skor`
- `status_pengerjaan`

### 11. `jawaban_siswa`
- `id_jawaban_siswa` (PK)
- `id_sesi` (FK -> `sesi_tes_siswa.id_sesi`)
- `id_soal` (FK -> `soal.id_soal`)
- `id_pilihan` (FK -> `pilihan_jawaban.id_pilihan`, *Nullable* jika Esai)
- `jawaban_esai` (*Nullable*)
- `is_ragu_ragu` (Boolean)
- `nilai_soal`




*LAYOUT UNTUK FORM LOGIN*

Buatkan komponen Halaman Login (Login Page) berbasis React dan Tailwind CSS dengan tampilan wireframe minimalis split-screen persis sesuai spesifikasi berikut:

1. Layout Utama & Topbar:
   - Full screen height (min-h-screen) dengan background netral.
   - Topbar melintang penuh di bagian paling atas: background hitam (#1E1E1E), padding kecil, teks "HALAMAN LOGIN" warna abu-abu terang di sudut kiri atas.
   - Card Login di tengah layar (centered): background abu-abu terang (#E0E0E0), border-radius besar (rounded-3xl), bayangan lembut, terbagi menjadi 2 kolom (grid grid-cols-2) yang dipisahkan garis vertikal tipis di tengah.

2. Kolom Kiri (Branding Section):
   - Top: Box logo di kiri atas (w-16 h-12), background putih, border hitam, rounded-xl, teks "LOGO" di tengah.
   - Middle: Di bagian tengah kolom, buat 2 elemen pill (rounded-full):
     * Pill 1: Background hitam solid, teks putih tebal "SLOGAN APLIKASI", padding px-6 py-3.
     * Pill 2: Background hitam solid polos tanpa teks di bawah Pill 1.
   - Bottom: Di bagian bawah kolom, buat badge pill kecil hitam dan baris pill hitam berisi teks putih kecil "SEDIKIT KATA-KATA PERKENALAN APLIKASI".

3. Kolom Kanan (Formulir Login):
   - Top: Box header "UCAPAN SELAMAT DATANG" dengan border hitam, background abu-abu terang, rounded-2xl, p-6, teks besar & tebal di tengah.
   - Middle (Inputs): 
     * Label "username" (font-medium, text-black).
     * Input Username: Bentuk pill (rounded-full), background hitam solid, teks input warna putih, focus outline-none.
     * Label "Password" (font-medium, text-black).
     * Input Password: Bentuk pill (rounded-full), background hitam solid, teks input warna putih, type="password".
   - Bottom (Action):
     * Tombol Submit "CONFIRM": Full width pill (w-full rounded-full), background hitam solid, teks putih tebal, tracking-wider, cursor-pointer.

Pastikan jarak antar elemen (spacing/gap) rapi, proporsional, dan tampilan wireframe presisi seperti desain mockup.



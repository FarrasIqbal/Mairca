# Dokumentasi REST API Mairca

Dokumentasi ini menjelaskan penggunaan REST API yang tersedia pada proyek Mairca. REST API ini dilindungi menggunakan **Laravel Sanctum** untuk autentikasi berbasis token.

---

## 1. Aturan Request Umum

Untuk setiap request ke endpoint terproteksi, Anda wajib menyertakan HTTP Header berikut:

| Header | Value | Keterangan |
| :--- | :--- | :--- |
| `Accept` | `application/json` | Memastikan respon yang dikembalikan selalu berupa format JSON (bukan HTML/redirect) |
| `Authorization` | `Bearer <token_anda>` | Token akses yang didapatkan setelah melakukan login |

*Base URL Default:* `http://127.0.0.1:8000`

---

## 2. Endpoint Autentikasi

### Login User
Menerima kredensial email dan password, lalu menghasilkan token akses.
- **URL**: `/api/login`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "email": "hrd@mairca.com",
  "password": "password"
}
```
- **Respon Sukses (200 OK)**:
```json
{
  "status": "success",
  "message": "Login berhasil.",
  "data": {
    "access_token": "1|auth_token_hash...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "HR Admin",
      "email": "hrd@mairca.com",
      "role": "hr",
      "role_label": "HRD"
    }
  }
}
```

### Logout User
Menghapus token akses yang sedang aktif digunakan.
- **URL**: `/api/logout`
- **Method**: `POST`
- **Respon Sukses (200 OK)**:
```json
{
  "status": "success",
  "message": "Logout berhasil. Token telah dihapus."
}
```

### Profil Pengguna Aktif (Me)
Mendapatkan informasi profil pengguna yang sedang login.
- **URL**: `/api/me`
- **Method**: `GET`
- **Respon Sukses (200 OK)**:
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "HR Admin",
    "email": "hrd@mairca.com",
    "role": "hr",
    "role_label": "HRD"
  }
}
```

---

## 3. Endpoint Posisi Pekerjaan (Positions)

### List Semua Posisi
- **URL**: `/api/positions`
- **Method**: `GET`

### Detail Posisi (Termasuk Kriteria, Pelamar, & Hasil MAIRCA)
Menampilkan data lengkap posisi, kriteria terkait, pipeline pelamar, dan hasil perangkingan MAIRCA secara real-time.
- **URL**: `/api/positions/{id}`
- **Method**: `GET`

### Tambah Posisi Baru (HR-Only)
- **URL**: `/api/positions`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "name": "Frontend Developer",
  "is_active": true
}
```

### Edit Posisi (HR-Only)
- **URL**: `/api/positions/{id}`
- **Method**: `PUT`
- **Body (JSON)**:
```json
{
  "name": "Senior Frontend Developer",
  "is_active": true
}
```

### Hapus Posisi (HR-Only)
- **URL**: `/api/positions/{id}`
- **Method**: `DELETE`

---

## 4. Endpoint Kriteria Keputusan (Criteria)

### List Kriteria untuk Suatu Posisi
- **URL**: `/api/positions/{position_id}/criteria`
- **Method**: `GET`

### Tambah Kriteria Baru (HR-Only)
*Catatan: Total akumulasi bobot kriteria pada satu posisi tidak boleh melebihi 1.0.*
- **URL**: `/api/positions/{position_id}/criteria`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "name": "Pemahaman Framework Vue",
  "type": "benefit",
  "weight": 0.20
}
```

### Edit Kriteria (HR-Only)
- **URL**: `/api/positions/{position_id}/criteria/{criterion_id}`
- **Method**: `PUT`
- **Body (JSON)**:
```json
{
  "name": "Keahlian Vue & Nuxt",
  "type": "benefit",
  "weight": 0.25
}
```

### Hapus Kriteria (HR-Only)
- **URL**: `/api/positions/{position_id}/criteria/{criterion_id}`
- **Method**: `DELETE`

---

## 5. Endpoint Manajemen Kandidat (Candidates)

### List Pelamar (Dengan Filter Pencarian)
- **URL**: `/api/candidates?position_id=1&status=berkas&search=Budi`
- **Method**: `GET`
- *Query parameters (`position_id`, `status`, `search`) bersifat opsional.*

### Detail Profil Kandidat
- **URL**: `/api/candidates/{id}`
- **Method**: `GET`

### Tambah Kandidat Baru (HR-Only)
*Wajib dikirim menggunakan format **Form-Data (Multipart)** karena mendukung upload file resume.*
- **URL**: `/api/candidates`
- **Method**: `POST`
- **Form-Data Parameters**:
  - `position_id`: `1`
  - `name`: `Reza Pratama`
  - `email`: `reza@example.com`
  - `phone`: `081234567890`
  - `resume`: [Pilih file PDF/Doc max 5MB]

### Edit Data / Status Pipeline Kandidat (HR-Only)
- **URL**: `/api/candidates/{id}`
- **Method**: `PUT`
- **Body (JSON)**:
```json
{
  "position_id": 1,
  "name": "Reza Pratama",
  "email": "reza.new@example.com",
  "phone": "081234567890",
  "status": "tes_praktis"
}
```
*Pilihan Status:* `berkas`, `tes_praktis`, `wawancara_hr`, `wawancara_user`, `evaluasi_spk`, `hired`, `rejected`.

### Dapatkan Tautan Resume Kandidat
- **URL**: `/api/candidates/{id}/resume`
- **Method**: `GET`

### Hapus Data Kandidat (HR-Only)
- **URL**: `/api/candidates/{id}`
- **Method**: `DELETE`

---

## 6. Endpoint Jadwal Wawancara (Interviews)

### List Jadwal Wawancara
*Jika user yang login memiliki role `reviewer`, ia hanya dapat melihat jadwal wawancara di mana ia bertindak sebagai pewawancara.*
- **URL**: `/api/interviews?type=hr&status=scheduled`
- **Method**: `GET`

### Buat Jadwal Wawancara Baru (HR-Only)
- **URL**: `/api/interviews`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "candidate_id": 1,
  "interviewer_id": 2,
  "type": "hr",
  "scheduled_at": "2026-06-20 10:00:00",
  "zoom_link": "https://zoom.us/j/123456789",
  "notes": "Tes teknis awal."
}
```

### Update Jadwal Wawancara (HR & Reviewer)
Dapat digunakan oleh Reviewer untuk mengubah status pengerjaan atau menambahkan catatan hasil interview.
- **URL**: `/api/interviews/{id}`
- **Method**: `PUT`
- **Body (JSON)**:
```json
{
  "candidate_id": 1,
  "interviewer_id": 2,
  "type": "hr",
  "scheduled_at": "2026-06-20 10:00:00",
  "zoom_link": "https://zoom.us/j/123456789",
  "notes": "Kandidat memiliki skill problem solving yang sangat baik.",
  "status": "completed"
}
```
*Pilihan Status:* `scheduled`, `completed`, `cancelled`.

### Hapus Jadwal Wawancara (HR-Only)
- **URL**: `/api/interviews/{id}`
- **Method**: `DELETE`

---

## 7. Endpoint Penilaian Evaluasi (SPK)

### Ambil Grid Form Evaluasi
Mendapatkan kriteria dan daftar kandidat di status `evaluasi_spk` untuk diisi nilainya.
- **URL**: `/api/evaluations?position_id=1`
- **Method**: `GET`

### Simpan Penilaian Kriteria Massal (Bulk Submit)
- **URL**: `/api/evaluations/bulk`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "interview_type": "hr",
  "scores": {
    "1": { 
      "1": 80,
      "2": 85
    },
    "2": { 
      "1": 90,
      "2": 75
    }
  }
}
```
*Format `scores`:* `{"id_kandidat": {"id_kriteria": skor_nilai}}`.

---

## 8. Endpoint Tes Praktis Pelamar

### Daftar Peserta Ujian & Bank Soal Posisi
- **URL**: `/api/admin/practical-tests`
- **Method**: `GET`

### Ambil Jawaban Tes Praktis Pelamar untuk Dinilai
- **URL**: `/api/admin/practical-tests/{candidate_id}/evaluate`
- **Method**: `GET`

### Simpan Penilaian Ujian Praktis Pelamar
- **URL**: `/api/admin/practical-tests/{candidate_id}/evaluate`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "question_scores": {
    "1": 80,
    "2": 85,
    "3": 90
  },
  "reviewer_notes": "Jawaban komprehensif, sesuai dengan standar.",
  "status": "wawancara_hr"
}
```

### Perpanjang Link Ujian Pelamar (HR-Only)
Menambahkan masa aktif token ujian pelamar selama 3 hari ke depan.
- **URL**: `/api/admin/practical-tests/{candidate_id}/extend`
- **Method**: `POST`

---

## 9. Endpoint Soal Ujian Kustom (HR-Only)

### Tambah Soal Kustom per Posisi
- **URL**: `/api/admin/practical-tests/questions`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "position_id": 1,
  "question_text": "Bagaimana cara mengoptimalkan performa load page aplikasi React?",
  "placeholder_text": "Tuliskan jawaban Anda di sini...",
  "grading_guide": "Panduan: Harus menyebutkan Code Splitting, Lazy Loading, Memoization.",
  "max_score": 100
}
```

### Edit Soal Kustom
- **URL**: `/api/admin/practical-tests/questions/{id}`
- **Method**: `PUT`
- **Body (JSON)**:
```json
{
  "question_text": "Bagaimana cara mengoptimalkan performa load page aplikasi React & Next.js?",
  "placeholder_text": "Tuliskan jawaban Anda di sini...",
  "grading_guide": "Panduan: Harus menyebutkan Code Splitting, Lazy Loading, SSR, Image optimization.",
  "max_score": 100
}
```

### Hapus Soal Kustom
- **URL**: `/api/admin/practical-tests/questions/{id}`
- **Method**: `DELETE`

---

## 10. Endpoint Ujian Publik Pelamar (Tanpa Autentikasi)

Endpoint ini digunakan secara publik oleh pelamar yang memiliki token akses ujian unik tanpa memerlukan login.

### Ambil Data Ujian & Timer Pengerjaan
Membuka lembar ujian pelamar, memicu timer ujian berjalan secara real-time.
- **URL**: `/api/public/test/{token_unik}`
- **Method**: `GET`
- **Respon**: Mengembalikan sisa waktu pengerjaan (`remaining_seconds`), data soal ujian, dan info kandidat.

### Submit Jawaban Ujian Praktis Pelamar
*Catatan: Setiap jawaban wajib diisi dan memiliki panjang minimal 10 karakter.*
- **URL**: `/api/public/test/{token_unik}/submit`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "answers": {
    "1": "Jawaban detail untuk pertanyaan kesatu minimal sepuluh karakter.",
    "2": "Jawaban detail untuk pertanyaan kedua minimal sepuluh karakter.",
    "3": "Jawaban detail untuk pertanyaan ketiga minimal sepuluh karakter."
  }
}
```

---

## 11. Endpoint Ranking & Pengguna Sistem (HR-Only)

### Ambil Hasil Perangkingan MAIRCA Terkini
Menghitung secara real-time bobot dan skor kriteria pelamar berdasarkan engine MAIRCA, lalu menyajikan daftar pelamar terurut dari skor kesenjangan (gap Qi) terkecil.
- **URL**: `/api/rankings?position_id=1`
- **Method**: `GET`

### List Semua Pengguna Sistem
- **URL**: `/api/users`
- **Method**: `GET`

### Tambah Pengguna Baru (HR/Reviewer)
- **URL**: `/api/users`
- **Method**: `POST`
- **Body (JSON)**:
```json
{
  "name": "Aditya Reviewer",
  "email": "aditya@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "reviewer"
}
```

### Edit Data / Password Pengguna
- **URL**: `/api/users/{id}`
- **Method**: `PUT`
- **Body (JSON)**:
```json
{
  "name": "Aditya Reviewer",
  "email": "aditya.new@example.com",
  "role": "reviewer"
}
```
*Parameter password & password_confirmation bersifat opsional jika hanya ingin mengubah nama/email/role.*

### Hapus Pengguna
*Mencegah pengguna menghapus akunnya sendiri.*
- **URL**: `/api/users/{id}`
- **Method**: `DELETE`

# SmartPBI BPJS Theme Guide

## Warna Tema BPJS PBI

Tema aplikasi SmartPBI telah disesuaikan dengan identitas visual logo BPJS dengan palet warna berikut:

### Warna Utama
- **Primary Blue**: `#0052A3` - Warna dominan dari logo BPJS
- **Secondary Green**: `#00A651` - Warna aksen dari logo BPJS
- **Accent Blue**: `#0066CC` - Warna highlight dan interaktif
- **Dark Blue**: `#003D7A` - Warna untuk hover dan active states
- **Light Blue**: `#E8F4F8` - Warna background yang lembut

## Penggunaan Warna dalam Aplikasi

### 1. Tombol (Buttons)
- **Primary Button**: Menggunakan warna biru BPJS (#0052A3)
- **Success Button**: Menggunakan warna hijau BPJS (#00A651)
- Hover state: Lebih gelap untuk feedback visual yang jelas

### 2. Status Badge
- **Layak**: Hijau (#00A651) - Berhak menerima PBI BPJS
- **Tidak Layak**: Merah (#DC3545) - Tidak berhak menerima PBI BPJS
- **Diupayakan**: Kuning (#FFC107) - Bisa diupayakan dengan penyesuaian

### 3. Navigasi
- **Sidebar**: Gradient biru BPJS (dari #0052A3 ke #003D7A)
- **Topbar**: Gradient biru BPJS (dari #0052A3 ke #0066CC)
- **Active Link**: Dengan border hijau (#00A651) di sebelah kiri

### 4. Kartu (Cards)
- **Header**: Gradient biru ke biru muda
- **Border**: Warna biru muda (#E0E7FF)
- **Shadow**: Subtle shadow dengan warna biru BPJS

### 5. Tabel
- **Header**: Warna biru BPJS (#0052A3) dengan teks putih
- **Row Hover**: Background biru muda (#E8F4F8)
- **Striped**: Alternating rows dengan background biru transparan

### 6. Form Elements
- **Focus State**: Border dan shadow dengan warna biru BPJS
- **Select2**: Custom styling dengan warna tema
- **Progress Bar**: Gradient dari biru ke hijau BPJS

## Kelas CSS Khusus SmartPBI

### Header Section
```html
<div class="smartpbi-header">
    <h1>SmartPBI: BPJS PBI</h1>
    <p>Sistem Pendukung Keputusan untuk Penerima Bantuan Iuran BPJS</p>
</div>
```

### Result Card
```html
<div class="card smartpbi-result-card layak">
    <!-- Untuk status Layak -->
</div>

<div class="card smartpbi-result-card tidak-layak">
    <!-- Untuk status Tidak Layak -->
</div>

<div class="card smartpbi-result-card diupayakan">
    <!-- Untuk status Diupayakan -->
</div>
```

### Status Badge
```html
<span class="badge-layak">Layak</span>
<span class="badge-tidak-layak">Tidak Layak</span>
<span class="badge-diupayakan">Diupayakan</span>
```

### Stat Box
```html
<div class="stat-box">
    <div class="stat-value">1,234</div>
    <div class="stat-label">Total Peserta</div>
</div>
```

## Variabel CSS (CSS Variables)

Untuk konsistensi, gunakan variabel CSS berikut:

```css
--bpjs-primary: #0052A3;      /* Warna utama */
--bpjs-secondary: #00A651;    /* Warna sekunder */
--bpjs-accent: #0066CC;       /* Warna aksen */
--bpjs-light: #E8F4F8;        /* Warna terang */
--bpjs-dark: #003D7A;         /* Warna gelap */
```

Contoh penggunaan:
```css
.my-element {
    background-color: var(--bpjs-primary);
    color: white;
}
```

## Informasi BPJS PBI

### Apa itu BPJS PBI?
BPJS PBI (Penerima Bantuan Iuran) adalah program jaminan kesehatan gratis dari pemerintah bagi warga miskin/rentan miskin, di mana iuran bulanannya dibayar penuh melalui APBN/APBD.

### Poin Penting:
1. **Iuran**: Rp0 (Gratis/ditanggung pemerintah pusat atau daerah)
2. **Sasaran**: Fakir miskin, orang tidak mampu, anak terlantar, lansia, disabilitas, dan penyandang masalah kesejahteraan sosial
3. **Syarat**: Terdaftar dalam Data Terpadu Kesejahteraan Sosial (DTKS) yang dikelola Kementerian Sosial
4. **Layanan**: Rawat jalan dan rawat inap kelas 3
5. **Cek Status**: Bisa melalui WhatsApp PANDAWA (0811-816-5165), aplikasi Mobile JKN, atau situs BPJS Kesehatan
6. **Pengaktifan Kembali**: Jika dinonaktifkan, bisa diaktifkan kembali melalui Dinas Sosial atau dinas kesehatan setempat jika masih memenuhi syarat

### Status PBI
Status PBI dapat berubah/nonaktif jika peserta dianggap sudah mampu secara ekonomi atau tidak terdaftar lagi dalam data DTKS.

## Responsive Design

Tema ini fully responsive dan menyesuaikan dengan berbagai ukuran layar:
- Desktop: Sidebar penuh dengan navigasi lengkap
- Tablet: Sidebar dapat disembunyikan
- Mobile: Navigasi collapse dengan hamburger menu

## Aksesibilitas

- Semua elemen interaktif memiliki focus state yang jelas
- Kontras warna memenuhi standar WCAG AA
- Tombol dan link memiliki ukuran yang cukup untuk touch devices
- Notifikasi menggunakan warna dan ikon untuk clarity

---

**Terakhir diperbarui**: 10 Maret 2026
**Versi**: 1.0

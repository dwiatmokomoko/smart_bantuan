# SmartPBI BPJS Theme - Quick Reference Card

## 🎨 Warna Utama

```
Primary Blue:     #0052A3  (Tombol, Header, Sidebar)
Secondary Green:  #00A651  (Aksen, Status Layak)
Accent Blue:      #0066CC  (Links, Focus State)
Dark Blue:        #003D7A  (Hover State)
Light Blue:       #E8F4F8  (Background)
```

## 🔘 Tombol

```html
<!-- Primary Button -->
<button class="btn btn-primary">Tombol</button>

<!-- Success Button -->
<button class="btn btn-success">Tombol</button>

<!-- Disabled -->
<button class="btn btn-primary" disabled>Tombol</button>
```

## 🏷️ Badge Status

```html
<!-- Layak (Hijau) -->
<span class="badge-layak">✓ Layak</span>

<!-- Tidak Layak (Merah) -->
<span class="badge-tidak-layak">✗ Tidak Layak</span>

<!-- Diupayakan (Kuning) -->
<span class="badge-diupayakan">⚠ Diupayakan</span>
```

## 📇 Card

```html
<div class="card smartpbi-result-card layak">
    <div class="card-header">Header</div>
    <div class="card-body">Content</div>
</div>
```

## 📊 Stat Box

```html
<div class="stat-box">
    <div class="stat-value">1,234</div>
    <div class="stat-label">Label</div>
</div>
```

## 📈 Progress Bar

```html
<div class="progress">
    <div class="progress-bar" style="width: 85%"></div>
</div>
```

## 📋 Tabel

```html
<table class="table table-striped">
    <thead>
        <tr><th>Kolom</th></tr>
    </thead>
    <tbody>
        <tr><td>Data</td></tr>
    </tbody>
</table>
```

## 📝 Form

```html
<input type="text" class="form-control" placeholder="Input">
<select class="form-select">
    <option>Pilih...</option>
</select>
```

## ⚠️ Alert

```html
<div class="alert alert-success">Sukses!</div>
<div class="alert alert-info">Informasi</div>
```

## 🎯 Header Section

```html
<div class="smartpbi-header">
    <h1>SmartPBI: BPJS PBI</h1>
    <p>Deskripsi</p>
</div>
```

## 🔗 Link

```html
<a href="#">Link</a>
```

## 📄 Pagination

```html
<nav>
    <ul class="pagination">
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item active"><a class="page-link" href="#">2</a></li>
    </ul>
</nav>
```

## 🍞 Breadcrumb

```html
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Current</li>
    </ol>
</nav>
```

## 🎬 Modal

```html
<div class="modal">
    <div class="modal-header">Header</div>
    <div class="modal-body">Content</div>
</div>
```

## 🔄 Spinner

```html
<div class="spinner-border"></div>
```

## 📱 Responsive Classes

```html
<!-- Responsive Grid -->
<div class="row">
    <div class="col-md-6">Desktop: 50%</div>
    <div class="col-md-6">Desktop: 50%</div>
</div>

<!-- Responsive Display -->
<div class="d-none d-md-block">Visible on desktop</div>
<div class="d-md-none">Visible on mobile</div>
```

## 🎨 CSS Variables

```css
var(--bpjs-primary)      /* #0052A3 */
var(--bpjs-secondary)    /* #00A651 */
var(--bpjs-accent)       /* #0066CC */
var(--bpjs-light)        /* #E8F4F8 */
var(--bpjs-dark)         /* #003D7A */
```

## 📚 BPJS PBI Info

| Aspek | Detail |
|-------|--------|
| **Program** | BPJS PBI (Penerima Bantuan Iuran) |
| **Iuran** | Rp0 (Gratis) |
| **Sasaran** | Fakir miskin, tidak mampu, lansia, disabilitas |
| **Syarat** | Terdaftar di DTKS Kemensos |
| **Layanan** | Rawat jalan & inap kelas 3 |
| **Cek Status** | WA PANDAWA: 0811-816-5165 |

## 🚀 Tips

1. **Gunakan class yang sudah ada** - Jangan buat CSS baru
2. **Konsisten dengan warna** - Gunakan palet yang sudah ditentukan
3. **Mobile first** - Test di mobile dulu
4. **Aksesibilitas** - Pastikan contrast ratio cukup
5. **Performance** - Minimize CSS, optimize images

## 📖 Dokumentasi Lengkap

- `SMARTPBI_THEME_README.md` - Dokumentasi lengkap
- `smartpbi-theme-guide.md` - Panduan penggunaan
- `smartpbi-implementation-examples.html` - Contoh implementasi
- `smartpbi-colors.json` - Konfigurasi warna

---

**Versi**: 1.0  
**Terakhir diperbarui**: 10 Maret 2026

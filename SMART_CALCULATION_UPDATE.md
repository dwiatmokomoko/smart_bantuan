# Update SMART Calculation Formula

## Perubahan yang Dilakukan

SmartService telah diupdate untuk menggunakan rumus normalisasi yang benar sesuai dengan metode SMART:

### Rumus Perhitungan

1. **Normalisasi Bobot Kriteria**
   ```
   normalisasi_bobot = bobot_kriteria / total_bobot
   ```

2. **Utility Value (Normalisasi Nilai)**
   ```
   ui(ai) = (Cout - Cmin) / (Cmax - Cmin)
   ```
   Dimana:
   - **Cout** = nilai raw dari sub-criteria yang dipilih user
   - **Cmin** = nilai raw terendah dalam kriteria tersebut
   - **Cmax** = nilai raw tertinggi dalam kriteria tersebut

3. **Nilai Akhir**
   ```
   nilai_akhir = normalisasi_bobot × utility_value
   ```

4. **Probabilitas Kelayakan (Prob Layak)**
   ```
   prob_layak = Σ nilai_akhir (untuk semua kriteria)
   ```

### Klasifikasi Hasil

- **< 0.50**: Tidak Berhak Menerima PBI BPJS
- **0.50 - 0.75**: Bisa Diupayakan Menerima PBI BPJS dengan Penyesuaian Persyaratan
- **> 0.75**: Berhak Menerima PBI BPJS

## Contoh Perhitungan

Dengan input:
- Pekerjaan: Tetap (40)
- Status Hubungan Keluarga: Kepala Keluarga (80)
- Data Kependudukan Sinkron: Ya (80)
- Anggota Keluarga BPJS: Ada (40)
- Anggota Keluarga Luar: Ada (50)
- Kependudukan Wilayah PBI: Ya (80)

Hasil:
- Prob Layak: 0.5000000001
- Klasifikasi: Bisa Diupayakan Menerima PBI BPJS dengan Penyesuaian Persyaratan

## File yang Diubah

1. **app/Services/SmartService.php**
   - Update method `train()` untuk menggunakan rumus normalisasi yang benar
   - Menghitung Cmin dan Cmax dari sub_criterias
   - Menghitung utility_value menggunakan rumus normalisasi

2. **database/migrations/2026_03_10_000001_update_data_trainings_schema.php**
   - Membuat kolom lama (penghasilan, pekerjaan, dll) menjadi nullable
   - Memungkinkan penyimpanan data dengan skema baru

## Database Schema

Kolom data_trainings yang digunakan:
- `pekerjaan` - Nilai akhir C1
- `status_hubungan_keluarga` - Nilai akhir C2
- `data_kependudukan_sinkron` - Nilai akhir C3
- `anggota_keluarga_bpjs` - Nilai akhir C4
- `anggota_keluarga_luar` - Nilai akhir C5
- `kependudukan_wilayah_pbi` - Nilai akhir C6
- `pekerjaan_raw` - Raw value C1
- `status_hubungan_keluarga_raw` - Raw value C2
- `data_kependudukan_sinkron_raw` - Raw value C3
- `anggota_keluarga_bpjs_raw` - Raw value C4
- `anggota_keluarga_luar_raw` - Raw value C5
- `kependudukan_wilayah_pbi_raw` - Raw value C6
- `prob_layak` - Probabilitas kelayakan
- `input_label` - Label dari sub-criteria yang dipilih (JSON)
- `nik` - Nomor Induk Kependudukan

## Testing

Jalankan migration dan seeder:
```bash
php artisan migrate:fresh --seed
```

Aplikasi siap digunakan dengan perhitungan SMART yang benar.

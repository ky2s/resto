# Analisis Project WordPress (resto)

## Ringkasan stack
- **CMS inti**: WordPress **6.7.4**.
- **Tema utama custom**: **ThemeFood** versi **1.3.3** (tema katalog/order makanan berbasis WhatsApp).
- **Arsitektur fitur**: mayoritas logika bisnis ada di plugin companion **TF Supporter** (CPT, taxonomi, metabox, integrasi API, dsb).
- **Pendekatan frontend**: mobile-first, app-like UX, plus dukungan PWA.

## Plugin yang terpasang
### 1) TF Supporter (custom, wajib)
- Versi: 1.3.3.
- Fungsi: plugin pendamping ThemeFood.
- Mendaftarkan CPT/taksonomi utama bisnis:
  - `tf-produk`, `tf-info`, `tf-slider`, `tf-kupon`, `tf-cs`, `tf-order`
  - taksonomi `kategori_produk` dan `order_status`
- Seluruh CPT ditandai `show_in_rest => true` (REST API aktif untuk objek bisnis).

### 2) WP REST Cache
- Plugin versi 2025.1.4 + MU-plugin versi 2021.3.0.
- Dipakai untuk cache endpoint `/wp-json/...`, termasuk invalidasi cache saat data produk/order berubah.

### 3) Post Types Order
- Versi 2.3.7.
- Dipakai untuk drag-drop urutan post type.

### 4) Category Order and Taxonomy Terms Order
- Versi 1.9.
- Dipakai untuk urutan kategori/taxonomy berbasis drag-drop.

### 5) Akismet Anti-spam
- Versi 5.4.
- Plugin anti-spam standar WordPress.

## Framework/library yang dipakai (frontend)
Dari enqueue script/style ThemeFood, project ini menggunakan:
- **Onsen UI** (`onsenui.min.css/js`) untuk UI mobile/app-like.
- **Leaflet** (`leaflet.min.css`) untuk peta/lokasi.
- **Glide.js** (`glide.min.js`) untuk slider galeri produk.
- **jQuery datetimepicker** (opsional, aktif jika mode timeslot tanggal/jam).
- Library utilitas lain: lazyload, readmore, saveMyForm, select2 (admin), confetti.
- Integrasi push notif via **OneSignal SDK** (dimuat dari CDN jika dikonfigurasi).

## Integrasi/ekosistem tema
- ThemeFood memakai **TGM Plugin Activation** untuk mewajibkan/rekomendasikan plugin tertentu.
- Tema juga mengandung **plugin update checker** (`puc/plugin-update-checker.php`) dengan endpoint update JSON dari domain vendor.
- Opsi tema memakai **OptionTree** (dibundel di plugin TF Supporter, `OT_VERSION 2.7.3`).
- Terdapat alur **license validation** ke endpoint vendor (`themefood.id`) untuk aktivasi/deaktivasi lisensi.

## Ciri fungsi bisnis (domain restoran/katalog)
- Fokus pada katalog produk + order via WhatsApp (bukan WooCommerce-centric).
- Mendukung data promo slider, kupon, customer service WhatsApp, dan order status.
- Menonaktifkan Gutenberg untuk CPT tertentu agar editor lebih terkontrol.
- Ada import demo (One Click Demo Import) dan otomatis set permalink saat after import.

## Catatan teknis penting
- `wp-config.php` saat ini menyimpan kredensial DB dan salts secara hardcoded (normal untuk WP), namun jika repo ini bersifat publik, wajib diproteksi/dirotasi.
- Tema mengganti jQuery default WordPress pada frontend dengan file lokal (`js/jquery.min.js`).
- Project menunjukkan pendekatan “headless-lite”: konten tetap WP biasa, tapi data utama katalog/order diekspos intensif via REST API untuk dipakai script frontend tema.

## Kesimpulan singkat
Project ini adalah implementasi WordPress custom untuk bisnis F&B/retail cepat (resto, frozen food, minimarket) berbasis **ThemeFood + TF Supporter**, dengan stack plugin utama untuk **REST caching**, **ordering konten**, dan **opsi tema**. Secara arsitektur, ini lebih mirip aplikasi katalog-order mobile di atas WordPress dibanding website blog standar.

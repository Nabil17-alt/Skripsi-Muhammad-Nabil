# Diagram Sistem Percetakan

## Use Case Diagram

```mermaid
%% Use Case Diagram (disimulasikan sebagai flowchart karena versi Mermaid lama)
flowchart LR

C[Customer]
A[Admin]

UC_LOGIN[(Login / Logout)]
UC_BROWSE[(Lihat & Pilih Produk)]
UC_CART[(Kelola Keranjang)]
UC_ORDER[(Checkout & Buat Pesanan)]
UC_PAY[(Pembayaran Pesanan)]
UC_TRACK[(Lihat & Lacak Pesanan)]
UC_INST[(Lihat Jadwal Cicilan)]
UC_CHAT[(Chat dengan Admin)]

UA_USER[(Kelola Akun User)]
UA_PRODUCT[(Kelola Produk)]
UA_ORDER[(Kelola Pesanan)]
UA_PAYMENT[(Kelola Pembayaran)]
UA_METHOD[(Kelola Metode Pembayaran)]
UA_INST[(Kelola Cicilan)]
UA_CHAT[(Kelola Chat Pelanggan)]

%% Relasi Customer ke use case utama
C --> UC_LOGIN
C --> UC_BROWSE
C --> UC_CART
C --> UC_ORDER
C --> UC_PAY
C --> UC_TRACK
C --> UC_INST
C --> UC_CHAT

%% Relasi Admin ke use case utama
A --> UA_USER
A --> UA_PRODUCT
A --> UA_ORDER
A --> UA_PAYMENT
A --> UA_METHOD
A --> UA_INST
A --> UA_CHAT
```

## Class Diagram (Ringkas)

Untuk memudahkan penempatan pada gambar dengan rasio 1:1, diagram kelas berikut dipecah menjadi dua bagian: inti pemesanan dan pembayaran/chat. Masing-masing menggunakan layout vertikal (top-bottom) agar tidak terlalu melebar.

### Class Diagram – Inti Pemesanan

```mermaid
%% Class Diagram Inti Pemesanan
classDiagram
direction TB

class User {
  +int id
  +string name
  +string email
  +string password
}

class Role {
  +int id
  +string name
}

class Address {
  +int id
  +int user_id
  +string recipient_name
  +string phone
  +string full_address
  +float latitude
  +float longitude
  +bool is_default
}

class Product {
  +int id
  +string name
  +string slug
  +string category
  +decimal base_price
  +bool allow_custom_design
  +bool allow_design_service
}

class ProductImage {
  +int id
  +int product_id
  +string path
  +bool is_primary
}

class ProductVariant {
  +int id
  +int product_id
  +string name
  +decimal additional_price
}

class Promo {
  +int id
  +string name
  +string discount_type
  +decimal discount_value
}

class Order {
  +int id
  +int user_id
  +string order_number
  +int shipping_address_id
  +decimal subtotal_amount
  +decimal discount_amount
  +decimal shipping_fee
  +decimal grand_total
  +string production_status
  +string payment_status
}

class OrderItem {
  +int id
  +int order_id
  +int product_id
  +int product_variant_id
  +int quantity
  +decimal unit_price
}

User "1" --> "*" Address
User "1" --> "*" Order
Role "1" --> "*" User

Product "1" --> "*" ProductImage
Product "1" --> "*" ProductVariant

Order "1" --> "*" OrderItem
Address "1" --> "*" Order : shipping_address
```

### Class Diagram – Pembayaran & Chat

```mermaid
%% Class Diagram Pembayaran & Chat
classDiagram
direction TB

class PaymentMethod {
  +int id
  +string name
  +string type
  +bool is_active
}

class BankAccount {
  +int id
  +int payment_method_id
  +string bank_name
  +string account_name
  +string account_number
  +string image_path
  +bool is_active
}

class Payment {
  +int id
  +int order_id
  +int payment_method_id
  +int bank_account_id
  +decimal amount
  +string status
  +string reference
  +string payment_proof_path
  +datetime paid_at
  +int installment_tenor
}

class Installment {
  +int id
  +int payment_id
  +int sequence
  +decimal amount
  +date due_date
  +string status
  +datetime paid_at
}

class Chat {
  +int id
  +int user_id
  +int order_id
}

class ChatMessage {
  +int id
  +int chat_id
  +string sender_type
  +int sender_id
  +string message
  +string file_path
}

class Order {
  +int id
}

class User {
  +int id
}

Order "1" --> "*" Payment
PaymentMethod "1" --> "*" Payment
PaymentMethod "1" --> "*" BankAccount
BankAccount "1" --> "*" Payment
Payment "1" --> "*" Installment

User "1" --> "*" Chat
Order "1" --> "*" Chat
Chat "1" --> "*" ChatMessage
```

## Activity Diagram (Alur Aktivitas Sistem)

```mermaid
%% Activity Diagram keseluruhan sistem berbasis use case
flowchart TD

A[Mulai] --> B[Login]
B --> C{Jenis pengguna}
C -->|Customer| D1[Menu utama customer]
C -->|Admin| D2[Menu utama admin]

%% Alur aktivitas Customer
D1 --> E1[Lihat dan pilih produk]
E1 --> F1[Kelola keranjang]
F1 --> G1[Checkout dan buat pesanan]
G1 --> H1[Pilih dan lakukan pembayaran]
H1 --> I1[Lihat dan lacak pesanan]
I1 --> J1[Lihat jadwal cicilan]
J1 --> K1[Chat dengan admin]
K1 --> L1[Logout]

%% Alur aktivitas Admin
D2 --> E2[Kelola akun user]
E2 --> F2[Kelola produk]
F2 --> G2[Kelola pesanan]
G2 --> H2[Kelola pembayaran]
H2 --> I2[Kelola metode pembayaran]
I2 --> J2[Kelola cicilan]
J2 --> K2[Kelola chat pelanggan]
K2 --> L2[Logout]

L1 --> Z[Selesai]
L2 --> Z
```

## Sequence Diagram (Checkout & Pembayaran QRIS)

```mermaid
%% Sequence Diagram
sequenceDiagram
    actor Customer
    participant Browser
    participant CartController
    participant CheckoutController
    participant Order
    participant Payment
    participant PaymentController
    participant PG as PaymentGateway

    Customer->>Browser: Pilih produk, klik "Tambah ke Keranjang"
    Browser->>CartController: store(product, qty, opsi desain)
    CartController-->>Browser: Keranjang terupdate

    Customer->>Browser: Buka /checkout
    Browser->>CheckoutController: index()
    CheckoutController-->>Browser: Form checkout + ringkasan cart

    Customer->>Browser: Isi form (nama, telepon, alamat, titik lokasi, opsi desain), pilih QRIS, submit
    Browser->>CheckoutController: process(request)
    CheckoutController->>Order: create(grand_total termasuk jasa desain & ongkir)
    CheckoutController->>Payment: create(pending, reference, tenor cicilan jika perlu)
    CheckoutController-->>Browser: redirect ke /pembayaran/{orderNumber}

    Browser->>PaymentController: show(orderNumber)
    PaymentController-->>Browser: Halaman pembayaran QRIS

    Customer->>PG: Bayar via aplikasi (scan QR / pilih channel)
    PG-->>PaymentController: POST gatewayCallback(reference, status=success)
    PaymentController->>Payment: update(status=lunas, paid_at, raw_callback_log)
    PaymentController->>Order: update(payment_status=lunas)
    PaymentController-->>PG: { status: "ok" }

    Customer->>Browser: Refresh halaman pembayaran / lihat riwayat
    Browser->>PaymentController: show(orderNumber)
    PaymentController-->>Browser: Status pembayaran sudah LUNAS
```

## Desain Output

**Halaman utama (frontend)**
- Beranda: daftar produk unggulan, produk terlaris, dan promo aktif.
- Katalog produk: grid produk dengan nama, harga dasar, kategori, status aktif.
- Detail produk: foto-foto, deskripsi, harga, estimasi waktu pengerjaan, opsi desain.
- Keranjang: tabel item (nama produk, qty, harga, jasa desain, subtotal, total).

**Proses pemesanan & pembayaran**
- Checkout: ringkasan order (alamat, jarak ke toko, ongkir, subtotal, jasa desain, grand total), pilihan metode pembayaran.
- Halaman pembayaran: data order, metode pembayaran, total tagihan.
  - QRIS/E-Wallet: informasi channel/QR dan status pembayaran.
  - Transfer bank/cicilan: informasi rekening, status pembayaran, link upload bukti.
  - Cicilan: ringkasan tenor, bunga total, cicilan per bulan, tabel jadwal cicilan.
- Riwayat & detail pesanan: daftar pesanan dengan status produksi/pembayaran, dan detail item.
- Halaman chat customer: daftar pesan dengan admin, termasuk lampiran bukti.

**Halaman admin (back office)**
- Dashboard: ringkasan pesanan, pembayaran, dan statistik singkat.
- Manajemen produk: daftar produk dengan aksi tambah, ubah, nonaktifkan, dan kelola gambar.
- Manajemen pesanan: daftar & detail pesanan, status produksi/pengiriman.
- Manajemen pembayaran: daftar payment, status, bukti transfer, referensi, jadwal cicilan.
- Manajemen metode pembayaran & rekening bank: daftar channel, nomor rekening, gambar/QR.
- Manajemen user: daftar user, detail, dan aksi reset password.
- Manajemen chat: daftar percakapan pelanggan dan balasan admin.

## Desain Input

**Form customer**
- Registrasi: nama, email, password, konfirmasi password.
- Login: email, password.
- Keranjang: product_id, quantity, opsi desain (custom/service), catatan (opsional).
- Checkout:
  - Data penerima: nama penerima, nomor telepon.
  - Alamat: teks alamat lengkap, koordinat latitude/longitude (dari peta), penandaan default.
  - Pembayaran: payment_method_id, tenor cicilan (jika metode cicilan).
- Upload bukti pembayaran: file bukti (jpg/png/pdf), catatan (opsional).
- Chat customer: isi pesan, file lampiran (opsional).

**Form admin**
- Produk: nama, slug, kategori, deskripsi, harga dasar, waktu pengerjaan, flag custom design/service, status aktif, gambar produk.
- Metode pembayaran & rekening bank: nama channel, tipe (qris/ewallet/bank_transfer/cash/installment), nama bank, nama pemilik, nomor rekening, gambar/logo/QR, status aktif.
- Pesanan: perubahan status produksi/pengiriman, catatan internal (opsional).
- Pembayaran: aksi verifikasi (set lunas/gagal), aksi simulasi callback, penandaan cicilan per bulan sebagai lunas.
- User: nama, email, role, reset password.

## Desain Database (Ringkasan ERD)

**Tabel utama**
- `tb_users`: menyimpan data pengguna (customer & admin) dengan relasi ke role.
- `tb_roles`: menyimpan peran (admin, customer) dan relasi ke users.
- `tb_products`: menyimpan produk percetakan (nama, slug, kategori, harga dasar, opsi desain, dst.).
- `tb_product_images`: menyimpan gambar produk dan penanda gambar utama.
- `tb_product_variants`: menyimpan variasi produk (jika digunakan) dan harga tambahan.
- `tb_promos`: menyimpan promo dan skema diskon.
- `tb_addresses`: menyimpan alamat pelanggan beserta koordinat lokasi dan penanda default.
- `tb_orders`: menyimpan pesanan (user, alamat, subtotal, diskon, ongkir, total, jarak, status produksi, status pembayaran, metode pengiriman).
- `tb_order_items`: menyimpan item per pesanan (produk, qty, harga, jasa desain, catatan).
- `tb_payment_methods`: menyimpan metode pembayaran (nama, tipe, status aktif).
- `tb_bank_accounts`: menyimpan data rekening bank/channel pembayaran terkait metode pembayaran.
- `tb_payments`: menyimpan transaksi pembayaran per order (metode, jumlah, status, reference, bukti, callback, data cicilan).
- `tb_installments`: menyimpan jadwal cicilan per payment (urutan, jumlah, jatuh tempo, status, tanggal bayar).
- `tb_chats`: menyimpan percakapan antara customer dan admin yang terkait order.
- `tb_chat_messages`: menyimpan pesan di dalam chat, termasuk jenis pengirim dan file lampiran.

## Blackbox Testing (Contoh Kasus Uji)

| Kode | Nama Pengujian                              | Input Utama                                                           | Expected Output                                                                                  |
|------|---------------------------------------------|------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------|
| TC-01| Registrasi akun berhasil                    | Form registrasi diisi valid (nama, email unik, password cocok)       | Akun baru tersimpan di `tb_users`, user diarahkan ke login/berhasil login.                       |
| TC-02| Login gagal (password salah)                | Email terdaftar + password salah                                     | Autentikasi ditolak, muncul pesan error login gagal, tidak masuk ke dashboard.                   |
| TC-03| Tambah produk ke keranjang                  | Dari detail produk, pilih qty=2, opsi desain=service                 | Session `cart` berisi item dengan qty=2, total harga termasuk jasa desain 10% per item.          |
| TC-04| Checkout dalam radius 1 KM                  | Checkout dengan titik lokasi dalam radius ≤ 1 KM                     | `shipping_distance_km` terisi, `delivery_method = antar`, `shipping_fee = 0`, order tersimpan.   |
| TC-05| Checkout di luar radius 1 KM                | Checkout dengan titik lokasi > 1 KM                                  | `shipping_distance_km` terisi, `delivery_method = ambil_di_toko`, `shipping_fee = 0`.            |
| TC-06| Checkout dengan metode cicilan              | Pilih metode cicilan, tenor=3 bulan                                  | Order.grand_total termasuk bunga 3% x tenor, Payment dan 3 record `tb_installments` terbentuk.   |
| TC-07| Upload bukti pembayaran transfer bank       | Pesanan dengan metode bank transfer, upload file jpg valid           | `payment_proof_path` terisi, ChatMessage baru tercipta dengan lampiran bukti.                    |
| TC-08| Callback pembayaran QRIS sukses             | HTTP POST ke endpoint callback dengan reference valid, status=success| Payment.status menjadi `lunas`, `paid_at` terisi, Order.payment_status menjadi `lunas`.          |
| TC-09| Penandaan angsuran cicilan per bulan        | Di admin, tandai satu installment sebagai paid                       | Record installment berubah `status=paid`, Payment masih `pending` jika masih ada angsuran lain.  |
| TC-10| Semua angsuran cicilan lunas                | Semua installment untuk satu payment ditandai paid                    | Semua installment berstatus `paid`, Payment.status dan Order.payment_status menjadi `lunas`.     |
| TC-11| Penandaan cicilan overdue via command       | Ada installment `due_date` < hari ini dan status `pending`           | Setelah `php artisan installments:mark-overdue`, installment terkait berstatus `overdue`.        |

Relasi kunci:
- Satu `user` memiliki banyak `addresses`, `orders`, dan `chats`.
- Satu `order` memiliki banyak `order_items`, banyak `payments`, dan banyak `chats`.
- Satu `payment_method` memiliki banyak `payments` dan banyak `bank_accounts`.
- Satu `payment` memiliki banyak `installments`.
- Satu `chat` memiliki banyak `chat_messages`.

## Product Backlog (Ringkasan)

Tabel berikut merupakan ringkasan product backlog utama yang disusun berdasarkan analisis kebutuhan dan diimplementasikan pada project_baru.

| ID  | User Story                                                                                  | Prioritas | Sprint | Status       |
|-----|---------------------------------------------------------------------------------------------|-----------|--------|--------------|
| PB-01 | Sebagai user, saya dapat registrasi dan login/logout agar bisa mengakses fitur sesuai peran. | High      | 1      | Selesai      |
| PB-02 | Sebagai admin, saya dapat mengelola data akun user (tambah, ubah, nonaktifkan, reset sandi). | High      | 1      | Selesai      |
| PB-03 | Sebagai admin, saya dapat mengelola data produk percetakan beserta gambar dan variannya.    | High      | 1      | Selesai      |
| PB-04 | Sebagai customer, saya dapat menambahkan produk ke keranjang dan mengubah jumlah pesanan.   | High      | 1      | Selesai      |
| PB-05 | Sebagai customer, saya dapat melakukan checkout, memilih alamat, dan membuat pesanan.       | High      | 2      | Selesai      |
| PB-06 | Sebagai customer, saya dapat memilih metode pembayaran (tunai, transfer, QRIS, e-wallet, cicilan). | High | 2 | Selesai |
| PB-07 | Sebagai sistem, saya menghitung ongkir berdasarkan jarak dan bunga cicilan per tenor.        | Medium    | 2      | Selesai      |
| PB-08 | Sebagai admin, saya dapat mengelola metode pembayaran dan rekening bank yang digunakan.     | Medium    | 2      | Selesai      |
| PB-09 | Sebagai customer, saya dapat mengunggah bukti pembayaran dan admin dapat memverifikasinya.  | High      | 3      | Selesai      |
| PB-10 | Sebagai customer, saya dapat melihat riwayat dan status pesanan saya secara real-time.      | High      | 3      | Selesai      |
| PB-11 | Sebagai customer dan admin, saya dapat berkomunikasi lewat chat termasuk mengirim gambar revisi desain. | Medium | 3 | Selesai |
| PB-12 | Sebagai admin, saya dapat melihat dashboard berisi ringkasan pesanan, pendapatan, dan grafik penjualan. | Medium | 3 | Selesai |

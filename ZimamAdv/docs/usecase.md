```mermaid
%% Use Case Diagram
usecaseDiagram

actor Customer
actor Admin
actor "Payment Gateway" as PG

Customer --> (Registrasi Akun)
Customer --> (Login / Logout)
Customer --> (Lihat Katalog Produk)
Customer --> (Lihat Detail Produk)
Customer --> (Kelola Keranjang)
Customer --> (Checkout Pesanan)
Customer --> (Lihat Riwayat Pesanan)
Customer --> (Lacak Pesanan)
Customer --> (Lihat Halaman Pembayaran)
Customer --> (Upload Bukti Pembayaran)
Customer --> (Lihat Jadwal Cicilan)
Customer --> (Chat dengan Admin)

Admin --> (Login / Logout)
Admin --> (Kelola User)
Admin --> (Kelola Produk)
Admin --> (Kelola Metode Pembayaran \n& Rekening Bank)
Admin --> (Kelola Pesanan)
Admin --> (Kelola Pembayaran)
Admin --> (Kelola Jadwal Cicilan)
Admin --> (Kelola Chat Pelanggan)
```
## ERD Konseptual (Gaya Diagram Oval)

Bagian ini menyajikan ERD konseptual dengan gaya mirip contoh buku (entitas persegi panjang, atribut oval, dan relasi bertuliskan kata kerja) untuk memudahkan dimasukkan ke bab analisis/perancangan.

```mermaid
graph LR
    %% Definisi gaya
    classDef entity fill:#1f2937,stroke:#0f172a,stroke-width:1,color:#f9fafb;
    classDef attribute fill:#e5f2ff,stroke:#60a5fa,stroke-width:1,color:#1f2937;
    classDef relation fill:#0f172a,stroke:#0f172a,color:#f9fafb;

    %% Entitas utama
    ROLE["Role"]:::entity
    USER["User"]:::entity
    ADDRESS["Address"]:::entity
    PRODUCT["Product"]:::entity
    VARIANT["ProductVariant"]:::entity
    ORDER["Order"]:::entity
    ORDERITEM["OrderItem"]:::entity
    PROMO["Promo"]:::entity
    PAYMETHOD["PaymentMethod"]:::entity
    BANKACC["BankAccount"]:::entity
    PAYMENT["Payment"]:::entity
    INSTALL["Installment"]:::entity
    CHAT["Chat"]:::entity
    CHATMSG["ChatMessage"]:::entity

    %% Relasi (diamond)
    R_USER_ROLE{"memiliki role"}:::relation
    R_USER_ORDER{"membuat"}:::relation
    R_ORDER_ITEM{"memiliki"}:::relation
    R_ORDER_PAYMENT{"dibayar_dengan"}:::relation
    R_PAYMENT_INSTALL{"memiliki_cicilan"}:::relation
    R_USER_CHAT{"berkomunikasi"}:::relation
    R_PAYMETHOD_BANK{"punya_rekening"}:::relation
    R_ITEM_PRODUCT{"adalah_item_dari"}:::relation
    R_PRODUCT_VARIANT{"memiliki_varian"}:::relation
    R_ORDER_PROMO{"menggunakan_promo"}:::relation
    R_USER_ADDRESS{"memiliki_alamat"}:::relation
    R_PAYMENT_METHOD{"menggunakan_metode"}:::relation
    R_PAYMENT_BANK{"masuk_ke_rekening"}:::relation
    R_ORDER_CHAT{"terkait_pesanan"}:::relation
    R_CHAT_MESSAGE{"terdiri_dari_pesan"}:::relation

    %% Contoh atribut per entitas
    a_user_id((id_user)):::attribute --> USER
    a_user_name((nama)):::attribute --> USER
    a_user_email((email)):::attribute --> USER

    a_product_name((nama_produk)):::attribute --> PRODUCT
    a_product_price((harga_dasar)):::attribute --> PRODUCT

    a_order_number((nomor_pesanan)):::attribute --> ORDER
    a_order_total((grand_total)):::attribute --> ORDER

    a_payment_amount((amount)):::attribute --> PAYMENT
    a_payment_status((status_pembayaran)):::attribute --> PAYMENT

    a_install_seq((urutan_cicilan)):::attribute --> INSTALL
    a_install_due((jatuh_tempo)):::attribute --> INSTALL

    a_chat_created((tanggal_buat)):::attribute --> CHAT
    a_chatmsg_text((isi_pesan)):::attribute --> CHATMSG

    %% Hubungan antar entitas
    ROLE --- R_USER_ROLE --- USER

    USER --- R_USER_ORDER --- ORDER
    ORDER --- R_ORDER_ITEM --- ORDERITEM
    ORDERITEM --- R_ITEM_PRODUCT --- PRODUCT
    PRODUCT --- R_PRODUCT_VARIANT --- VARIANT
    ORDER --- R_ORDER_PROMO --- PROMO
    USER --- R_USER_ADDRESS --- ADDRESS

    ORDER --- R_ORDER_PAYMENT --- PAYMENT
    PAYMENT --- R_PAYMENT_METHOD --- PAYMETHOD
    PAYMENT --- R_PAYMENT_BANK --- BANKACC
    PAYMENT --- R_PAYMENT_INSTALL --- INSTALL

    PAYMETHOD --- R_PAYMETHOD_BANK --- BANKACC

    USER --- R_USER_CHAT --- CHAT
    ORDER --- R_ORDER_CHAT --- CHAT
    CHAT --- R_CHAT_MESSAGE --- CHATMSG
```

diagram ERD tersebut menggambarkan bahwa data di sistem dibagi ke dalam entitas‑entitas terpisah (seperti User, Role, Product, Order, Payment, Chat, dan lain‑lain) yang masing‑masing menyimpan satu jenis informasi saja. Setiap hubungan antar entitas tidak digambarkan dengan garis langsung, tetapi selalu melalui relasi (diamond) yang diberi nama kata kerja, misalnya “memiliki role”, “membuat pesanan”, “menggunakan metode pembayaran”, atau “berkomunikasi”. Dengan cara ini, struktur data menjadi lebih rapi, tidak terjadi entitas saling “bertemu” langsung, dan lebih mudah dipetakan ke basis data relasional yang sudah ternormalisasi.

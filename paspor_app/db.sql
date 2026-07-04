CREATE DATABASE IF NOT EXISTS paspor_db;
USE paspor_db;

CREATE TABLE IF NOT EXISTS pendaftaran (
    no_daftar INT AUTO_INCREMENT PRIMARY KEY,
    nama_pemohon VARCHAR(100) NOT NULL,
    tgl_daftar DATE NOT NULL,      
    hari VARCHAR(20) NOT NULL,      
    tanggal DATE NOT NULL,          
    jam TIME NOT NULL,             
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS daftar_ulang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_daftar INT NOT NULL,
    nama_pemohon VARCHAR(100) NOT NULL,
    hari_harus_datang VARCHAR(20) NOT NULL,
    tgl_harus_datang DATE NOT NULL,
    hari_datang VARCHAR(20) NOT NULL,
    tgl_datang DATE NOT NULL,
    ktp ENUM('ada','tidak') NOT NULL DEFAULT 'tidak',
    kk ENUM('ada','tidak') NOT NULL DEFAULT 'tidak',
    ijazah_akte ENUM('ada','tidak') NOT NULL DEFAULT 'tidak',
    keperluan VARCHAR(255) NULL,
    keterangan VARCHAR(10) NOT NULL,   
    no_antrian INT DEFAULT NULL,       
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (no_daftar) REFERENCES pendaftaran(no_daftar) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS pengurusan (
    no_antrian INT PRIMARY KEY,
    no_daftar INT NOT NULL,
    nama_pemohon VARCHAR(100) NOT NULL,
    berkas VARCHAR(20) NOT NULL,       
    status VARCHAR(20) NOT NULL,       
    keterangan VARCHAR(10) NOT NULL DEFAULT '', 
    pembayaran INT NOT NULL DEFAULT 0, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

<?php

use CodeIgniter\I18n\Time;

if (! function_exists('buatAkunDefault')) {
    function buatAkunDefault(string $role, array $data): array
    {
        $username = '';
        $password = '';

        if ($role === 'mahasiswa') {
            $username = $data['nim'] ?? '';
            $password = $data['nim'] ?? ''; // default password = NIM mahasiswa

        } elseif ($role === 'dospem') {
            $username = $data['nppy'] ?? '';
            $password = '12345678'; // default password dospem

        } elseif ($role === 'kaprodi') {
            // Proses nama untuk username kaprodi
            $namaLengkap = $data['nama_lengkap'] ?? '';
            
            // Hapus gelar dan tanda baca
            $namaLengkap = preg_replace('/\b(Dr\.?|Prof\.?|S\.Kom\.?|M\.Kom\.?|S\.T\.?|M\.T\.?|S\.Si\.?|M\.Si\.?|Ph\.D\.?)\b/i', '', $namaLengkap);
            
            // Bersihkan spasi berlebih dan tanda baca
            $namaLengkap = preg_replace('/[^\w\s]/', '', $namaLengkap);
            $namaLengkap = preg_replace('/\s+/', ' ', $namaLengkap);
            $namaLengkap = trim($namaLengkap);
            
            // Ambil 2 kata pertama
            $kataKata = explode(' ', $namaLengkap);
            $duaKataPertama = array_slice($kataKata, 0, 2);
            
            // Gabungkan dan lowercase
            $nama = strtolower(implode('', $duaKataPertama));
            
            // Hapus karakter non-alphanumeric
            $nama = preg_replace('/[^a-z0-9]/', '', $nama);
            
            $username = 'kaprodi_' . $nama;
            $password = '12345678'; // default password kaprodi

        } elseif ($role === 'mitra') {
            $username = $data['id_unit'] ?? '';
            $password = '12345678';

        } else {
            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';
        }

        return [
            'username' => $username,
            'password' => $password
        ];
    }
}

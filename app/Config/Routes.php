<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');

//login
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/logout', 'Login::logout');
$routes->get('update_password', 'Login::updatePassword');
$routes->post('update_password_process', 'Login::updatePasswordProcess');


//admin
$routes->get('/admin/dashboard', 'Home::dashboard');
$routes->get('dashboard', 'Home::dashboard');
$routes->get('admin/profil', 'Admin::profil');
$routes->post('admin/update_profil', 'Admin::update_profil');

// kelola user
$routes->get('kelola_user', 'Kelola_user::index');
$routes->post('kelola_user/updateAjax', 'Kelola_user::updateAjax');
$routes->post('kelola_user/hapusAjax', 'Kelola_user::hapusAjax');
$routes->get('kelola_user/cariMahasiswa', 'Kelola_user::cariMahasiswa');
$routes->get('kelola_user/cariDosen', 'Kelola_user::cariDosen');
$routes->get('kelola_user/cariUnit', 'Kelola_user::cariUnit');
$routes->get('kelola_user/cariKaprodi', 'Kelola_user::cariKaprodi');
$routes->post('kelola_user/simpanAjax', 'Kelola_user::simpanAjax');
$routes->get('arsip_pengguna', 'Kelola_user::arsip');
$routes->post('kelola_user/restoreAjax', 'Kelola_user::restoreAjax');
$routes->post('user/importExcel', 'Kelola_user::importExcel');


// kelola mitra
$routes->get('kelola_mitra', 'Kelola_mitra::index');
$routes->get('kelola_mitra/detail/(:segment)', 'Kelola_mitra::detail/$1');
$routes->post('kelola_mitra/simpanAjax', 'Kelola_mitra::simpanAjax');
$routes->post('kelola_mitra/updateAjax', 'Kelola_mitra::updateAjax');
$routes->post('kelola_mitra/hapusAjax', 'Kelola_mitra::hapusAjax');
$routes->post('kelola_mitra/restoreAjax', 'Kelola_mitra::restoreAjax');
$routes->get('arsip_mitra', 'Kelola_mitra::arsip');
$routes->post('kelola_mitra/importExcel', 'Kelola_mitra::importExcel');

// kelola unit
$routes->get('kelola_unit', 'Kelola_unit::index');
$routes->get('kelola_unit/detail/(:segment)', 'Kelola_unit::detail/$1');
$routes->post('kelola_unit/simpanAjax', 'Kelola_unit::simpanAjax');
$routes->post('kelola_unit/updateAjax', 'Kelola_unit::updateAjax');
$routes->post('kelola_unit/hapusAjax', 'Kelola_unit::hapusAjax');
$routes->get('arsip_unit', 'Kelola_unit::arsip');
$routes->post('kelola_unit/restoreAjax', 'Kelola_unit::restoreAjax');

// program magang
$routes->get('program_magang', 'ProgramMagang::index');
$routes->post('admin/program_magang/simpanAjax', 'ProgramMagang::simpanAjax');
$routes->get('admin/program_magang/detail/(:num)', 'ProgramMagang::detail/$1');
$routes->post('admin/program_magang/updateAjax', 'ProgramMagang::updateAjax');
$routes->post('admin/program_magang/hapusAjax', 'ProgramMagang::hapusAjax');

// profil magang
$routes->get('kelola_profil_magang', 'ProfilMagang::index');
$routes->get('profilMagang/detail/(:segment)', 'ProfilMagang::detail/$1');
$routes->post('profilMagang/simpanAjax', 'ProfilMagang::simpanAjax');
$routes->post('profilMagang/updateAjax', 'ProfilMagang::updateAjax');
$routes->delete('profilMagang/hapusAjax/(:segment)', 'ProfilMagang::hapusAjax/$1');
$routes->get('arsip_profil_magang', 'ProfilMagang::arsip');
$routes->post('kelola_profil_magang/restoreAjax', 'ProfilMagang::restoreAjax');
$routes->post('profilMagang/importExcel', 'ProfilMagang::importExcel');
// Autocomplete profil magang
$routes->get('profilMagang/searchMahasiswa', 'ProfilMagang::searchMahasiswa');
$routes->get('profilMagang/searchDosen', 'ProfilMagang::searchDosen');
$routes->get('profilMagang/searchMitra', 'ProfilMagang::searchMitra');
$routes->get('profilMagang/searchUnit', 'ProfilMagang::searchUnit');


//HALAMAN MAHASISWA
$routes->get('/mahasiswa/dashboard', 'Home::dashboard_mahasiswa');
$routes->get('dashboard', 'Home::dashboard_mahasiswa');
$routes->get('mahasiswa/profil', 'Mahasiswa::profilAkun');
$routes->post('mahasiswa/update_profil', 'Mahasiswa::updateProfilAkun');
$routes->get('profil_magang', 'Mahasiswa::profil');
$routes->get('mahasiswa/profil_magang', 'Mahasiswa::profil');
$routes->get('mahasiswa/searchMitra', 'Mahasiswa::searchMitra');
$routes->get('mahasiswa/getUnitByMitra', 'Mahasiswa::getUnitByMitra');
$routes->get('mahasiswa/getMitraDetail', 'Mahasiswa::getMitraDetail');
$routes->get('mahasiswa/getUnitDetail', 'Mahasiswa::getUnitDetail');
$routes->post('mahasiswa/tambahUnit', 'Mahasiswa::tambahUnit');
$routes->post('mahasiswa/profil/update', 'Mahasiswa::updateProfil');
// Presensi Mahasiswa
$routes->get('presensi', 'Presensi::index');
$routes->post('presensi/simpanMasuk', 'Presensi::simpanMasuk');
$routes->post('presensi/simpanPulang', 'Presensi::simpanPulang');
// Learning Plan Mahasiswa
$routes->post('mahasiswa/learningplan/store', 'LearningPlan::store');
$routes->post('mahasiswa/learningplan/storeMengajar', 'LearningPlan::storeMengajar');
$routes->post('mahasiswa/learningplan/storeAdopsi', 'LearningPlan::storeAdopsi');
$routes->get('mahasiswa/learning_plan', 'LearningPlan::index');
$routes->get('mahasiswa/learningplan/cetak/(:segment)', 'LearningPlan::cetak/$1');
// Logbook Mahasiswa
$routes->get('logbook', 'Logbook::index');
$routes->post('logbook/store', 'Logbook::store');
$routes->post('logbook/update', 'Logbook::update');
$routes->get('logbook/cetak', 'Logbook::cetak');
//Bimbingan Mahasiswa
$routes->get('mahasiswa/bimbingan_magang', 'Bimbingan::magang');
$routes->post('bimbingan/simpanJudul', 'Bimbingan::simpanJudul');
$routes->get('mahasiswa/bimbingan_asb', 'Bimbingan::asb');
$routes->get('mahasiswa/bimbingan_dsib', 'Bimbingan::dsib');
$routes->get('mahasiswa/bimbingan_kombis', 'Bimbingan::kombis');
//TUGAS AKHIR MAGANG
$routes->get('tugas_akhir_magang', 'TugasAkhirMagang::index');
$routes->post('tugas_akhir_magang/store', 'TugasAkhirMagang::store');
$routes->post('tugas_akhir_magang/update/(:any)', 'TugasAkhirMagang::update/$1');


//HALAMAN PEMBIMBING MITRA
$routes->get('mitra/dashboard', 'PembimbingMitra::dashboard');
$routes->get('mitra/profil', 'PembimbingMitra::profil');
$routes->post('mitra/update_profil', 'PembimbingMitra::updateProfil');
$routes->get('mitra/mahasiswa', 'PembimbingMitra::mahasiswa'); 
$routes->get('mitra/mahasiswa/(:segment)', 'PembimbingMitra::detail/$1');
// Kelola Jam Kerja Unit
$routes->get('mitra/kelola_jam_kerja', 'jamKerjaUnit::index'); 
$routes->post('mitra/store', 'jamKerjaUnit::store'); 
$routes->post('mitra/update', 'jamKerjaUnit::update');
//Kelola Presensi Mahasiswa
$routes->get('mitra/kelola_presensi', 'Kelola_Presensi::index');
$routes->get('kelola_presensi/validasi/(:segment)', 'Kelola_Presensi::validasi/$1');
$routes->post('kelola_presensi/tolak', 'Kelola_Presensi::tolak');
$routes->get('mitra/detailPresensi/(:segment)', 'Kelola_Presensi::detailPresensi/$1');
//Kelola Learning Plan
$routes->get('mitra/kelola_learning_plan', 'KelolaLearningPlan::index');
$routes->post('mitra/kelola_learning_plan/setuju/(:segment)', 'KelolaLearningPlan::setuju/$1'); // tombol Setujui
$routes->post('mitra/kelola_learning_plan/tolak/(:segment)', 'KelolaLearningPlan::tolak/$1');  // tombol Tolak pakai form POST
$routes->get('mitra/kelola_learning_plan/detail/(:segment)', 'KelolaLearningPlan::detail/$1');
$routes->get('mitra/detail_learning_plan/(:segment)', 'KelolaLearningPlan::Detailmhs/$1');
//Kelola Logbook
$routes->get('mitra/kelola_logbook', 'KelolaLogbook::index');
$routes->get('kelola_logbook/validasi/(:segment)', 'KelolaLogbook::validasi/$1');
$routes->post('kelola_logbook/tolak', 'KelolaLogbook::tolak');
$routes->get('mitra/logbook/(:any)', 'KelolaLogbook::logbook/$1');
//KELOLA PENILAIAN
$routes->get('mitra/kelola_penilaian', 'KelolaPenilaian::indexMitra');
$routes->get('input_nilai_magang/(:segment)', 'KelolaPenilaian::inputNilaiMitra/$1');
$routes->post('simpan_nilai_magang/(:any)', 'KelolaPenilaian::simpanNilaiMagangMitra/$1');



//HALAMAN KAPRODI
$routes->get('kaprodi/dashboard', 'Kaprodi::dashboard');
$routes->get('kaprodi/profil', 'Kaprodi::profil');
$routes->post('kaprodi/update_profil', 'Kaprodi::update_profil');
$routes->get('data_mahasiswa', 'Kaprodi::mahasiswa');
$routes->get('kaprodi/data_mahasiswa/(:segment)', 'Kaprodi::detail/$1');
//KELOLA LEARNING PLAN OLEH KAPRODI
$routes->get('kelola_learning_plan', 'KelolaLearningPlan::kaprodiIndex');
$routes->get('kaprodi/kelola_learning_plan', 'KelolaLearningPlan::kaprodiIndex');
$routes->get('kaprodi/kelola_learning_plan/detail/(:segment)', 'KelolaLearningPlan::kaprodiDetail/$1');
$routes->post('kaprodi/kelola_learning_plan/setuju/(:segment)', 'KelolaLearningPlan::kaprodiSetuju/$1');
$routes->post('kaprodi/kelola_learning_plan/tolak/(:segment)', 'KelolaLearningPlan::kaprodiTolak/$1');
$routes->get('kaprodi/detail_learning_plan/(:segment)', 'KelolaLearningPlan::kaprodiDetailmhs/$1');
//KOMPONEN PENILAIAN 
$routes->get('kaprodi/komponen_penilaian', 'KomponenNilai::index');
$routes->post('kaprodi/komponen_penilaian/save', 'KomponenNilai::save');
$routes->post('kaprodi/komponen_penilaian/updateGroup', 'KomponenNilai::updateGroup');
$routes->post('komponenNilai/deleteGroup', 'KomponenNilai::deleteGroup');
//NILAI MAHASISWA MAGANG
$routes->get('kaprodi/nilai_mahasiswa', 'Kaprodi::nilai_mahasiswa');
$routes->get('kaprodi/detail_nilai/(:any)', 'Kaprodi::detail_nilai/$1');



//HALAMAN DOSEN PEMBIMBING
$routes->get('dospem/dashboard', 'Dospem::dashboard');
$routes->get('dospem/profil', 'Dospem::profil');
$routes->post('dospem/update_profil', 'Dospem::update_profil');
//Data Mahasiswa
$routes->get('dospem/data_mahasiswa', 'Dospem::mahasiswa');
$routes->get('dospem/data_mahasiswa/(:segment)', 'Dospem::detail/$1');
$routes->get('dospem/arsip_mahasiswa', 'Dospem::arsip_mahasiswa');
//Data Presensi
$routes->get('/dospem/data_presensi', 'Dospem::dataPresensi');
$routes->get('dospem/detailPresensi/(:segment)', 'Dospem::detailPresensi/$1');
//Data Learning Plan
$routes->get('/dospem/data_learning_plan', 'KelolaLearningPlan::dospemIndex');
$routes->get('dospem/kelola_learning_plan/detail/(:segment)', 'KelolaLearningPlan::dospemDetail/$1');
$routes->get('dospem/detail_learning_plan/(:segment)', 'KelolaLearningPlan::dospemDetailmhs/$1');
//LOGBOOK MAHASISWA BERDASARKAN NIM
$routes->get('dospem/logbook/(:any)', 'Dospem::logbook/$1');
//KELOLA BIMBINGAN MAGANG MAHASISWA
$routes->get('dospem/bimbingan_magang', 'KelolaBimbingan::magang');
$routes->post('dospem/bimbingan/simpan', 'KelolaBimbingan::simpanMagang');
$routes->get('dospem/bimbingan/detail/(:any)/(:any)', 'KelolaBimbingan::detail/$1/$2');
$routes->post('dospem/bimbingan/update', 'KelolaBimbingan::update');
//KELOLA BIMBINGAN MAKALAH ANALISIS SISTEM 
$routes->get('dospem/bimbingan_asb', 'KelolaBimbingan::asb');
$routes->post('dospem/bimbingan_asb/simpan', 'KelolaBimbingan::simpanAsb');
//KELOLA BIMBINGAN MAKALAH DESAIN SISTEM 
$routes->get('dospem/bimbingan_dsib', 'KelolaBimbingan::dsib');
$routes->post('dospem/bimbingan_dsib/simpan', 'KelolaBimbingan::simpanDsib');
//KELOLA BIMBINGAN MAKALAH KOMUNIKASI BISNIS
$routes->get('dospem/bimbingan_kombis', 'KelolaBimbingan::kombis');
$routes->post('dospem/bimbingan_kombis/simpan', 'KelolaBimbingan::simpanKombis');
//KELOLA PENILAIAN MAHASISWA
$routes->get('dospem/kelola_penilaian', 'KelolaPenilaian::index');
$routes->get('dospem/input_nilai_magang/(:segment)', 'KelolaPenilaian::inputNilaiDospem/$1');
$routes->post('dospem/simpan_nilai_magang/(:any)', 'KelolaPenilaian::simpanNilaiMagang/$1');






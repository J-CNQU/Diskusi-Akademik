<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/css/global.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/css/main.css">

    <?php if (isset($data['judul']) && ($data['judul'] == 'Halaman Login' || $data['judul'] == 'Daftar Akun')): ?>
        <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/css/auth.css">
    <?php endif; ?>
    <?php if (isset($data['judul']) && $data['judul'] == 'Buat Topik'): ?>
        <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/css/topic_form.css">
    <?php endif; ?>
    <?php if (isset($data['judul']) && $data['judul'] == 'Detail Topik & Diskusi'): ?>
        <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/css/detail.css">
    <?php endif; ?>
</head>

<body>
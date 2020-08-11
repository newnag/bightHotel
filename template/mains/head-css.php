<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <link rel="shortcut icon" href="https://ansme.me/img/icon/06.png" type="image/x-icon">
  <meta name='description' content="<?= $head['description'] ?>">
  <meta name='keywords' content="<?= $head['keyword'] ?>">
  <meta property='og:image:type' content='image/jpeg'>
  <meta property='og:type' content='website'>
  <meta property='og:title' content="<?= $head['title'] ?>">
  <meta property='og:url' content="<?= SITE_URL . $head['url'] ?>">
  <meta property='og:description' content="<?= $head['description'] ?>">
  <meta property='og:image' content="<?= SITE_URL . $head['thumbnail'] ?>">
  <title><?= $head['title'] ?></title>  
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  <link rel="stylesheet" href="<?=ROOT_URL?>css/home.min.css?v=<?=time()?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="<?=ROOT_URL?>plugin/OwlCarousel/dist/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="<?=ROOT_URL?>plugin/OwlCarousel/dist/assets/owl.theme.default.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;700&display=swap" rel="stylesheet">
  
  <?php // สไตล์ในหน้าต่างๆที่เตรียมแยก ?>
  <link rel="stylesheet" href="<?=ROOT_URL?>css/gallary.min.css?v=<?=time()?>">
  <link rel="stylesheet" href="<?=ROOT_URL?>css/booking-room.min.css?v=<?=time()?>">
  <link rel="stylesheet" href="<?=ROOT_URL?>css/contact.min.css?v=<?=time()?>">
  <link rel="stylesheet" href="<?=ROOT_URL?>css/promotion.min.css?v=<?=time()?>"> 
  <link rel="stylesheet" href="<?=ROOT_URL?>css/room.min.css?v=<?=time()?>">
  <link rel="stylesheet" href="<?=ROOT_URL?>css/meeting.min.css?v=<?=time()?>">
  <link rel="stylesheet" href="<?=ROOT_URL?>css/history.min.css?v=<?=time()?>">
  <link rel="stylesheet" href="<?=ROOT_URL?>css/meStyle.css?v=<?=time()?>">

  <script>let hostname = "<?=ROOT_URL?>";</script>
  <?php /*
    <link rel="stylesheet" href="/css/style.min.css?v=<?=time()?>">
  */ ?> 

  <?php /*
    <link rel="preload" as="style" onload="this.rel='stylesheet'" rels="stylesheet" href="/css/style.min.css?v=<?= time() ?>">
  */ ?>

  <?php /*
  <!-- backup -->
  <!-- <link rel="stylesheet" href="/css/jaudStyle.min.css?v=1.1.1.2<?=time()?>"> -->
  <!-- <link rel="stylesheet" href="/plugin/OwlCarousel/dist/assets/owl.carousel.min.css"> -->
  <!-- <link rel="stylesheet" href="/plugin/OwlCarousel/dist/assets/owl.theme.default.min.css"> -->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
  <!-- <link rel="stylesheet" href="/css/selectMulti.css?v=1.0.0"> -->
  <!-- <link rel="stylesheet" href="/css/cssDisplayTable.min.css"> -->
  */ ?>

  <?php
  require_once DOC_ROOT . '/classes/browserDetect.class.php';
  $browser = new Browser();
  if ($browser->getBrowser() == Browser::BROWSER_CHROME && empty($_SERVER['HTTP_REFERER']) && false) {
  ?>
    <!-- <link rel="preload" as="style" onload="this.rel='stylesheet'" href="/css/jaudStyle.min.css?v=1.1.3">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="/plugin/OwlCarousel/dist/assets/owl.carousel.min.css">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="/plugin/OwlCarousel/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="/css/selectMulti.css?v=1.0.0">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="/css/cssDisplayTable.min.css"> -->

  <?php } else { ?>

    <!-- preload -->
    <!-- <link rel="preload" as="style" href="/css/jaudStyle.min.css?v=1.1.3">
    <link rel="preload" as="style" href="/plugin/OwlCarousel/dist/assets/owl.carousel.min.css">
    <link rel="preload" as="style" href="/plugin/OwlCarousel/dist/assets/owl.theme.default.min.css">
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="preload" as="style" href="/css/selectMulti.css?v=1.0.0">
    <link rel="preload" as="style" href="/css/cssDisplayTable.min.css"> -->
    <!-- preload -->

    <!-- <link rel="stylesheet" href="/css/jaudStyle.min.css?v=1.1.3">
    <link rel="stylesheet" href="/plugin/OwlCarousel/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="/css/selectMulti.css?v=1.0.0">
    <link rel="stylesheet" href="/plugin/OwlCarousel/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/cssDisplayTable.min.css"> -->

  <?php } ?>

  <!-- <script async src="/js/jquery/jquery-3.5.1.min.js"></script>
  <script async src="/plugin/fontawesome/all.min.js"></script>
  <script async src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script async src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script> -->
</head>
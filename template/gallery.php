<body>
    <!-- สคริปของเฟสบุ๊ค -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v7.0&appId=599318023929202&autoLogAppEvents=1" nonce="0s4WMai9"></script>
    <!-- Header Web -->
    <?php require_once "mains/header.php"; ?>
    <article>
        <div class="gallary-page">
            <div class="title">
                <h1><?=$article->title?></h1>
                <span><?=$article->description?></span>
            </div>
            <div class="gallary-zone">
                <?=$gallery['html']?>
            </div>

            <div class="showpic">
                <div class="bigpic">
                    <figure><img src="<?=ROOT_URL?>img/test1.jpg" alt=""></figure>
                    <div class="close">
                        X
                    </div>
                </div>
            </div>
            <div class="loadmore">
                <?=$gallery['more']?>
            </div>
        </div>
    </article>

    <!-- โซนฟุตเตอร์ -->
    <?php require_once "mains/footer.php"; ?>
    <script type="text/javascript" src="<?=ROOT_URL?>js/page/gallery.js?v=<?=time()?>"></script>

</body>
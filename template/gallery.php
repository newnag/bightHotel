<body>
    <!-- สคริปของเฟสบุ๊ค -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v7.0&appId=599318023929202&autoLogAppEvents=1" nonce="0s4WMai9"></script>
    <!-- Header Web -->
    <?php require_once "mains/header.php"; ?>
    <article>
        <div class="gallary-page">
            <div class="title">
                <h1>แกเลอรี่</h1>
                <span>ภาพบรรยากาศ ที่ Bright Hotel</span>
            </div>
            <div class="gallary-zone">
                <figure><img src="<?=ROOT_URL?>img/test1.jpg" alt=""></figure>
                <figure><img src="<?=ROOT_URL?>img/test2.jpg" alt=""></figure>
                <figure><img src="<?=ROOT_URL?>img/test3.jpg" alt=""></figure>
                <figure><img src="<?=ROOT_URL?>img/test4.jpg" alt=""></figure>
                <figure><img src="<?=ROOT_URL?>img/test5.jpg" alt=""></figure>
                <figure><img src="<?=ROOT_URL?>img/test1.jpg" alt=""></figure>
                <figure><img src="<?=ROOT_URL?>img/test2.jpg" alt=""></figure>
                <figure><img src="<?=ROOT_URL?>img/test3.jpg" alt=""></figure>
            </div>

            <div class="loadmore">
                <button>More</button>
            </div>
        </div>
    </article>

    <!-- โซนฟุตเตอร์ -->
    <footer>
        <div class="footer">
            <div class="logo"><figure><img src="<?=ROOT_URL?>img/logo B-01.png" alt=""></figure></div>

            <div class="address-contact">
                <div class="address">
                    <p>บริษัท ไบรท์โฮเต็ล จำกัด</p>
                    <p>เลขที่ 177/88 ถนนมิตรภาพ หมู่17 ต.ในเมือง อ.เมือง จ.ขอนแก่น 40000</p>
                </div>
                <div class="contact">
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/mobile-solid.svg" alt=""></figure>
                        <p>098-1201970</p>
                    </div>
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/phone.svg" alt=""></figure>
                        <p>043-306777-79</p>
                    </div>
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/envelope-open-solid.svg" alt=""></figure>
                        <p>brighthotelkhonkaen@gmail.com</p>
                    </div>
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/instagram-brands.svg" alt=""></figure>
                        <p>brighthotelkhonkaen</p>
                    </div>
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt=""></figure>
                        <p>brighthotelkhonkaen</p>
                    </div>
                </div>
            </div>
            
            <div class="social">
                <div class="facebookPage">
                    <div class="fb-page" data-href="https://www.facebook.com/brighthotelkhonkaen/" data-tabs="timeline" data-width="" data-height="" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/brighthotelkhonkaen/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/brighthotelkhonkaen/">Bright Hotel Khonkaen</a></blockquote></div>
                </div>
            </div>

            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3854.2311303549764!2d102.08191621528145!3d14.9798695716843!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31228827f7d124ed%3A0x1dcee4ef7e4bcaa0!2sBright%20Hotel!5e0!3m2!1sth!2sth!4v1592885106260!5m2!1sth!2sth" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
    </footer>
    
    <!-- โซนสคริป -->
    <script src="<?=ROOT_URL?>plugin/OwlCarousel/dist/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?=ROOT_URL?>js/page/hotel.js"></script>
    <script type="text/javascript" src="<?=ROOT_URL?>js/page/bookHome.js"></script>
    <script src="<?=ROOT_URL?>js/page/slide.js"></script>
</body>
</html>
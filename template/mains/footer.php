
    <!-- โซนฟุตเตอร์ -->
    <div class="loading">
        <div class="box-load">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            <p>กรุณารอสักครู่</p>
        </div>
    </div>

    <footer>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v7.0&appId=599318023929202&autoLogAppEvents=1" nonce="kebzBbCb"></script>
    
        <div class="footer">
            <div class="logo"><figure><img src="<?=ROOT_URL?>img/logo B-01.png" alt=""></figure></div>

            <div class="address-contact">
                <div class="address">
                    <p><?=$CONTACT_WEB->company_name?></p>
                    <p><?=$CONTACT_WEB->address?></p>
                </div>
                <div class="contact">
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/phone2.svg" alt=""></figure>
                        <p><?=$CONTACT_WEB->mobilephone?></p>
                    </div>
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/phone.svg" alt=""></figure>
                        <p><?=$CONTACT_WEB->phone?></p>
                    </div>
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/envelope-open-solid.svg" alt=""></figure>
                        <p><?=$CONTACT_WEB->email?></p>
                    </div>
          
                    <div class="single">
                        <figure><img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt=""></figure>
                        <p><?=$CONTACT_WEB->facebook?></p>
                    </div>
                </div>
            </div>
            
            <div class="social">
                <div class="facebookPage">
                    <div class="fb-page" 
                        data-href="https://www.facebook.com/brighthotelkhonkaen/" 
                        data-tabs="timeline" 
                        data-width="" 
                        data-height="" 
                        data-small-header="true" 
                        data-adapt-container-width="true" 
                        data-hide-cover="true" 
                        data-show-facepile="true">
                            <blockquote cite="https://www.facebook.com/brighthotelkhonkaen/" class="fb-xfbml-parse-ignore">
                                <a href="https://www.facebook.com/brighthotelkhonkaen/">Bright Hotel Khonkaen</a>
                            </blockquote>
                    </div>
                </div>
            </div>

           
            <div class="map"> <?=$CONTACT_WEB->map?> </div>
        </div>

        <div class="buttonTop" onclick="warpTop()">
            <figure><img src="<?=ROOT_URL?>img/icon/upload.svg" alt=""></figure>
        </div>
  </footer>
    
  <!-- โซนสคริป -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script src="<?=ROOT_URL?>plugin/OwlCarousel/dist/owl.carousel.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.3/flatpickr.min.js"></script>
  <script type="text/javascript" src="<?=ROOT_URL?>js/page/functions.js?v=<?=time()?>"></script>
  <script type="text/javascript" src="<?=ROOT_URL?>js/page/hotel.js?v=<?=time()?>"></script>
  <script type="text/javascript" src="<?=ROOT_URL?>js/page/bookHome.js?v=<?=time()?>"></script>
  <script type="text/javascript" src="<?=ROOT_URL?>js/page/slide.js?v=<?=time()?>"></script>
  <script src="<?=ROOT_URL?>js/page/reserve.js?v=1.2.1<?=time()?>"></script> 
  <script src="<?=ROOT_URL?>js/page/manage.js?v=<?=time()?>"></script> 
  <script src="//www.google.com/recaptcha/api.js?render=6LfYAbwZAAAAAMHxHuGHnNWfFR3-lr9UVrbCAoQH"></script>

  



  


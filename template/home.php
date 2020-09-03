    <body>
        <!-- Header Web -->
        <?php require_once "mains/header.php"; ?>

        <!-- โซนคอนเทนท์ที่1 -->
        <article>
            <div class="content row1">
                <div class="title">
                    <h1> <?=$post->title?></h1>
                </div>
                <div class="ck">
                   <?=$post->content?>
                </div>
            </div>
        </article>

        <!-- โซนคอนเทนท์ที่2 บริการ -->
        <article>
            <div class="content service" style="background:url('<?=ROOT_URL?>img/new-bg.jpg')">
                <div class="title">
                    <h1><?=$lang_config['page_home_facility_title']?></h1>
                    <span><?=$lang_config['page_home_faclity_description']?></span>
                </div>
                <div class="box-grid">
                    <?= $home_facilities ?>
                </div>
            </div>
        </article>

        <!-- โซนคอนเทนท์ที่3 ที่มีรูปใหญ่ๆ-->
        <article>
            <div class="content row3">
                <figure class="bigimg"><img src="<?=ROOT_URL.$article->thumbnail?>" alt=""></figure>
                <div class="title">
                    <h1><?=$article->title?></h1>
                    <h1><?=$article->description?></h1>
                </div>
                <div class="ck">
                    <p> <?=$article->content?> </p>
                </div>
    
                <div class="icon">
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/phone2.svg" alt="">
                        <span><?=$CONTACT_WEB->mobilephone?></span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/phone.svg" alt="">
                        <span><?=$CONTACT_WEB->phone?></span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/envelope-open-solid.svg" alt="">
                        <span><?=$CONTACT_WEB->email?></span>
                    </div>
           
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt="">
                        <span><?=$CONTACT_WEB->facebook?></span>
                    </div>
                    <!-- <div class="item share">
                        <img src="<?=ROOT_URL?>img/icon/share-alt-solid.svg" alt="">
                        <span>share</span>
                    </div> -->
                </div>
            </div>
  
        </article>

        <!-- โซนคอนเทนท์ที่3 ห้องพัก-->
        <article>
            <div class="content room" style="background:url('<?=ROOT_URL?>img/BG.jpg')">
                <div class="title">
                    <h1>รูปแบบห้องพัก</h1>
                    <span>ห้องพักประกอบด้วยห้องมาตรฐาน 79 ห้อง หรูหรา ระดับพรีเมียมคลาส</span>
                </div>

                <div class="grid-room">
                    <?php
                       echo($get);
                    ?>
                </div>
            </div>
        </article>

        <!-- โซนติดต่อเรา-->
        <article>
            <div class="content contact">
                <div class="title">
                    <h1>ติดต่อ</h1>
                </div>
                <div class="form-contact">
                    <div class="inputDual">
                        <div class="input-box">
                            <label>ชื่อ</label>
                            <input type="text" class="txt_name" placeholder="กรอกชื่อ">
                        </div>
                        <div class="input-box">
                            <label>นามสกุล</label>
                            <input type="text" class="txt_lastname" placeholder="กรอกนามสกุล">
                        </div>
                    </div>
                    <div class="inputDual">
                        <div class="input-box">
                            <label>เบอร์โทร</label>
                            <input type="tel" class="txt_tel" maxlength="10" placeholder="กรอกเบอร์โทร">
                        </div>
                        <div class="input-box">
                            <label>อีเมล</label>
                            <input type="email" class="txt_email" placeholder="กรอกอีเมล">
                        </div>
                    </div>
                    <div class="inputSingle">
                        <label>เรื่อง</label>
                        <input type="text" placeholder="กรอกเรื่อง" class="txt_subject">
                    </div>
                    <div class="textSingle">
                        <label>ข้อความ</label>
                        <textarea name="" id="" cols="30" rows="5" class="txt_message" placeholder="กรอกข้อความ"></textarea>
                    </div>
                    <div class="button"><button>ส่ง</button></div>
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">
                </div>
            </div>
        </article>

        

    <!-- Footer -->
    <?php 
        require_once "mains/footer.php"; 
    ?>

  <script>
    // grecaptcha.ready(function() {
    //     grecaptcha.execute('6LfYAbwZAAAAAMHxHuGHnNWfFR3-lr9UVrbCAoQH', {action: 'submit_contact'}).then(function(token) {
    //         // ค่า token ที่ถูกส่งกลับมา จะถูกนำไปใช้ส่งไปตรวจสอบกับ api อีกครั้ง
    //         // เราเอาค่า token ไปไว้ใน input hidden ชื่อg-recaptcha-response
    //         document.getElementById('g-recaptcha-response').value = token;
    //     });
    // },0);
    function getReCaptcha(){
        grecaptcha.ready(function() {
            grecaptcha.execute('6LfYAbwZAAAAAMHxHuGHnNWfFR3-lr9UVrbCAoQH', {action: 'submit_contact'}).then(function(token) {
                // ค่า token ที่ถูกส่งกลับมา จะถูกนำไปใช้ส่งไปตรวจสอบกับ api อีกครั้ง
                // เราเอาค่า token ไปไว้ใน input hidden ชื่อg-recaptcha-response
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
    }
    getReCaptcha();
    setInterval(function(){getReCaptcha();}, 60000);
  </script>    

    <!-- <div class="csrf-space-timeround"><?= $CSRF_TIMERROUND ?></div>
    <div class="csrf-space-province"><?= $CSRF_PROVINCE ?></div>
    <div class="csrf-space-position-arch-table"><?= $CSRF_POSITION_ARCH_TABLE ?></div>
    <div class="csrf-space-timeround-arch"><?= $CSRF_TIMEROUND_ARCH ?></div>
    <div class="csrf-space-timeround-table"><?= $CSRF_TIMEROUND_TABLE ?></div>
    <div class="csrf-space-booking-arch"><?= $CSRF_BOOKING_ARCH ?></div>
    <div class="csrf-space-booking-table"><?= $CSRF_BOOKING_TABLE ?></div>

    <script async defer src="/js/home.js?v=1.0.1"></script> -->
    </body>
<!-- หัวเว็บมีโลโก้ เมนู ค้นหา -->
<header> 
        <div class="header">
            <div class="logo"><figure><a href="<?=ROOT_URL?>"><img src="<?=ROOT_URL.$CONTACT_WEB->logo?>" alt=""></a></figure></div>
            <nav>
                <div class="ham-social">
                    <div class="hamburger" onclick="clickMenuMobile()">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                    <div class="icon-mobile">
                        <a target="_blank" href="tel:<?=$CONTACT_WEB->mobilephone?>"><img src="<?=ROOT_URL?>img/icon/phone2.svg" alt="telephone icon"></a>
                        <!-- <a target="_blank" href="https://line.me/ti/p/~<?=$CONTACT_WEB->line?>"><img src="<?=ROOT_URL?>img/icon/instagram-brands.svg" alt="instagram icon"></a> -->
                        <a target="_blank" href="https://facebook.com/<?=$CONTACT_WEB->facebook?>"><img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt="line icon"></a>
                    </div> 
                </div>
                <ul class="menu">
                    <?=$MYNAV_MENU_TOP?>
                    <div class="icon">
                        <a target="_blank" href="tel:<?=$CONTACT_WEB->mobilephone?>"><img src="<?=ROOT_URL?>img/icon/phone2.svg" alt="telephone icon"></a>
                        <!-- <a target="_blank" href="https://line.me/ti/p/~<?=$CONTACT_WEB->line?>"><img src="<?=ROOT_URL?>img/icon/instagram-brands.svg" alt="instagram icon"></a> -->
                        <a target="_blank" href="https://facebook.com/<?=$CONTACT_WEB->facebook?>"><img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt="line icon"></a>
                    </div> 
                    <div class="searchBM">
                        <input type="tel" maxlength="10" placeholder="ค้นหาการจองจากเบอร์โทร" class="reservation_search" value="<?=$tel?>">
                        <img src="<?=ROOT_URL?>img/icon/search-solid.svg" alt="">
                    </div>
                </ul>
                <div class="search">
                    <input type="tel" maxlength="10" placeholder="ค้นหาการจองจากเบอร์โทร" class="reservation_search" value="<?=$tel?>">
                    <img src="<?=ROOT_URL?>img/icon/search-solid.svg" alt="">
               
                </div>
               
                <?php 
                    if(isset($_GET['test']) || $_SESSION['mytest'] == 'yes'){
                            $_SESSION['mytest'] = 'yes';

                        ?>
                        <div class="translate">
                            <!-- GTranslate: https://gtranslate.io/ -->
                            <a href="#" onclick="doGTranslate('th|en');return false;" title="English" class="gflag nturl"><img src="<?=ROOT_URL?>img/icon/united-states-of-america.png" alt="English" /></a><a href="#" onclick="doGTranslate('th|th');return false;" title="Thai" class="gflag nturl"><img src="<?=ROOT_URL?>img/icon/thailand-2.png" alt="Thai" /></a>

                            <style type="text/css">
                            <!--
                            /* a.gflag {vertical-align:middle;font-size:16px;padding:1px 0;background-repeat:no-repeat;background-image:url(//gtranslate.net/flags/16.png);}
                            a.gflag img {border:0;}
                            a.gflag:hover {background-image:url(//gtranslate.net/flags/16a.png);} */
                            #goog-gt-tt {display:none !important;}
                            .goog-te-banner-frame {display:none !important;}
                            .goog-te-menu-value:hover {text-decoration:none !important;}
                            body {top:0 !important;}
                            #google_translate_element2 {display:none!important;}
                            -->
                            </style>

                            <div id="google_translate_element2"></div>
                            <script type="text/javascript">
                            function googleTranslateElementInit2() {new google.translate.TranslateElement({pageLanguage: 'th',autoDisplay: false}, 'google_translate_element2');}
                            </script><script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>


                            <script type="text/javascript">
                            /* <![CDATA[ */
                            eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('6 7(a,b){n{4(2.9){3 c=2.9("o");c.p(b,f,f);a.q(c)}g{3 c=2.r();a.s(\'t\'+b,c)}}u(e){}}6 h(a){4(a.8)a=a.8;4(a==\'\')v;3 b=a.w(\'|\')[1];3 c;3 d=2.x(\'y\');z(3 i=0;i<d.5;i++)4(d[i].A==\'B-C-D\')c=d[i];4(2.j(\'k\')==E||2.j(\'k\').l.5==0||c.5==0||c.l.5==0){F(6(){h(a)},G)}g{c.8=b;7(c,\'m\');7(c,\'m\')}}',43,43,'||document|var|if|length|function|GTranslateFireEvent|value|createEvent||||||true|else|doGTranslate||getElementById|google_translate_element2|innerHTML|change|try|HTMLEvents|initEvent|dispatchEvent|createEventObject|fireEvent|on|catch|return|split|getElementsByTagName|select|for|className|goog|te|combo|null|setTimeout|500'.split('|'),0,{}))
                            /* ]]> */
                            </script>
                    </div>

                    <?php
                    }
                ?>
            </nav>
        </div>
    </header>
    <!-- ตัวสไลด์ ไฟล์จัดการชื่อ slide.js -->
    <div class="slide owl-carousel">
        <?=$myAds?>
    </div> 

    <!-- โซนการจอง -->
    <div class="booking" style="background:url('<?=ROOT_URL?>img/new-bg.jpg')">
        <div class="formBook">
            <div class="input-box">
                <label>เช็คอิน</label>
                <input type="date" class="dateCheck header_checkin" placeholder="วัน-เดือน-ปี">
            </div>

            <div class="input-box">
                <label>เช็คเอ้าท์</label>
                <input type="date" class="dateCheck header_checkout" placeholder="วัน-เดือน-ปี">
            </div>

            <div class="input-box adult">
                <label>ผู้ใหญ่/คน</label>
                <input type="number" id="adult" value="0" disabled>
                <img class="left" src="<?=ROOT_URL?>img/icon/minus.svg" alt="">
                <img class="right" src="<?=ROOT_URL?>img/icon/plus.svg" alt="">
            </div>

            <div class="input-box child">
                <label>เด็ก/คน</label>
                <input type="number" id="child" value="0" disabled>
                <img class="left" src="<?=ROOT_URL?>img/icon/minus.svg" alt="">
                <img class="right" src="<?=ROOT_URL?>img/icon/plus.svg" alt="">
            </div>

            <div class="button"><button>ค้นหา</button></div>
        </div>
    </div>
<?php

/**
 * ********************************************************************************
 * New Code By Developer(kot)
 * ********************************************************************************
 */
session_start();
class Application extends Helper
{
  public $language_available;
  public function __construct()
  {
    //ไปสั่งให้คลาส FrontEnd ทำงาน
    parent::__construct();
    if ($this->language_available == "") {
      $this->language_available = $this->get_language_array();
    }
  }


#[*]=======================================
#[*]            SEO
#[*]=======================================

  #ตั้งค่า ตัวแปร ที่จะไปทำ SEO
  public function setHeaderSEO($data)
  {
    global $head;
    $head['title'] = $data['title'];
    $head['description'] = $data['description'];
    $head['keyword'] = $data['keyword'];
    $head['thumbnail'] = $data['thumbnail'];
    $head['url'] = $data['url'];
  }



#[*]=======================================
#[*]            TABLE CATEGORY
#[*]=======================================

  #ดึงข้อมูล cate_id By cate_name จากตาราง category
  public function getCateIDByCateName($_catename)
  {
    $cateName = filter_var($_catename, FILTER_SANITIZE_STRING);
    $sql = "SELECT cate_id FROM category WHERE cate_name = '" . $cateName . "'";
    $result =  $this->fetch($sql);
    return $result['cate_id'];
  }

  #ดึงข้อมูล cate_id By url จากตาราง category
  public function getCateIDByURL($_url)
  {
    $url = filter_var($_url, FILTER_SANITIZE_STRING);
    $sql = "SELECT cate_id FROM category WHERE url = '" . $url . "'";
    $result =  $this->fetch($sql);
    return $result['cate_id'];
  }

  #ดึงข้อมูล blabla By cate_id จากตาราง category
  #return to object
  public function getCategoryFieldByCateID($_cateid, $field)
  {
    $cateid = filter_var($_cateid, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT " . $field . " FROM category WHERE cate_id = :cate_id";
    $result =  $this->fetchObject($sql, [":cate_id" => $cateid]);
    return $result;
  }

  #ดึงข้อมูล cate_name By cate_id จากตาราง category
  public function getCateNameByCateID($_cateid)
  {
    $cateid = filter_var($_cateid, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT cate_name FROM category WHERE cate_id = '" . $cateid . "'";
    $result =  $this->fetch($sql);
    return $result['cate_name'];
  }

  #ดึงข้อมูล url By cate_id จากตาราง category
  public function getURLByCateID($_cateid)
  {
    $cateid = filter_var($_cateid, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT url FROM category WHERE cate_id = '" . $cateid . "'";
    $result =  $this->fetch($sql);
    return $result['url'];
  }

  #ดึงข้อมูล * By cate_id จากตาราง categor y
  #return array
  public function getCateByCateID($_cateid)
  {
    $cateid = filter_var($_cateid, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT * FROM category WHERE cate_id = '" . $cateid . "'";
    return $this->fetch($sql);
  }

  #ดึงข้อมูล * By cate_id จากตาราง category
  #return object
  public function getCateObjectByCateID($_cateid)
  {
    $cateid = filter_var($_cateid, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT * FROM category WHERE cate_id =:cate_id";
    return $this->fetchObject($sql, [":cate_id" => $cateid]);
  }

  #ดึงข้อมูล * By IN(cate_id) จากตาราง category
  #return fetchAll
  public function getCateByCateArrayID($_cateid)
  {
    $sql = "SELECT * FROM category WHERE cate_id IN(" . implode(',', $_cateid) . ")";
    return $this->fetchAll($sql, []);
  }

  #ดึงข้อมูล field By IN(cate_id) จากตาราง category
  #return fetchAll
  public function getCateFieldByCateArrayID($fields, $_cateid)
  {
    $sql = "SELECT $fields FROM category WHERE cate_id IN(" . implode(',', $_cateid) . ")";
    return $this->fetchAll($sql, []);
  }

  #insert Category
  public function testInsertCategoryArr($data)
  {
    $getCateID = $this->fetchObject("SELECT MAX(cate_id) + 1  as max_id FROM category", []);
    $sql = "INSERT INTO 
              category(cate_id,cate_name,url,topic,title,keyword,description,thumbnail,parent_id,level,display,menu,priority,position,main_page,language,defaults)
              VALUES (:cate_id,:cate_name,:url,:topic,:title,:keyword,:description,:thumbnail,:parent_id,:level,:display,:menu,:priority,:position,:main_page,:language,:defaults)";
    $value = [
      ":cate_id" => $getCateID->max_id,
      ":cate_name" => $data['cate_name'],
      ":url" => $data['url'],
      ":topic" => $data['topic'],
      ":title" => $data['title'],
      ":keyword" => $data['keyword'],
      ":description" => $data['description'],
      ":thumbnail" => $data['thumbnail'],
      ":parent_id" => $data['parent_id'],
      ":level" => $data['level'],
      ":display" => $data['display'],
      ":menu" => $data['menu'],
      ":priority" => $data['priority'],
      ":position" => $data['position'],
      ":main_page" => $data['main_page'],
      ":language" => $data['language'],
      ":defaults" => $data['defaults'],
    ];
    return $this->insertValue($sql, $value);
  }

#[*]=======================================
#[*]            TABLE POST
#[*]=======================================

  #ดึงข้อมูล * จาก table post by  cate_name , slug
  public function getPostByCateIDAndPostSlug($_catID, $_slugname)
  {
    $sql = "SELECT * 
            FROM post 
            WHERE slug = :slug AND category = :cate_id AND display = 'yes' AND defaults = 'yes'
            ORDER BY id ASC";
    return $this->fetchObject($sql, [":slug" => $_slugname, ":cate_id" => $_catID]);
  }

  #ดึงข้อมูล * จาก table post by  cate_name , slug
  public function getPostByCateNameAndPostSlug($_catename, $_slugname)
  {
    $cateid = $this->getCateIdByCateName($_catename);
    $sql = "SELECT * 
                FROM post 
                WHERE slug = '" . $_slugname . "' AND category = '" . $cateid . "' AND display = 'yes' AND defaults = 'yes'
                ORDER BY id ASC";
    return $this->fetch($sql);
  }

  #ดึงข้อมูล * จาก ตาราง post ด้วย category return fetchall
  public function getPostBySQL($cateid, $whereOther = "", $order = "", $limit = "")
  {
    $sql = "SELECT * 
            FROM post 
            WHERE category = '" . $cateid . "' AND display = 'yes' AND defaults = 'yes' $whereOther
            $order
            $limit";
    return $this->fetchAll($sql, []);
  }

  #ดึงข้อมูล content จากตาราง post ด้วย field slug limit 1
  public function get_content_by_slug($url)
  {
    $sql = "SELECT id,title,keyword,description,slug,content,thumbnail
                  FROM post  
                 WHERE slug = :slug 
                 LIMIT 0,1  ";
    $value = array(
      ':slug' => $url
    );
    $data_return  = $this->fetchAll($sql, $value);
    $res  = $data_return[0];
    return $res;
  }

  #insert test table post
  public function testInsertPost($data)
  {
    $getCateID = $this->fetchObject("SELECT MAX(id) + 1  as max_id FROM post", []);
    $getCateID->max_id = empty($getCateID->max_id) ? 1 : $getCateID->max_id;
    $sql = "INSERT INTO 
              post(id,title,keyword,description,slug,thumbnail,category,topic,content,display,post_view,pin,language,defaults,priority)
              VALUES (:id,:title,:keyword,:description,:slug,:thumbnail,:category,:topic,:content,:display,:post_view,:pin,:language,:defaults,:priority)";
    $value = [
      ":id" => $getCateID->max_id,
      ":title" => $data['title'],
      ":keyword" => $data['keyword'],
      ":description" => $data['description'],
      ":slug" => $data['slug'],
      ":thumbnail" => $data['thumbnail'],
      ":category" => $data['category'],
      ":topic" => $data['topic'],
      ":content" => $data['content'],
      ":display" => $data['display'],
      ":post_view" => $data['post_view'],
      ":pin" => $data['pin'],
      ":language" => $data['language'],
      ":defaults" => $data['defaults'],
      ":priority" => $data['priority']
    ];
    $res = $this->insertValue($sql, $value);
    return $res;
  }


  #ดึงข้อมูลรูปภาพ Gallery หน้าแรก
  public function getPostImagesWithCateID($cateID = null)
  {
    $sql = "SELECT pi.image_link , p.title
            FROM post_image as pi
            INNER JOIN post as p ON pi.post_id = p.id
            INNER JOIN category as c ON p.category = c.cate_id 
            WHERE pi.pin = 'yes' AND c.cate_id IN (8,9,10)
            ORDER BY pi.image_id ASC
          ";
    $response = $this->fetchAll($sql, []);
    $html = "";
    foreach ($response as $key => $res) {
      $html .= '<img class="lazy" data-src="' .SITE_THUMBGEN.'?src='. SITE_URL . $res['image_link'] . '&size=500" alt="' . $res['title'] . '">';
    }
    return $html;
  }



#[*]=======================================
#[*]            FUNCTION MAIN
#[*]=======================================

  #ดึงรูปโลโก้หัวเว็บ
  public function getImageWebHeader($_id)
  {
    $sql = "SELECT ad_image,ad_title FROM ads WHERE ad_id = :id AND ad_display = 'yes' ";
    $result = $this->fetchObject($sql, [":id" => $_id]);
    return $result;
  }

  #ดึงโฆษณา จาก position
  public function getImageAdsByPosition($_position)
  {
    $sql = "SELECT ad_image,ad_link,ad_title FROM ads WHERE ad_position = :position LIMIT 1";
    $res = $this->fetchObject($sql, [":position" => $_position]);
    if (!empty($res)) {
      return $res;
    }
  }

  #ดึงโฆษณาทั้งหมด ด้วย array
  public function getImageAdsByPositionArr($_position)
  {
    $sql = "SELECT ad_image,ad_link,ad_title,ad_position
            FROM ads 
            WHERE ad_position IN('" . implode("','", $_position) . "') 
            ";
    $res = $this->fetchAll($sql, []);
    if (!empty($res)) {
      $output = [];
      foreach ($res as $key => $r) {
        $output[$r['ad_position']] = $r;
      }
      return $output;
    }
  }

  #ฟังก์ชั่น ดึงข้อมูล navbar
  public function getNavbarMenu()
  {
    global $cateID;
    $sql = "SELECT cate_id,cate_name,url 
            FROM category 
            WHERE menu = 'yes' AND display = 'yes' AND cate_id <> 1
            ORDER BY priority ASC";
    $result = $this->fetchAll($sql, []);
    $html = "";
    foreach ($result as $key => $res) {
      if ($cateID == $res['cate_id']) {
        $html .= '<li cateid="' . $res['cate_id'] . '"><a href="' . SITE_URL . $res['url'] . '" class="selected">' . $res['cate_name'] . '</a></li>';
      } else {
        $html .= '<li><a href="' . SITE_URL . $res['url'] . '" >' . $res['cate_name'] . '</a></li>';
      }
    }
    $html .= '<li class="closemenu"><i class="fas fa-times"></i></li>';
    return $html;
  }

  #ฟังก์ชั่น ดึงข้อมูล footer navbar
  public function getNavbarFooterMenu()
  {
    $sql = "SELECT cate_id,cate_name,url 
            FROM category 
            WHERE footer_menu = 'yes' AND display = 'yes' AND cate_id <> 1
            ORDER BY footer_priority ASC";
    $result = $this->fetchAll($sql, []);
    $out = "";
    foreach ($result as $key => $res) {
      $out .=
        '<li class="ft-item">
            <a href="' . SITE_URL . $res['url'] . '">' . $res['cate_name'] . '</a>
        </li>';
    }
    $out .= "";
    return $out;
  }

  #ดึงข้อมูล เมนู footer
  public function getFooterInfoByID($id)
  {
    $sql = "SELECT title,description FROM footer_info WHERE display='YES' AND defaults='YES' AND id =:id";
    $res = $this->fetchObject($sql, [":id" => $id]);
    return !empty($res) ? $res : null;
  }

  #ดึง Slide Banner
  public function getSlideCarousel()
  {
    require_once DOC_ROOT."/classes/mobileDetect.class.php";
    $detect = new Mobile_Detect;
    $sql = "SELECT * 
            FROM ads 
            WHERE ad_position = 'pin' AND ad_display = 'yes'
            ORDER BY ad_priority ASC";
    $result = $this->query($sql);
    $slide = '';
    foreach ($result as $res) {
      if( $detect->isMobile()){
        $slide .= '
          <img src="' . SITE_THUMBGEN.'?src='.SITE_URL. $res['ad_image'] . '&size=180"  alt="' . $res['ad_title'] . '">
        ';
      }
      else if( $detect->isTablet()){
        $slide .= '
          <img src="' . SITE_THUMBGEN.'?src='.SITE_URL. $res['ad_image'] . '&size=300"  alt="' . $res['ad_title'] . '">
        ';
      }
      else{
        $slide .= ' <img src="' . SITE_THUMBGEN.'?src='.SITE_URL. $res['ad_uploadImage'] . '&size=700"  alt="' . $res['ad_title'] . '">  ';
      }
    }
    return $slide;
  }

  #ฟังก์ชั่น ดึงข้อมูล getMenuSideBar
  public function getMenuSideBar()
  {
    $sql = "SELECT pc.id,pc.name,c.url
                FROM product_cate as pc
                INNER JOIN category as c ON c.cate_id = pc.cate_id  
                WHERE pc.display = 'yes' AND c.cate_id = 12
                ORDER BY pc.priority ASC";
    $productCate = $this->fetchAll($sql, []);
    $out = "<ul class='menu-left'>";
    foreach ($productCate as $key => $res) {
      if ($res['id'] == 1) {
        $out .= '
          <li>
              <a href="' . SITE_URL . $res['url'] . "/" . $res['name'] . '">' . $res['name'] . '</a>
              <i class="fas fa-bell"></i>
          </li>';
      } else {
        $out .= '
          <li>
            <a href="#">' . $res['name'] . '</a>
        ';
        $sql2 = "SELECT id,name FROM product_sub_cate WHERE display = 'yes' AND product_cate = :cate_id ORDER BY priority ASC";
        $pSubCate = $this->fetchAll($sql2, [":cate_id" => $res['id']]);
        if ($pSubCate) {
          $out .= "<ul class='sub-menu'>";
          foreach ($pSubCate as $key2 => $res2) {
            $out .= '<li><a href="' . SITE_URL . $res['url'] . "/" . $res['name'] . "/" . $res2['name'] . '">' . $res2['name'] . '</a></li>';
          }
          $out .= "</ul>
            <i class=\"fas fa-chevron-right\"></i>
          ";
        }
        $out .= '
        </li>';
      }
    }
    $out .= "</ul>";
    return $out;
  }

  #ฟังก์ชั่น ดึงข้อมูล Text Slide ด้วย id
  public function getTextSlideByID($id)
  {
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT text FROM text_slide WHERE id = :id";
    $value = [":id" => $id];
    $res = $this->fetchObject($sql, $value);
    return $res->text;
  }



  #redirect
  public function redirect($path)
  {
    echo "<script>window.location = '" . $path . "'</script>";
    exit();
  }

  #แปลง date เป็น week return วันอาทิตย์ - วันเสาร์
  public function DayThai($opt)
  {
    switch (date('w', strtotime($opt))) {
      case 0:
        return "วันอาทิตย์";
        break;
      case 1:
        return "วันจันทร์";
        break;
      case 2:
        return "วันอังคาร";
        break;
      case 3:
        return "วันพุธ";
        break;
      case 4:
        return "วันพฤหัสบดี";
        break;
      case 5:
        return "วันศุกร์";
        break;
      case 6:
        return "วันเสาร์";
        break;
    }
  }

  public function convertTime($opt)
  {
    return date('H.i', strtotime($opt));
  }


  public function date_convert($date){
    $mydate = date("Y-m-d",strtotime($date));
    $explode = explode("-",$mydate);
    $month_arr=array(
      "01"=>"มกราคม",
      "02"=>"กุมภาพันธ์",
      "03"=>"มีนาคม",
      "04"=>"เมษายน",
      "05"=>"พฤษภาคม",
      "06"=>"มิถุนายน", 
      "07"=>"กรกฎาคม",
      "08"=>"สิงหาคม",
      "09"=>"กันยายน",
      "10"=>"ตุลาคม",
      "11"=>"พฤศจิกายน",
      "12"=>"ธันวาคม"                 
    );
    $new_date = $explode[2]." ".$month_arr[$explode[1]]." ".($explode[0]+543);
    return $new_date;
  }



  #ใส่โฆษณาใน Content
  // public function addAdsInPostContent($content)
  // {
  //   global $ADS;

  //   $str_replace_1 = str_replace(
  //     '<figure>ads-g1</figure>',
  //     Component::adsvertisement_g(["class" => "ads-g1 mt-0 desktopx", "ads" => $ADS['G1']]),
  //     $content
  //   );
  //   return str_replace(
  //     '<figure>ads-g2</figure>',
  //     Component::adsvertisement_g(["class" => "ads-g2 mt-0 desktopx", "ads" => $ADS['G2']]),
  //     $str_replace_1
  //   );
  // }


  #เพิ่ม view ของหน้านั้นๆ
  public function add_view_visit($table, $id)
  {
    if ($table == "post") {
      $sql = "UPDATE $table SET post_view = post_view+1 WHERE id=:id";
      $res = $this->updateValue($sql, [":id" => $id]);
      return $res;
    } else {
      $sql = "UPDATE $table SET view = view+1 WHERE id=:id";
      $res = $this->updateValue($sql, [":id" => $id]);
      return $res;
    }
  }

  #ดึงข้อมูลเกี่ยวกับ บัญชีธนาคาร
  public function getBanks()
  {
    return $this->fetchAll("SELECT * FROM bank_info", []);
  }


  public function renderBankInfo()
  {
    $response = $this->getBanks();
    $html = "";
    foreach ($response as $key => $res) {
      $html .= '
        <div class="bank">
          <figure><img src="' . $res['img'] . '" alt="' . $res['name'] . '"></figure>
          <div class="detail">
            <span>ชื่อบัญชี ' . $res['name'] . '</span>
            <span>เลขบัญชี ' . $res['number'] . '</span>
          </div>
        </div>
      ';
    }
    return $html;
  }


  #ดึงข้อมูลจังหวัด
  public function getProvince()
  {
    return $this->fetchAll("SELECT * FROM province", []);
  }

  #jaud_property เพลงเพราะ/ดนตรีสด , อาหารอร่อย , บรรยากาศดี
  public function getJuadProperty()
  {
    $sql = "SELECT * FROM juad_property ORDER BY priority ASC";
    $response = $this->fetchAll($sql, []);
    $html = "";
    foreach ($response as $key => $res) {
      $html .= "" . Component::juad_property(["res" => $res]) . "";
    }
    return $html;
  }

#[*]=======================================
#[*]            Register Login Password
#[*]=======================================

  #ฟังก์ชั่นจำลองการ login ถ้าใส่ $opt = true คือ loginแล้ว $opt = false คือ logout
  public function testLogin($opt)
  {
    if (!$opt) {
      unset($_SESSION['member']);
    } else {
      $_SESSION['member']['is_login'] = true;
      $_SESSION['member']['id'] = 119;
      $_SESSION['member']['member_id'] = "884de19d031e1f5281989f7afcfa99267222bc35";
      $_SESSION['member']['username'] = "kotbass";
      $_SESSION['member']['email'] = "email@gmail.com";
      $_SESSION['member']['name'] = "What The Fuck";
      $_SESSION['member']['image_profile'] = "images/webp/sexy2.webp";
      $_SESSION['member']['image_cover'] = "images/header-img-my-cart.jpg";
      $_SESSION['member']['clup_name'] = "สิงดำ";
      $_SESSION['member']['aboutus_detail'] = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto quis, labore rem molestiae tempore quibusdam cum aliquid earum consequuntur eos laudantium sint nisi, accusamus quos dolor molestias saepe, est rerum?";
      $_SESSION['member']['province'] = "ขอนแก่น";
      $_SESSION['member']['phone'] = "0123456789";
      $_SESSION['member']['line_id'] = "@LineIDDerr";
      $_SESSION['member']['confirm_regis'] = date('Y-m-d H:i:s');
      $_SESSION['member']['confirm_regis_status'] = "NO";
    }
    // $this->redirect('/');
  }

  #เช็คว่า login หรือยัง return true/false
  public function isLogin($opt)
  {
    if (!$opt) {
      #ถ้าlogin แล้ว จะ return false 
      #ถ้ายังไม่ login จะ return true
      return !isset($_SESSION['member']['is_login']) ? true : false;
    } else {
      #ถ้าlogin แล้ว จะ return true 
      #ถ้ายังไม่ login จะ return false
      return (isset($_SESSION['member']['is_login']) && !empty($_SESSION['member']['is_login'])) ? true : false;
    }
  }

  public function getMemberInfo()
  {
    if ($this->isLogin(true)) {
      $sql = "SELECT * FROM members WHERE mem_id = :mem_id LIMIT 1";
      $res = $this->fetchObject($sql, [":mem_id" => $_SESSION['member']['member_id']]);
      return (!empty($res)) ? $res : null;
    }
  }

  public function getMemberIDByID($id)
  {
    $sql = "SELECT mem_id FROM members WHERE id = :id LIMIT 1";
    $res = $this->fetchObject($sql, [":id" => $id]);
    return (!empty($res)) ? $res : null;
  }

  public function getMemberIDBy_memid($id)
  {
    $sql = "SELECT id FROM members WHERE mem_id = :id LIMIT 1";
    $res = $this->fetchObject($sql, [":id" => $id]);
    return (!empty($res)) ? $res : null;
  }

  public function logout()
  {
    unset($_SESSION['member']);
    unset($_SESSION['orders']);
    $this->redirect('/');
  }

    #ดึงข้อมูลเว็บ ชื่อ เบอร์โทร facebook ig youtube twitter
    public function getContactWeb()
    {
      $sql = "SELECT * FROM contact_sel WHERE id = 1";
      $res = $this->fetchObject($sql, []);
      return $res;
    }


      /* =========================================================================
      *     
      *                              BRIGHT-HOTEL START
      *
      * ========================================================================= */

    public function getNavtop($on_page){
      global $thumbgenerator,$myDevice;
      if($myDevice != "mobile"){
        $logoXsize = "x600";
      }else {
        $logoXsize = "x300";
      }

      $sql ="SELECT *,(SELECT url FROM category as cate WHERE cate.cate_id = category.parent_id) as parent_url FROM category WHERE display = 'yes' AND menu = 'yes' ORDER BY level DESC, priority ASC ";
      $result = $this->fetchAll($sql,[]);
      // $datein_out = (isset($_SESSION['cart']['result']['datein']) && isset($_SESSION['cart']['result']['dateout']) )? date('d-m-Y',strtotime($_SESSION['cart']['result']['datein']))."/".date('d-m-Y',strtotime($_SESSION['cart']['result']['dateout'])):"";
      
      if(!empty($result)){
        $setNavHTML ="";
        $setNavChild = array();
        foreach($result as $key=> $val){
          $setActive = ($on_page == $val['url'])?"active":"";

          if($val['level'] > 0 ){
            #หมวดหมู่ย่อย
            $setNavChild[$val['parent_id']]['html'] = (!isset($setNavChild[$val['parent_id']]['html']))?"":$setNavChild[$val['parent_id']]['html'];
            $setNavChild[$val['parent_id']]['html'] .= '<li><a  href="'.ROOT_URL.$val['parent_url'].'/'.$val['url'].'/'.$datein_out.'">'.$val['title'].'</a></li>';
          }else {
            #หมวดหมู่หลัก
            #ถ้ามี sub-category ทำส่วนนี้
            if(isset($setNavChild[$val['cate_id']])){
              $setNavHTML .=' <li class="menulist">
                                  <div href="#" class="room" onclick="toggleSubRoom()">'.$val['title'].' <img src="'.ROOT_URL.'img/icon/down-arrow.svg" alt="arrow nav-menu-bar">
                                    <ul class="subroom">'.$setNavChild[$val['cate_id']]['html'].' 
                                    </ul> 
                                  </div> 
                                </li>';
            }else{
              #ถ้าไม่มี sub-category ทำส่วนนี้
              $setNavHTML .= '<li class="menulist"><a class="'.$setActive.'"  href="'.ROOT_URL.$val['url'].'">'.$val['title'].'</a></li>';
            }
          }
        }
      }
      return $setNavHTML;
    }
 
    public function get_category_facilities_by_pin(){
        $sql = 'SELECT * FROM facility_list WHERE fac_status = "active" AND fac_pin = "yes" ORDER BY priority ASC ';
        $result = $this->fetchAll($sql,[]);
        $ret = "";
        if(!empty($result)){
          foreach($result as $key => $val){ 
              $ret .='  <div class="column">
                            <figure><img src="'.ROOT_URL.$val['fac_thumbnail'].'" alt="ภาพประกอบสิ่งอำนวยความสะดวก'.$val['fac_name'].'"></figure>
                            <span>'.$val['fac_title'].'</span>
                            <div class="ck">
                                <p>'.$val['fac_description'].'</p>
                            </div>
                        </div>';
          }
        }else{
              $ret ='<div></div> <div class="column">  <span>ยังไม่มีสิ่งอำนวยความสะดวก</span>  </div> <div></div> ';
        }
        return $ret;
    }


    public function home_get_product_by_pin(){
      $sql ='SELECT * FROM product_room WHERE product_pin = "yes" ';
      $result =$this->fetchAll($sq,[]);

      if(!empty($result) ){
        foreach($result as $key =>$val){
        }
      }else{

        $ret ="ไม่พบรายการ";
      } 

      return $ret;
    }


    public function get_room(){
      global $thumbgenerator,$myDevice;
      $xSize = ($myDevice === "browser")? "x400":"x250";
      #set category product = room
      $sql ='SELECT * FROM room_product WHERE (room_status = :room  AND room_amount > 0 ) ORDER BY room_priority ASC  ';
      $result = $this->fetchAll($sql,[":room" => "active" ]);
      $sql_facility = 'SELECT * FROM facility_list WHERE fac_status = ? ';
      $resultFacility = $this->fetchAll($sql_facility,["active"]);
      if(!empty($resultFacility)){
          $facArr = array();
          foreach($resultFacility as $key =>$fac){
            $facArr[$fac['fac_id']] = '<div class="item">
                                        <span>'.$fac['fac_name'].'</span>
                                        <img src="'.ROOT_URL.$fac['fac_thumbnail'].'" alt="ภาพประกอบสิ่งอำนวยความสะดวก '.$fac['fac_name'].'">
                                       </div>'; 
          }
      }
      $html ="";
      if(!empty($result)){
        foreach($result as $key =>$val){
          $roomFac = explode(",",$val['room_facility']);
          $setFacility ="";
          if(!empty($roomFac)){
            foreach($roomFac as $value){
              $setFacility .= $facArr[$value];
            }
          }
          $html .= '  <div class="item-room">
                        <div class="box"> 
                            <figure>
                                <span>'.$val['room_type_name'].'</span>
                                <img src="'.$thumbgenerator.$val['room_thumbnail'].'&size='.$xSize.'" alt="ภาพประกอบ '.$val['room_type_name'].'">
                            </figure>
                            <div class="price">
                                <div class="discount"><span>฿ '.number_format(($val['room_price'] + $val['breakfast_price'])).'</span></div>
                                <div class="sumprice"><span>฿ '.number_format(($val['room_current_price']+ $val['breakfast_price'])).'</span></div>
                            </div>
                            <div class="description">
                                <p> '.$val['room_title'].' </p>
                            </div>
                            <div class="inroom">'.$setFacility.'</div>
                        </div>
                        <div class="button">
                            <button>จอง</button>
                        </div>
                    </div>';
        }
      }else {
        $html = "PRODUCT WAS NOT FOUND";
      }
      return $html;
    }

    public function get_more_room_image($room_id,$type){
      global $thumbgenerator,$myDevice;
      $xSize = ($myDevice === "browser")? "x500":"x200";
      $srcSize = ($myDevice === "browser")? "x600":"x200";
      if($room_id == ""){ 
        $image_sql = 'SELECT * FROM room_images WHERE display = "yes" AND type = :room_type ORDER BY room_type_id ASC , priority ASC   ';
        $resultImg = $this->fetchAll($image_sql,[":room_type"=>$type]);
      }else{
        $image_sql = 'SELECT ri.* FROM room_images as ri 
                      INNER JOIN room_product as rp ON rp.room_id =  ri.room_type_id 
                      WHERE ri.display = "yes" AND rp.room_code = :room AND ri.type = :room_type ORDER BY ri.room_type_id ASC , ri.priority ASC   ';
        $resultImg = $this->fetchAll($image_sql,[":room"=>$room_id,":room_type"=>$type]); 
      } 
      if(!empty($resultImg)){
        $imgArr = array();
        foreach($resultImg as $key =>$rm){
          $imgArr[$rm['room_type_id']] .=  '<figure><img class="active" src="'.$thumbgenerator.$rm['url'].'&size=100x" data-src="'.ROOT_URL.$rm['url'].'" alt="ภาพประกอบห้อง '.$rm['title'].'"></figure>';

        }
      }
      return $imgArr;
    } 
    
    public function get_all_product_detail($getpost){
        global $thumbgenerator,$xSize;  
        $searchDate = "";
        if(isset($getpost['level_3'])){
          $lvl3 =  FILTER_VAR($getpost['level_3'],FILTER_SANITIZE_MAGIC_QUOTES);
          $datein = date("Y-m-d", strtotime($lvl3));
        }
        if(isset($getpost['level_4'])){
          $lvl4 = FILTER_VAR($getpost['level_4'],FILTER_SANITIZE_MAGIC_QUOTES);
          $dateout = date("Y-m-d", strtotime($lvl4)); 
        }
        if(isset($lvl4) && isset($lvl3)){
          $setBetween = " AND ( rso.date_checkin BETWEEN '".$datein."' AND '".$dateout."' )";
          $sql =' SELECT rp.room_id,rp.room_type_name,rp.room_code,COUNT(rp.room_id) as amount   FROM room_product as rp   
                  INNER JOIN reserve_detail as rsd ON rsd.room_type = rp.room_code   
                  INNER JOIN reserve_order as rso ON rso.resv_code = rsd.code   
                  WHERE rp.room_status = "active" AND rso.resv_status = "publish"  
                  '.$setBetween.'
                  GROUP BY rp.room_id ORDER BY rp.room_priority ASC ';
          $reserved = $this->fetchAll($sql,[]);
        }


        if(!empty($reserved)){
          foreach($reserved as $key =>$val){
            $reservedRoom[$val['room_code']] = $val['amount'];
          }
        }
        $sql ='SELECT * FROM room_product WHERE room_status = "active" ORDER BY room_priority ASC ';
        $result = $this->fetchAll($sql,[]); 
        $imgArr = $this->get_more_room_image("","product");
        $facArr = $this->get_all_facility('get_room');

        if(!empty($result)){ 
            $html="";
            foreach($result as $key => $val){
              if(!isset($reservedRoom[$val['room_code']])){
                $reservedRoom[$val['room_code']] = 0;
              }
              if(isset($lvl4) && isset($lvl3)){
                if(($val['room_amount'] - $reservedRoom[$val['room_code']]) < 1){
                  continue;
                }
              }
              
              $setFacility = "";
              $exp_fac = explode(",",$val['room_facility']);
              if(!empty($exp_fac)){
                foreach($exp_fac as $facs){
                  if($facs != ""){
                    $setFacility .=  $facArr[$facs];
                  }
                }
              }
              $html .= '      
              <div class="list-room">
                 <div class="img-review">
                  <figure><img src="'.$thumbgenerator.$val['room_thumbnail'].$xSize.'" alt=""></figure>
                  <div class="virwFull" data-room="'.$val['room_code'].'"><p>See More</p></div>
                  <div class="carousel">
                      <div class="list-img">
                        '.( (isset($val['room_thumbnail']))? '<figure><img class="active" src="'.$thumbgenerator.$val['room_thumbnail'].$xSize.'" data-src="'.ROOT_URL.$val['room_thumbnail'].'" alt="ภาพประกอบห้อง '.$val['title'].'"></figure> '  : "").' 
                        '.$imgArr[$val['room_id']].'
                      </div>
                  </div>
              </div>
                <div class="detail-room">
                    <div class="title-room">
                        <h2>'.$val['room_type_name'].'</h2>
                    </div>
                    <div class="priceBeforeSale">
                        <span class="price">฿ '.number_format(($val['room_price']+ $val['breakfast_price'])).'</span>
                        <span> บาท / คืน</span>
                    </div>
                    <div class="currentPrice">
                        <h3>฿ '.number_format(($val['room_current_price'] + $val['breakfast_price'])).'</h3>
                    </div>
                    <div class="facilities">
                        <p>'.$val['room_description'].'</p>
                    </div>
                    <div class="inroom">
                        '.$setFacility.'
                    </div>
                    <div class="button">
                        <button class="btn_reserve" data-room="'.$val['room_code'].'" >จอง</button>
                    </div>
                </div>
            </div> ';
            }
          }else {
            $html = "ยังไม่มีรายการสินค้า";
          }
          return $html;
    }

    public function get_slide_banner(){
      global $thumbgenerator,$myDevice;
      $xSize = ($myDevice != "mobile")?"x600":"x300";
      $sql ="SELECT * FROM ads WHERE ad_position = 'slide'  AND ad_display ='yes' ORDER BY ad_priority ASC";
      $result = $this->fetchAll($sql,[]);
      if(!empty($result)){
        $html = "";
        foreach($result as $key =>$val){
          $html .= '<div class="item"><img src="'.$thumbgenerator.$val['ad_image'].'&size='.$xSize.'" alt="'.$val['ad_title'].'"></div>';
        }
      }else {
        $html =" SLIDE NOT FOUND";
      }
      return $html;
    }

    public function get_level_2($lvl2){
      $sql = 'SELECT cate_id FROM category WHERE url = :url ';
      $result = $this->fetchObject($sql,[":url"=>$lvl2]);
      $ret['id'] = $result->cate_id;
      $ret['res'] = (!empty($result))?true:false;
      return $ret;
    }

    public function get_all_facility($part){
      $fac_sql = 'SELECT * FROM facility_list WHERE fac_status = "active" ';
      $resultFac = $this->fetchAll($fac_sql,[]);
      if(!empty($resultFac)){
        foreach($resultFac as $key => $fac){
          if($part == "get_room"){
            $facArr[$fac['fac_id']] =' <div class="item">
                <span>'.$fac['fac_title'].'</span>
                <img src="'.ROOT_URL.$fac['fac_thumbnail'].'" alt="ภาพประกอบสิ่งอำนวยความสะดวก'.$fac['fac_title'].'">
            </div>'; 
          }
          if($part == "detail"){
            $facArr[$fac['fac_id']] =' <div class="item">
                                        <img src="'.ROOT_URL.$fac['fac_thumbnail'].'"  alt="ภาพประกอบสิ่งอำนวยความสะดวก'.$fac['fac_title'].'">
                                        <p>'.$fac['fac_description'].'</p>
                                    </div>';
          }
        }
      }
      return $facArr;
    }

    public function order_in_cart(){
      if(!empty($_SESSION['my_order'])){
      }
    }

    public function remove_order(){
      unset($_SESSION['my_order']['room']);
    }

    public function getAllMyGalleryImage(){
      global $thumbgenerator,$myDevice;
      $setSize = ($myDevice !== "mobile")?"&size=x700":"&size=x300";
      $sql ="SELECT * FROM gallery_image WHERE display = :display  ORDER BY date_create DESC LIMIT 0,15";
      $result = $this->fetchAll($sql,[":display"=>"yes"]); 
      if(!empty($result)){
        $ret['html'] = "";
        $amount = count($result);
        if($amount > 14){
          $ret['more'] = '<button  onClick="get_more_images()">More</button>';
        }
        foreach($result as $key =>$val){
          $ret['html'] .= '<figure><img src="'.$thumbgenerator.$val['thumbnail'].$setSize.'" alt="'.$val['title'].'"></figure> ';
        }
      }
      return $ret;
    }

    public function set_cart_detail(){
      if(!empty($_SESSION['my_order'])){
        $html = '';
        foreach($_SESSION['my_order'] as $key => $val){
          $count = count($val);
          $price = $val[0]['price'] + $val[0]['breakfast_price'];
          if($count > 0){
            $html .= '<div class="list-item" data-id="'.$val[ 0]['id'].'">
                    <span class="nameRoom">'.$val[0]['room'].'</span>
                    <div class="amound-room">
                        <span class="minus">-</span>
                        <span class="amound-room"><p>'.($count).'</p> ห้อง</span>
                        <span class="plus">+</span>
                    </div>
                    <span class="amound-pricePerDay">'.number_format($price).' บาท/คืน</span>
                    <div class="delete" data-id="'.$val[0]['id'].'">X</div>
                </div>';
          }else{
            $html .= '<div class="list-item" data-id="'.$val[0]['id'].'>
                      <span class="nameRoom">'.$val[0]['room'].'</span>
                      <div class="amound-room">
                          <span class="minus">-</span>
                          <span class="amound-room"><p>'.($val[0]['position']+1).'</p> ห้อง</span>
                          <span class="plus">+</span>
                      </div>
                      <span class="amound-pricePerDay">'.number_format($price).' บาท/คืน</span>
                      <div class="delete">X</div>
                  </div>'; 
          }  
        }
      } 
      
      return $html;
    }

    public function set_date_format($date_in,$date_out){
      $timesql ="SELECT time_checkin,time_checkout FROM room_product ";
      $chktime = $this->fetchObject($timesql,[]);
      $date_in = FILTER_VAR($date_in,FILTER_SANITIZE_MAGIC_QUOTES);
      $date_in = date("Y-m-d",strtotime($date_in));
      $date['date_in'] = date("Y-m-d H:i:s", strtotime($date_in.$chktime->time_checkin));

      $date_out = date("Y-m-d",strtotime($date_out));
      $date_out = FILTER_VAR($date_out,FILTER_SANITIZE_MAGIC_QUOTES);
      $date['date_out'] = date("Y-m-d H:i:s", strtotime($date_out.$chktime->time_checkout));
      
      $_SESSION['cart']['result']['datein'] = $date['date_in'];
      $_SESSION['cart']['result']['dateout'] = $date['date_out'];
      
      return $date;
    }

    public function reset_cart($date_in,$date_out){
      if((!isset($_SESSION['cart']['result']['datein']) || $_SESSION['cart']['result']['datein'] !== $date_in) 
      || (!isset($_SESSION['cart']['result']['dateout']) || $_SESSION['cart']['result']['dateout'] !== $date_out)){
        unset($_SESSION['cart']);
        unset($_SESSION['my_order']);
        unset($_SESSION['discount']);
        $_SESSION['cart']['result']['datein'] = $date_in;
        $_SESSION['cart']['result']['dateout'] = $date_out;
        $_SESSION['cart']['result']['amount']= 0;
      }
    }

    public function calculate_cost(){
      $_SESSION['cart']['result']['breakfast'] = 0;
      $_SESSION['cart']['result']['amount'] = 0;
      $_SESSION['cart']['result']['price'] = 0;
      $_SESSION['cart']['result']['extra'] = 0; 
      $_SESSION['cart']['result']['discount'] = 0;
      $_SESSION['cart']['result']['netpay'] = 0;
      $_SESSION['cart']['result']['discount_desc']="";
      $_SESSION['room_result'] = "";
      $_SESSION['result']['dateout'] = "";
      $set = $this->set_date_format($_SESSION['cart']['result']['datein'],$_SESSION['cart']['result']['dateout']);
      $date1=date_create($set['date_in']);
      $date2=date_create($set['date_out']);
      $diff = date_diff($date1,$date2);
      $duration = $diff->days+1;
      if(count($_SESSION['my_order'])>0){ 
        foreach($_SESSION['my_order'] as $key => $val){ 
          $discount = (isset($_SESSION['discount'][$key]))?$_SESSION['discount'][$key]['discount']:0; 
          $round = 0; 
          $breakfast = 0;
          $room_discount = 0; 
          foreach($val as $aa){ 
            // $aa['price'] = ($aa['breakfast'] == 'yes')? ($aa['price'] + $aa['breakfast_price']) : $aa['price'];
            $breakfast = ($aa['breakfast'] == 'yes')?$aa['breakfast_price']:0;
            $extra = ($aa['adult'] > 2 )? (($aa['adult'] - 2) * $aa['extra']):0;
            $netpay = (($aa['price'] + $breakfast ) + $extra) - $discount;
            $_SESSION['cart']['result']['amount'] = $duration;
            $_SESSION['cart']['result']['price'] += ($aa['price'] * $duration);
            $_SESSION['cart']['result']['breakfast'] += ($aa['breakfast'] =="yes")?(float)$aa['breakfast_price'] * $duration:0;
            $_SESSION['cart']['result']['extra'] += ($extra * $duration);
            $_SESSION['cart']['result']['discount'] += ($discount * $duration);
            $_SESSION['cart']['result']['netpay'] += $netpay * $duration ;
            $room_discount += $discount * $duration;
            if($round == 0){ 
                $_SESSION['room_result'] .= '<div class="list-item" data-id="'.$aa['id'].'">
                          <span class="nameRoom">'.$aa['room'].'</span>
                          <span class="amound-room"><span class="amr">'.count($_SESSION['my_order'][$aa['id']]).'</span> ห้อง</span>
                          <span class="amound-pricePerDay">'.$aa['price'].' บาท/คืน</span>
                      </div>';
                $_SESSION['cart']['room_result'] = $_SESSION['room_result'];
            }
            $round++;
          }

          if($discount != 0){
            $_SESSION['cart']['result']['discount_desc'] .=  
            ' <span class="txt-dis" data-room="'.$val[0]['id'].'">'.$_SESSION['discount'][$key]['name'].'</span>
                <span class="txt-dis room-discount" data-room="'.$val[0]['id'].'">'.$room_discount.'</span>  ';
          }
          
        }
      }
 

      return $_SESSION['cart'];
    }

    public function get_promotion(){
      $date = date("Y-m-d H:i:s");
      $sql ="SELECT  pro.*,rp.room_code,rp.room_type_name FROM reserve_promotion  as pro 
             INNER JOIN room_product as rp ON pro.pro_roomtype_id = rp.room_id
             WHERE pro.pro_status = :status AND ( pro.pro_date_available < :mindate AND pro.pro_date_expire > :maxdate ) 
             ORDER BY pro.pro_pin ASC, pro.pro_date_expire DESC ";
      $result = $this->fetchAll($sql,[":status"=>"publish",":mindate"=>$date,":maxdate"=>$date]);
      if(!empty($result)){
        $html ="";
        foreach($result as $key =>$val ){
          $html .='<div class="item">
                    <div class="head">
                        <img src="'.ROOT_URL.$val['thumbnail'].'" alt="ภาพประกอบส่วนลด">
                        <h2 class="code">'.$val['pro_code'].'</h2>
                        <h5 class="room">'.$val['room_type_name'].'</h5>
                    </div>
                    <div class="description">
                        <p>'.$val['pro_description'].'</p>
                    </div>
                </div>';
        }
      }
      return $html;
    }

    public function check_voucher_code($code){
      $date = date('Y-m-d H:i:s');
      $sql ="SELECT pro_code,discount,pro_roomtype_id FROM reserve_promotion 
              WHERE pro_status = :pro_status 
              AND pro_code = :code 
              AND (pro_date_available < :datea AND pro_date_expire > :dateb )  
              AND quota > 0 
              ";
      $result = $this->fetchObject($sql,[":pro_status"=>"publish", ":code"=>$code ,":datea"=>$date,":dateb"=>$date]); 
      if(!empty($result)){
          return $result;
      }else{
          return 'error';
      }
    }

    public function get_confirm_order(){
      global $lang_config;
      if(count($_SESSION['my_order'])>0){ 
        $ret['detail']="";
        $ret['result']="";
        foreach($_SESSION['my_order'] as $key => $val){
          $round = 0;
          $discount = (isset($_SESSION['discount'][$key]))?$_SESSION['discount'][$key]['discount']:0;
          foreach($val as $aa){ 
            $price = $aa['price'] + $aa['breakfast'];
            $ret['detail'] .='<div class="bookingRoom" data-room="'.$aa['id'].'" data-position="'.$aa['position'].'">
                  <div class="remove-order unslc-text">X</div>
                  <div class="row">
                      <div class="nameroom"><h2>'.$aa['room'].'</h2></div>
                      <div class="price"><h2>'.number_format($price).' บาท/คืน</h2></div>
                  </div>
                  <div class="row">
                      <div class="form-grid">
                          <div class="input-box">
                              <label>*ชื่อ (ผู้เข้าพัก)</label>
                              <input type="text" class="fill-int txt_guest_name"  placeholder="กรอกชื่อผู้เข้าพัก">
                          </div>
                          <div class="input-box">
                              <label>*นามสกุล (ผู้เข้าพัก)</label>
                              <input type="text" class="fill-int txt_guest_lastname"  placeholder="กรอกนามสกุลผู้เข้าพัก">
                          </div>
                          <div class="input-box adult">
                              <label>'.$lang_config['page_confirm_order_label_adult'].'</label>
                              <input type="number" class="inputAdult" value="'.$aa['adult'].'" disabled>
                              <img class="left" src="'.ROOT_URL.'img/icon/minus.svg" alt="">
                              <img class="right" src="'.ROOT_URL.'img/icon/plus.svg" alt="">
                          </div>
                          <div class="input-box child">
                              <label>'.$lang_config['page_confirm_order_label_child'].'</label>
                              <input type="number" class="inputChild" value="'.$aa['child'].'" disabled>
                              <img class="left" src="'.ROOT_URL.'img/icon/minus.svg" alt="">
                              <img class="right" src="'.ROOT_URL.'img/icon/plus.svg" alt="">
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="breakfast">
                        <input type="checkbox" class="breakfast-check"  '.(($aa['breakfast'] == 'yes')?"checked":"").'>
                        <label for="breakfast-check">รับอาหารเช้า</label>
                      </div>
 
                  </div>

                  <div class="row">
                      <div class="ps">
                          <p>'.$aa['note'].' </p>
                      </div>
                  </div>
              </div>';

              if($round == 0){
                $ret['result'].= '<div class="list-item" data-id="'.$aa['id'].'">
                                    <span class="nameRoom">'.$aa['room'].'</span>
                                    <span class="amound-room"><span class="amr">'.count($_SESSION['my_order'][$aa['id']]).'</span> ห้อง</span>
                                    <span class="amound-pricePerDay">'.$aa['price'].' บาท/คืน</span>
                                </div>';
              }

              $round++;
          }
        }
      } 
      return $ret;
    }

    public function check_room_available_array($getpost){
       $sql ='SELECT rp.*,COUNT(rp.room_id) as amount FROM room_product as rp    
              INNER JOIN reserve_detail as rsd ON rsd.room_type = rp.room_code    
              INNER JOIN reserve_order as rso ON rso.resv_code = rsd.code    
              WHERE rp.room_status = "active"   
              AND ( '.$getpost['condition'].' )     
              AND rso.resv_status != "fail"   
              AND ( ((rso.date_checkin BETWEEN :check_in AND :check_out) OR (rso.date_checkout BETWEEN :check_in AND :check_out)) 
                    OR 
                    (rso.date_checkin <= :check_in AND rso.date_checkout >= :check_out))   
              HAVING (rp.room_amount - amount) < 1 '; 
        $sqlArr = [ 
          ":check_in"=>$getpost['datein'],
          ":check_out"=>$getpost['dateout']
        ]; 
        $result = $this->fetchAll($sql,$sqlArr);

        return $result;
    }

    public function check_room_available_object($getpost){
      $sql =' SELECT rp.*,COUNT(rp.room_id) as amount FROM room_product as rp    
              INNER JOIN reserve_detail as rsd ON rsd.room_type = rp.room_code    
              INNER JOIN reserve_order as rso ON rso.resv_code = rsd.code    
              WHERE rp.room_status = "active"   
              AND rp.room_code = :room_id    
              AND (rso.resv_status = "publish" OR rso.resv_status = "pending")
              AND ( (
                      (rso.date_checkin BETWEEN :check_in AND :check_out) 
                      OR 
                      (rso.date_checkout BETWEEN :check_in AND :check_out)
                    )  
                    OR 
                    (rso.date_checkin <= :check_in AND rso.date_checkout >= :check_out)
                  )       
              GROUP BY rp.room_id    '; 
 
        $sqlArr = [ 
          ":check_in"=>$getpost['datein'],
          ":check_out"=>$getpost['dateout'],
          ":room_id" =>$getpost['room']
        ]; 
        $result = $this->fetchObject($sql,$sqlArr); 
        return $result;
    }

    public function get_detail_confirm_payment($getpost){ 
      $detailArr = array();
      $sql = "SELECT rsc.*,rsd.* 
                     ,rso.resv_action 
                     ,rso.date_checkin 
                     ,rso.date_checkout  
                     ,rp.room_current_price   
                     ,rp.room_code  
                     ,rp.room_type_name 
                     ,rp.room_extra   
                     ,pro.pro_status  
                     ,pro.discount 
                     ,pro.pro_date_available  
                     ,pro.pro_date_expire 
              FROM reserve_order as rso 
              INNER JOIN reserve_detail as rsd ON rso.resv_code = rsd.code 
              INNER JOIN reserve_contact as rsc ON rso.resv_code = rsc.code 
              INNER JOIN room_product as rp ON rp.room_code = rsd.room_type  
              LEFT JOIN reserve_promotion as pro ON rsd.discount_code = pro.pro_code 
              WHERE rso.resv_action = :code AND rso.resv_status = :order_status  
              ORDER BY rsd.id ASC ";
        $result = $this->fetchAll($sql,[":code"=>$getpost['id'],":order_status" => "pending"]);
        if(!empty($result)){
          $setSession =  $this->set_session_order($result); 
          $list = $result[0]; 
          $_SESSION['cart']['result']['datein'] = date("Y-m-d",strtotime($list['date_checkin']));
          $_SESSION['cart']['result']['dateout']= date("Y-m-d",strtotime($list['date_checkout']));  
          $detailArr = $this->calculate_cost();
          $setform =  $this->set_form_detail_confirm_payment($result); 
          $detailArr['html'] = $setform;
          $detailArr['result']['datein'] = $_SESSION['cart']['result']['datein'];
          $detailArr['result']['dateout'] = $_SESSION['cart']['result']['dateout'];
          $detailArr['result']['id'] = $list['resv_action'];
          $detailArr['contact']['name'] = $list['contact_name'];
          $detailArr['contact']['lastname'] = $list['contact_lastname'];
          $detailArr['contact']['tel'] = $list['contact_tel'];
          $detailArr['contact']['email'] = $list['contact_email'];
          $detailArr['contact']['line'] = $list['contact_line'];
          $detailArr['contact']['address'] = $list['contact_address'];
          $detailArr['contact']['district'] = $list['contact_district'];
          $detailArr['contact']['subdistrict'] = $list['contact_subdistrict'];
          $detailArr['contact']['province'] = $list['contact_province'];
          $detailArr['contact']['postcode'] = $list['contact_postcode'];
          $detailArr['contact']['description'] = $list['contact_description'];
          $detailArr['contact']['otp'] = $list['contact_otp']; 
          $detailArr['contact']['taxinvoice_name'] = $list['contact_taxinvoice']; 

        }
     

      return $detailArr;
    }

    public function select_bank_option(){
      $sql ="SELECT * FROM bank_info";
      $result = $this->fetchAll($sql,[]);
      if(!empty($result)){
        foreach($result as $key => $val){
          $ret .= "<option value='".$val['id']."'>".$val['account']." 056-8-89558-4 (".$val['name']." )</option>";
        }
      }
      return $ret;
    }

    public function set_session_order($getpost){
      if(!empty($getpost)){
        unset($_SESSION['my_order']);
        unset($_SESSION['discount']);
        foreach($getpost as $key => $val){ 

          $count  = count($_SESSION['my_order'][$val['room_code']]);
          $_SESSION['my_order'][$val['room_code']][$count]['position'] = $count;
          $_SESSION['my_order'][$val['room_code']][$count]['id'] = $val['room_code'];
          $_SESSION['my_order'][$val['room_code']][$count]['room'] = $val['room_type_name'];
          $_SESSION['my_order'][$val['room_code']][$count]['price'] = $val['room_current_price'];
          $_SESSION['my_order'][$val['room_code']][$count]['extra'] = $val['room_extra'];
          $_SESSION['my_order'][$val['room_code']][$count]['adult'] = $val['adult'];
          $_SESSION['my_order'][$val['room_code']][$count]['child'] =$val['children'];
          $_SESSION['my_order'][$val['room_code']][$count]['note'] = $val['room_note'];
          $_SESSION['discount'][$val['room_code']]['code'] = $val['pro_code'];
          $_SESSION['discount'][$val['room_code']]['discount'] = $val['discount'];
        }
      }

    }
     
    public function set_form_detail_confirm_payment($getpost){
      global $lang_config;
      if(!empty($getpost)){
        $html =""; 
        foreach($getpost as $key => $val){  
          $html .= '<div class="bookingRoom">
                    <div class="row">
                        <div class="nameroom"><h2>'.$val['room_type_name'].'</h2></div>
                        <div class="price"><h2>'.number_format($val['room_current_price']).' บาท/คืน</h2></div>
                    </div>
                    <div class="row">
                        <div class="form-grid">
                            <div class="input-box">
                                <label>'.$lang_config['page_confirm_booking_input_label_name'].'</label>
                                <input type="text" value="'.($val['guest_name']).'" placeholder="กรอกชื่อผู้เข้าพัก" disabled>
                            </div>
                            <div class="input-box">
                                <label>'.$lang_config['page_confirm_booking_input_label_lastname'].'</label>
                                <input type="text" value="'.($val['guest_lastname']).'" placeholder="กรอกนามสกุลผู้เข้าพัก" disabled>
                            </div>
                            <div class="input-box adult">
                                <label>'.$lang_config['page_confirm_booking_input_label_adult'].'</label>
                                <input type="number"   value="'.$val['adult'].'" disabled>
                            </div>
                            <div class="input-box child">
                                <label>'.$lang_config['page_confirm_boooking_input_label_child'].'</label>
                                <input type="number"   value="'.$val['children'].'" disabled>
                            </div> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="ps">
                            <p>'.$lang_config['page_confirm_booking_message'].' </p>
                        </div>
                    </div>
                </div>';
        } 
      }
      return $html;
    }
 
    public function check_reservation_order_id($getpost){
      $sql = "SELECT resv_action FROM reserve_order  WHERE resv_action = :code";
      $result = $this->fetchObject($sql,[":code"=>$getpost['id']]);
      return $result;
    } 



    public function check_history_result($tel){
      $sql ="SELECT * FROM reserve_contact as rc 
              INNER JOIN reserve_order as ro ON ro.resv_code = rc.code 
              WHERE rc.contact_tel = :tel 
              AND (ro.resv_status = 'pending' OR ro.resv_status = 'publish')
             ";
      $result = $this->fetchObject($sql,[":tel"=>$tel]);
      if(!empty($result)){
        return true;
      }else{
        return false;
      }
    }

    public function get_meeting_room(){
      global $thumbgenerator,$xSize;
      $sql_facility = 'SELECT * FROM facility_list WHERE fac_status = ? ';
      $resultFacility = $this->fetchAll($sql_facility,["active"]);
      if(!empty($resultFacility)){
          $facArr = array();
          foreach($resultFacility as $key =>$fac){
            $facArr[$fac['fac_id']] = '<div class="item">
                                        <span>'.$fac['fac_name'].'</span>
                                        <img src="'.ROOT_URL.$fac['fac_thumbnail'].'" alt="ภาพประกอบสิ่งอำนวยความสะดวก '.$fac['fac_name'].'" >
                                       </div>'; 
          }
      }

      $imgArr = $this->get_more_room_image("","meeting");
      $sql = "SELECT * FROM room_meeting WHERE display = 'yes' ";
      $result = $this->fetchAll($sql,[]);
      if(!empty($result)){
        $html ="";
        $images .= $imgArr[$aa];
        foreach($result as $key =>$val){
          $facility ="";
          if($val['facility'] != ""){
              $exp = explode(",",$val['facility']);
              foreach($exp as $aa){
                 $facility .= $facArr[$aa];
              }
          }
          $html .= '<div class="list-room">
                        <div class="img-review">
                            <figure><img src="'.$thumbgenerator.$val['thumbnail'].$xSize.'" alt="ภาพประกอบ'.$val['title'].'"></figure>
                            <div class="carousel">
                            <div class="list-img">
                                    <figure><img class="active" src="'.$thumbgenerator.$val['thumbnail'].$xSize.'" data-src="'.ROOT_URL.$val['thumbnail'].'" alt="ภาพประกอบ'.$val['title'].'"></figure>
                                    '.$imgArr[$val['id']].'
                                </div>
                            </div>
                        </div>
                        <div class="detail-room">
                            <div class="title-room">
                                <h2>'.$val['title'].'</h2>
                            </div>
                            <div class="facilities">
                                <p>'.$val['description'].'</p>
                            </div>
                            <div class="inroom">
                                '.$facility.'
                            </div>
                        </div>
                    </div>';
          }
      } 
      return $html;
    }

    public function get_content_by_id($id){
      $sql ="SELECT title,description,thumbnail,content FROM post WHERE id = $id ";
      $result = $this->fetchObject($sql,[]);
      return $result;
    }


    public function get_contact_website(){
        $sql ="SELECT * FROM contact_sel";
        $result= $this->fetchObject($sql,[]);
        return $result;
    }



}

<?php
// require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/FrontEnd.php';

/**
 * pagination($table, $where)               นับจำนวนแถวโดยกำหนด where ของตารางที่ต้องการ
 * pagination_multi_lang($table, $where)    นับจำนวนแถวโดยกำหนด where ของตารางที่ต้องการ รองรับหลายภาษา
 * get_advertise($fiter_position = '')      ฟังก์ชั่นดึงข้อมูลแบนเนอร์
 * get_category_by_parent_id($id)           ฟังก์ชั่นดึงข้อมูล category ด้วย parent id
 *
 * translateQuery($result, $defaulColumtId = 'id')      ฟังก์ชั่นแปลงข้อมูล Post ให้เป็นข้อมูลที่ใช้หลายภาษา
 * get_nav_top_menu() , buildMenu($array, &$html = '')  ฟังก์ชั่นสร้าง Top เมนู
 *
 */

/*
รายละเอียดของแต่ละฟังก์ชั่น

get_content_by_search($getpost)   ค้นหาข้อมูลในตาราง post

 */

class Helper extends FrontEnd
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

    public function pagination($table, $where)
    {
        $sql = "SELECT count(*) FROM $table WHERE $where";
        $result = $this->runQuery($sql);
        $result->execute();
        $number_of_rows = $result->fetchColumn();
        return $number_of_rows;
    }

    public function pagination_multi_lang($table, $where)
    {
        $sql = "SELECT COUNT(*) FROM " . $table . " WHERE defaults = 'yes' AND display = 'yes' " . $where;
        $result = $this->query($sql);
        return $result[0]['COUNT(*)'];
    }

    /**
     * @get_advertise ฟังก์ชั่นดึงรายละเอียดแบนเนอร์
     * $fiter_position พารามิเตอร์ position
     * return array
     *
     */
    public function get_advertise($fiter_position = '')
    {
        if ($fiter_position != '') {
            $whereCondition = " AND ad_position='{$fiter_position}' ";
        }
        $sql = "SELECT * FROM ads WHERE ad_display = 'yes' {$whereCondition}
                ORDER BY FIELD(ad_position,'pin')ASC ,ad_position ASC,FIELD(ad_priority,'0')ASC ,ad_priority ASC,ad_id DESC ,FIELD(defaults,'yes') DESC";
        $result = $this->query($sql);
        $advertise_all = array();
        $advertiseList = array();
        if (!empty($result)) {
            $Id_follow = "";
            $langActive = $_SESSION['language'];
            foreach ($result as $advertise) {

                $Id_current = $advertise['ad_id'];

                // จดจำ id ของ ads ว่ายังเป็น id เดียวกันอยู่หรือไม่เพราะจะมี id เดียวกันแต่ละคนละภาษา
                if ($Id_follow == "") {
                    $Id_follow = $Id_current;
                }
                // เก็บโพสต์ default เอาไว้
                if ($advertise['defaults'] == 'yes') {
                    $advertise_all[$Id_current] = $advertise;
                }
                //เก็บโพสต์ที่เป็นภาษาปัจจุบัน
                if ($advertise['language'] == $langActive) {
                    $advertise_all[$Id_current] = $advertise;
                }

                //เก็บภาษาที่โพสต์ได้ถูกสร้างขึ้น ตาม position
                if ($Id_follow != $advertise['ad_id']) {
                    $position = $advertise_all[$Id_follow]['ad_position'];
                    $advertiseList[$position][] = $advertise_all[$Id_follow];
                    $Id_follow = $Id_current;
                }

                //ตรวจสอบ id สุดท้าย แล้วใส่ค่าเข้าไป
                if (!next($result)) {
                    $position = $advertise_all[$Id_current]['ad_position'];
                    $advertiseList[$position][] = $advertise_all[$Id_current];
                }
            }
        }
        return $advertiseList;
    }

    //แก้ไข loop ให้น้อยลง ปรับให้รองรับ 3 submenu
    public function buildMenu($array, &$html = '')
    {
        foreach ($array as $item) {
            $html .= '<li class="navbar-item' . $level . '"><a href="' . SITE_URL . $item['url'] . '">' . $item['cate_name'] . "</a>";
            if (isset($item['submenu'])) {
                $html .= '<ul class="navbar-menu">';
                $this->buildMenu($item['submenu'], $html);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }

    public function get_nav_top_menu()
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);
        $sss = explode('?', $url[1]);
        $slug = urldecode($sss[0]);
        $site_url = SITE_URL;

        $sql = "SELECT * FROM category
                WHERE (defaults = 'yes' OR language = '" . $_SESSION['language'] . "')
                        AND display =  'yes'
                        AND menu =  'yes'
                        AND cate_name !=  'Uncategorized'
                        AND cate_id !=  1
                        ORDER BY level,parent_id ASC, FIELD(priority,'0')ASC , priority ASC,cate_id ASC, FIELD( defaults,  'yes')DESC ";

        $result = $this->query($sql);
        $cateList = $this->translateQuery($result, 'cate_id');
        //แบ่งกลุ่มเมนู
        $cateMenu = array();
        foreach ($cateList as $cate) {
            $parentId = $cate['parent_id'];
            $cateId = $cate['cate_id'];
            if ($parentId != 0) {
                //เพิ่ม subment เข้าไปที่ parent
                $cateList[$parentId]['submenu'][$cateId] = $cateList[$cateId];
                //ลบ index ที่เก็บข้อมูลแล้วออก
                unset($cateList[$cate['cate_id']]);
            }
        }
        $html = '<ul class="navbar-menu">';
        //อาศัย php recusive โยนค่าเพื่อวนสร้างเมนูจนครบ
        $html .= $this->buildMenu($cateList);
        $html .= '</ul>';
        return $html;
    }

    /* @translateQuery   ฟังก์ชั่งนี้ใช้เพื่อนำค่าจากตารางโพสต์ แล้วจัดเรียงข้อมูลให้เป็นภาษาปัจุบัน
    @result  ค่าที่ได้การการดึงข้อมุลในฐานข้อมุลด้วยคำสั่ง$this->query($sql); ต้องเปลี่ยนชื่อคอมลัมให้เป็น id ในคำนั่ง sql ด้วย
     */
    public function translateQuery($result, $defaulColumtId = 'id')
    {
        $post_all = array();
        if (!empty($result)) {
            $langActive = $_SESSION['language'];
            foreach ($result as $post) {
                $post_id = $post[$defaulColumtId];
                /* เก็บโพสต์ default เอาไว้ */
                if ($post['defaults'] == 'yes') {
                    $post_all[$post_id] = $post;
                }
                /* เก็บโพสต์ที่เป็นภาษาปัจจุบัน โดยต้องมีในค่าภาษาที่ระบบได้เพิ่มเอาไว้ */
                if ($post['language'] == $langActive) {
                    $post_all[$post_id] = $post;
                }
            }
        }
        return $post_all;
    }

    
    public function get_category_by_parent_id($id)
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);
        $sss = explode('?', $url[1]);
        $slug = urldecode($sss[0]);

        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        $condition = "parent_id = '" . $id . "'";

        $sql = "SELECT * FROM category WHERE " . $condition . " AND display =  'yes' ORDER BY level,parent_id ASC, FIELD(priority,'0')ASC , priority ASC,cate_id ASC, FIELD( defaults,  'yes')DESC ";
        $res = $this->query($sql);

        $category = array();
        $ret = array();
        $i = 0;

        foreach ($res as $a) {
            if ($a['defaults'] == 'yes') {
                $category[$i] = $a;
            }
            if ($a['language'] == $_SESSION['language']) {
                $category[$i] = $a;
            }
            $i++;
        }

        $parent = array();
        $return = array();

        foreach ($category as $a) {
            if (!in_array($a['parent_id'], $parent)) {
                array_push($parent, $a['parent_id']);
                $return[$a['parent_id']] = array();
                $return[$a['parent_id']][$a['cate_id']] = $a;
            } else {
                $return[$a['parent_id']][$a['cate_id']] = $a;
            }

        }
        $count = count($return) - 1;
        for ($i = $count; $i >= 0; $i--) {
            foreach ($return[$parent[$i]] as $b => $c) {
                if ($slug == $c['url']) {
                    $act = 'active';
                } else {
                    $act = '';
                }

                if (isset($ret[$c['cate_id']])) {
                    $ret[$c['parent_id']] .= '<li class="' . $c['language'] . ' ' . $act . '"><a href="' . $site_url . $c['url'] . '">' . $c['cate_name'] . '</a>';
                    $ret[$c['parent_id']] .= '<ul>' . $ret[$c['cate_id']] . '</ul>';
                    $ret[$c['parent_id']] .= '</li>';
                } else {
                    $ret[$c['parent_id']] .= '<li class="' . $c['language'] . ' ' . $act . '"><a href="' . $site_url . $c['url'] . '">' . $c['cate_name'] . '</a></li>';
                }
            }
        }
        return $return;
    }

    public function get_category_by_id($id)
    {
        $lan_arr = $this->get_language_array();
        // $id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT * FROM category WHERE cate_id = '" . $id . "' AND display =  'yes' ORDER BY FIELD( defaults,  'yes')DESC ";
        $result = $this->query($sql);

        $category = array();
        $ret = array();

        if ($result != false) {
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $category[$a['cate_id']]['defaults'] = $a;
                }
                $category[$a['cate_id']][$a['language']] = $a;
            }
        }

        foreach ($category as $a) {
            foreach ($a as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
                        $lang_info .= ',' . $c['language'];
                    }
                }

                if ($b == 'defaults') {
                    $ret = $c;
                }

                if ($b == $_SESSION['language']) {
                    $ret = $c;
                }

            }
            $ret['lang_info'] = $lang_info;
            $lang_info = '';
        }
        return $ret;
    }

    public function get_category($search)
    {
        $lan_arr = $this->get_language_array();
        $search = filter_var($search, FILTER_SANITIZE_MAGIC_QUOTES);
        if (isset($search)) {
            $sql = "SELECT * FROM category INNER JOIN (SELECT cate_id as id FROM category WHERE cate_name LIKE '%" . $search . "%') c ON c.id = cate_id ORDER BY parent_id,priority, cate_id, FIELD( defaults,  'yes')DESC ";
        } else {
            $sql = "SELECT * FROM category ORDER BY parent_id,priority, cate_id, FIELD( defaults,  'yes')DESC ";
        }
        $result = $this->query($sql);

        $category = array();
        $ret = array();

        if ($result != false) {
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $category[$a['cate_id']]['defaults'] = $a;
                }
                $category[$a['cate_id']][$a['language']] = $a;
            }
        }

        foreach ($category as $a) {
            foreach ($a as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
                        $lang_info .= ',' . $c['language'];
                    }
                }

                if ($b == 'defaults') {
                    $ret[$c['cate_id']] = $c;
                }

                if ($b == $_SESSION['language']) {
                    $ret[$c['cate_id']] = $c;
                }
            }
            $ret[$c['cate_id']]['lang_info'] = $lang_info;
            $lang_info = '';
        }
        return $ret;
    }

    public function get_author()
    {

        $sql = "SELECT * FROM user";
        $result = $this->query($sql);

        if ($result) {
            foreach ($result as $key) {
                $content[$key['member_id']] = $key;
            }
        } else {
            $content[0] = 'no_result';
        }
        return $content;
    }

    public function get_content_by_search($getpost)
    {
        #game
        $pagi = filter_var($getpost['pagi'], FILTER_SANITIZE_MAGIC_QUOTES);
        $amount = filter_var($getpost['amount'], FILTER_SANITIZE_NUMBER_INT);
        $slug = filter_var($getpost['slug'], FILTER_SANITIZE_MAGIC_QUOTES);
        $search = $getpost['key_value']['search'];
        $condition .= "post.id LIKE '%" . $search . "%' OR post.title LIKE '%" . $search . "%' OR post.description LIKE '%" . $search . "%' ";

        if ($pagi == '' || $pagi <= 1) {
            $lim = "0," . $amount;
        } else {
            $begin = ((($pagi - 1) * $amount));
            $lim = $begin . ',' . ($amount);
        }

        if ($_REQUEST['sort'] == '') {

            $sql = "SELECT * from post inner join(SELECT id FROM post WHERE (" . $condition . ") AND display =  'yes' AND (date_display < '" . date('Y-m-d') . "' OR date_display = '0000-00-00 00:00:00') group by id  ORDER BY field(pin, 'yes') DESC,date_created DESC ,id DESC, FIELD(defaults,'yes') DESC LIMIT " . $lim . ")pos on pos.id = post.id ORDER BY field(post.pin, 'yes') DESC,post.date_created DESC ,post.id DESC, FIELD(post.defaults,'yes') DESC ";
        } else {
            $sql = "SELECT * from post inner join(SELECT id FROM post WHERE (" . $condition . ") AND display =  'yes' AND (date_display < '" . date('Y-m-d H:i:s') . "' OR date_display = '0000-00-00 00:00:00') group by id  ORDER BY " . $_REQUEST['sort'] . " " . $_REQUEST['order'] . ",date_created DESC ,id DESC, FIELD(defaults,'yes') DESC LIMIT " . $lim . ")pos on pos.id = post.id ORDER BY post." . $_REQUEST['sort'] . " " . $_REQUEST['order'] . ",post.date_created DESC ,post.id DESC, FIELD(post.defaults,'yes') DESC ";
        }
        $res = $this->query($sql);
        $realsql = $sql;
        if ($res != false) {
            foreach ($res as $a) {
                if ($a['defaults'] == 'yes') {
                    $return[$a['id']] = $a;
                }
                if ($a['language'] == $_SESSION['language']) {
                    $return[$a['id']] = $a;
                }

                if ($pagi == '' || $pagi <= 1) {
                    $lim_img = "0,15";
                } else {
                    $begin = ((($pagi - 1) * 15));
                    $lim_img = $begin . ',15';
                }
                $sql = "SELECT * FROM post_image WHERE post_id = '" . $a['id'] . "' ORDER BY position LIMIT " . $lim_img . "";
                $img = $this->query($sql);
                if ($img != false) {
                    foreach ($img as $b) {
                        $return[$a['id']]['images'][$b['position']] = $b;
                    }
                    $sql = "SELECT  count(*) from post_image WHERE post_id = '" . $a['id'] . "'";
                    $cnt_img = $this->runQuery($sql);
                    $cnt_img->execute();
                    $return[$a['id']]['total_img'] = $cnt_img->fetchColumn();
                }
            }

        } else {
            $return[0] = 'no_result';
            $return[1] = $sql;
        }
        return $return;
    }

    public function get_url_category_for_search()
    {
        $sql = "SELECT * FROM category ORDER BY FIELD(defaults,'yes') DESC";
        $ret = $this->query($sql);
        foreach ($ret as $a) {
            if ($a['defaults'] == 'yes') {
                $return[$a['cate_id']] = $a['url'];
            }
            if ($a['language'] == $_SESSION['language']) {
                $return[$a['cate_id']] = $a['url'];
            }
        }
        return $return;

    }

    public function get_relate_content_by_cate_id($getpost)
    {
        #game
        $pagi = filter_var($getpost['pagi'], FILTER_SANITIZE_MAGIC_QUOTES);
        $amount = filter_var($getpost['amount'], FILTER_SANITIZE_NUMBER_INT);
        $slug = filter_var($getpost['slug'], FILTER_SANITIZE_MAGIC_QUOTES);
        $cate = $getpost['key_value']['cate_id'];
        $condition .= "post.category LIKE '%," . $cate . ",%' AND id NOT LIKE '" . $getpost['key_value']['this_id'] . "' ";

        if ($pagi == '' || $pagi <= 1) {
            $lim = "0," . $amount;
        } else {
            $begin = ((($pagi - 1) * $amount));
            $lim = $begin . ',' . ($amount);
        }

        if ($_REQUEST['sort'] == '') {
            $sql = "SELECT * from post inner join(SELECT  id FROM post WHERE (" . $condition . ") AND display =  'yes' AND (date_display < '" . date('Y-m-d') . "' OR date_display = '0000-00-00 00:00:00') group by id  ORDER BY field(pin, 'yes') DESC,date_created DESC ,id DESC, FIELD(defaults,'yes') DESC LIMIT " . $lim . ")pos on pos.id = post.id ORDER BY field(post.pin, 'yes') DESC,post.date_created DESC ,post.id DESC, FIELD(post.defaults,'yes') DESC ";
        } else {
            $sql = "SELECT * from post inner join(SELECT  id FROM post WHERE (" . $condition . ") AND display =  'yes' AND (date_display < '" . date('Y-m-d H:i:s') . "' OR date_display = '0000-00-00 00:00:00') group by id  ORDER BY " . $_REQUEST['sort'] . " " . $_REQUEST['order'] . ",date_created DESC ,id DESC, FIELD(defaults,'yes') DESC LIMIT " . $lim . ")pos on pos.id = post.id ORDER BY post." . $_REQUEST['sort'] . " " . $_REQUEST['order'] . ",post.date_created DESC ,post.id DESC, FIELD(post.defaults,'yes') DESC ";
        }
        //echo $sql;
        $res = $this->query($sql);

        if ($res != false) {
            foreach ($res as $a) {
                if ($a['defaults'] == 'yes') {
                    $return[$a['id']] = $a;
                }
                if ($a['language'] == $_SESSION['language']) {
                    $return[$a['id']] = $a;
                }

                if ($pagi == '' || $pagi <= 1) {
                    $lim_img = "0,15";
                } else {
                    $begin = ((($pagi - 1) * 15));
                    $lim_img = $begin . ',15';
                }
                $sql = "SELECT * FROM post_image WHERE post_id = '" . $a['id'] . "' ORDER BY position LIMIT " . $lim_img . "";
                $img = $this->query($sql);
                if ($img != false) {
                    foreach ($img as $b) {
                        $return[$a['id']]['images'][$b['position']] = $b;
                    }
                    $sql = "SELECT  count(*) from post_image WHERE post_id = '" . $a['id'] . "'";
                    $cnt_img = $this->runQuery($sql);
                    $cnt_img->execute();
                    $return[$a['id']]['total_img'] = $cnt_img->fetchColumn();
                }
            }

            $sql = "SELECT * FROM category WHERE cate_id = '" . $cate . "' ORDER BY FIELD(defaults,'yes') DESC";
            $ret = $this->query($sql);
            foreach ($ret as $a) {
                if ($a['defaults'] == 'yes') {
                    $return['title'] = $a['title'];
                    $return['keyword'] = $a['keyword'];
                    $return['description'] = $a['description'];
                    $return['url'] = $a['url'];
                    $return['thumbnail'] = $a['thumbnail'];
                }

                if ($a['language'] == $_SESSION['language']) {
                    $return['title'] = $a['title'];
                    $return['keyword'] = $a['keyword'];
                    $return['description'] = $a['description'];
                    $return['thumbnail'] = $a['thumbnail'];
                }
            }

            $sql = "SELECT  count(*) from (select * FROM post WHERE (" . $condition . ") AND post.display =  'yes' AND(post.date_display < '" . date('Y-m-d H:i:s') . "' OR post.date_display = '0000-00-00 00:00:00') GROUP BY post.id )ps";
            $cnt = $this->runQuery($sql);
            $cnt->execute();
            $return['total'] = $cnt->fetchColumn();

        } else {
            $return[0] = 'no_result';
        }
        return $return;
    }

    public function get_name_by_id($table, $field, $key, $id)
    {
        $result = '';
        $sql = "SELECT * FROM " . $table . " WHERE " . $key . " =  '" . $id . "' ORDER BY FIELD(defaults,'yes') DESC";
        // echo $sql;
        $res = $this->query($sql);
        if ($res != false) {
            foreach ($res as $b) {
                if ($b['defaults'] == 'yes') {
                    $result = $b[$field];
                }
                if ($b['language'] == $_SESSION['language']) {
                    $result = $b[$field];
                }

            }
            #game แก้ปัญหา slug ต่างภาษา เพราะหา ภาษา ในฟิล language ไม่เจอ
            if ($result == null || !$result || $result == false) {
                $result = $b[$field];
            }
        }
        //print_r($result);
        return $result;
    }

    /**
     * ฟังก์ชึ่นดึงข้อมูลแผนที่
     */
    public function get_map()
    {
        $sql = "SELECT * FROM map_setting";
        $res = $this->query($sql);
        return $res;
    }

    public function check_social_id($id, $type)
    {
        $id = filter_var($id, FILTER_SANITIZE_MAGIC_QUOTES);
        $type = filter_var($type, FILTER_SANITIZE_MAGIC_QUOTES);
        $sql = " SELECT *
                 FROM member
                 WHERE social_id = '" . $id . "'
                 AND social_type = '" . $type . "'";
        $result = $this->query($sql);
        return $result;
    }

    public function check_email($email)
    {

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $check_email = false;
        $sql = " SELECT COUNT(*) AS 'count' FROM user WHERE email = '{$email}' ";
        $countEmail = $this->fetch_assoc($sql);
        if ($countEmail > 0) {
            $check_email = true;
        }
        return $check_email;
    }

    public function check_email_letter($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sql = " SELECT *
                 FROM email_letter
                 WHERE e_mail = '" . $email . "'";
        $result = $this->query($sql);
        return $result;
    }

    public function check_favorite($member, $product)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $product = filter_var($product, FILTER_SANITIZE_MAGIC_QUOTES);
        $sql = " SELECT *
                 FROM product_favorite
                 WHERE member_id = '" . $member . "'
                 AND product_id = '" . $product . "'";
        $result = $this->query($sql);
        return $result;
    }

    public function video_id_from_url($url)
    {
        if (substr($url, -1) == '/') {
            $url = rtrim($url, '/');
        }
        $pattern =
            '%^# Match any youtube URL
            (?:https?://)?
            (?:www\.)?
            (?:
              youtu\.be/
            | youtube\.com
              (?:
                /embed/
              | /v/
              | /watch\?v=
              )
            )
            ([\w-]{10,12})
            $%x'
        ;
        $result = preg_match($pattern, $url, $matches);
        if ($result) {
            return $matches[1];
        }

        $tmp = explode('/', $url);
        if (strtolower($tmp[count($tmp) - 2] == 'videos')) {
            return $tmp[count($tmp) - 1];
        }
        parse_str(parse_url($url)['query'], $query);
        if (!empty($query['v'])) {
            return $query['v'];
        }
    }

    public function DateThai($strDate, $type = false,$yearth)
    { //type: false = short month, true = long month

        if ($_SESSION['language'] == 'TH' || $_SESSION['language'] == "") {
            # code...
            // $strYear = date("Y", strtotime($strDate)) + 543;
            $strYear = !empty($yearth)?date("Y", strtotime($strDate)) + 543:date("Y", strtotime($strDate));
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            if ($type) {
                $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
            }

        } elseif ($_SESSION['language'] == 'LA') {
            # code...
            $strYear = date("Y", strtotime($strDate));
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "ມັງກອນ", "ກຸມພາ", "ມີນາ", "ເມສາ", "ພຶດສະພາ", "ມິຖຸນາ", "ກໍລະກົດ", "ສິງຫາ", "ກັນຍາ", "ຕຸລາ", "ພະຈິກ", "ທັນວາ");

        } else {
            $strYear = date("Y", strtotime($strDate));
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        }
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    public function MonthThai($strDate, $type = false,$yearth)
    { //type: false = short month, true = long month

        if ($_SESSION['language'] == 'TH' || $_SESSION['language'] == "") {
            # code...
            // $strYear = date("Y", strtotime($strDate)) + 543;
            $strYear = !empty($yearth)?date("Y", strtotime($strDate)) + 543:date("Y", strtotime($strDate));
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            if ($type) {
                $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
            }

        } elseif ($_SESSION['language'] == 'LA') {
            # code...
            $strYear = date("Y", strtotime($strDate));
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "ມັງກອນ", "ກຸມພາ", "ມີນາ", "ເມສາ", "ພຶດສະພາ", "ມິຖຸນາ", "ກໍລະກົດ", "ສິງຫາ", "ກັນຍາ", "ຕຸລາ", "ພະຈິກ", "ທັນວາ");

        } else {
            $strYear = date("Y", strtotime($strDate));
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        }
        $strMonthThai = $strMonthCut[$strMonth];
        // return "$strDay $strMonthThai $strYear";
        return "$strMonthThai $strYear";
    }

    public function get_detail_by_post_id($getpost)
    {

        $post_id = filter_var($getpost['key_value']['post_id'], FILTER_SANITIZE_MAGIC_QUOTES);
        $condition = "id ='" . $post_id . "'";
        //print_r($post_id);

        $table = "post";
        $set = "post_view = post_view+1";
        $where = "id = '" . $post_id . "'";
        $result = $this->update($table, $set, $where);

        $sql = "SELECT  * FROM post WHERE (" . $condition . ") AND display =  'yes' AND(date_display < '" . date('Y-m-d H:i:s') . "' OR date_display = '0000-00-00 00:00:00') ORDER BY field(pin, 'yes') DESC,date_created DESC ,post.id DESC, FIELD(defaults,'yes') DESC";
        $res = $this->query($sql);
        if ($res != false) {
            foreach ($res as $a) {
                if ($a['defaults'] == 'yes') {
                    $return[$a['id']] = $a;
                }
                if ($a['language'] == $_SESSION['language']) {
                    $return[$a['id']] = $a;
                }

                // $return['title'] = $return[$a['id']]['title'];
                // $return['keyword'] = $return[$a['id']]['keyword'];
                // $return['description'] = $return[$a['id']]['description'];
                // $return['url'] = $return[$a['id']]['slug'];
                // $return['thumbnail'] = $return[$a['id']]['thumbnail'];
                // $return['freetag'] = $return[$a['id']]['freetag'];

                $return['seo'] = array(
                    'title' => $return[$a['id']]['title'],
                    'keyword' => $return[$a['id']]['keyword'],
                    'description' => $return[$a['id']]['description'],
                    'url' => $return[$a['id']]['slug'],
                    'thumbnail' => $return[$a['id']]['thumbnail'],
                    'freetag' => $return[$a['id']]['freetag'],
                );
            }
            $sql = "SELECT * FROM post_image WHERE post_id = '" . $post_id . "' ORDER BY position";
            $img = $this->query($sql);
            if ($img != false) {
                foreach ($img as $b) {
                    $return['images'][$b['position']] = $b;
                }
            }
        } else {
            $return = $sql;
        }
        return $return;
    }

    public function get_detail_by_post_id_and_post_cate_id_game($getpost)
    {
        #game
        $post_id = filter_var($getpost['key_value']['post_id'], FILTER_SANITIZE_MAGIC_QUOTES);
        $cate_id = filter_var($getpost['key_value']['post_cate_id'], FILTER_SANITIZE_MAGIC_QUOTES);
        $condition = "id ='" . $post_id . "' AND category LIKE '%," . $cate_id . ",%'";
        //print_r($post_id);

        $sql = "SELECT  * FROM post WHERE (" . $condition . ") AND display =  'yes' AND(date_display < '" . date('Y-m-d H:i:s') . "' OR date_display = '0000-00-00 00:00:00') ORDER BY field(pin, 'yes') DESC,date_created DESC ,post.id DESC, FIELD(defaults,'yes') DESC";
        $res = $this->query($sql);
        if ($res != false) {
            $table = "post";
            $set = "post_view = post_view+1";
            $where = "id = '" . $post_id . "'";
            $result = $this->update($table, $set, $where);
            foreach ($res as $a) {
                if ($a['defaults'] == 'yes') {
                    $return[$a['id']] = $a;
                }
                if ($a['language'] == $_SESSION['language']) {
                    $return[$a['id']] = $a;
                }
                $return['title'] = $return[$a['id']]['title'];
                $return['keyword'] = $return[$a['id']]['keyword'];
                $return['description'] = $return[$a['id']]['description'];
                $return['url'] = $return[$a['id']]['url'];
                $return['thumbnail'] = $return[$a['id']]['thumbnail'];
                $return['freetag'] = $return[$a['id']]['freetag'];
            }
            $sql = "SELECT * FROM post_image WHERE post_id = '" . $post_id . "' ORDER BY position";
            $img = $this->query($sql);
            if ($img != false) {
                foreach ($img as $b) {
                    $return['images'][$b['position']] = $b;
                }
            }
        } else {
            //$return = $sql;
            $return = '';
        }
        return $return;
    }

    public function update_counter_visitor()
    {

        $sql = " SELECT DATE FROM counter LIMIT 0,1";
        $ret = $this->query($sql);
        if ($ret["0"]["DATE"] != date("Y-m-d")) {

            $table = "count_all";
            $set = "CountYear = (SELECT COUNT(*) AS intYesterday FROM  counter WHERE 1 AND DATE = '" . date('Y-m-d', strtotime("-1 day")) . "')+CountYear";
            $where = "id = '1'";
            $this->update($table, $set, $where);

            $table = "daily";
            $where = "1";
            $this->delete($table, $where);

            $table = "daily";
            $field = "DATE,NUM";
            $value = "'" . date('Y-m-d', strtotime("-1 day")) . "', (SELECT COUNT(*) AS intYesterday FROM  counter WHERE 1 AND DATE = '" . date('Y-m-d', strtotime("-1 day")) . "')";
            $this->insert($table, $field, $value);

            $table = "counter";
            $where = "DATE != '" . date("Y-m-d") . "'";
            $this->delete($table, $where);

        }

        $table = "counter";
        $field = "ID,DATE,IP";
        $value = "'" . date("YmdH") . "','" . date("Y-m-d") . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        $this->insert($table, $field, $value);

    }

    public function user_online($userID, $sessionID, $rejectTime)
    {

        $userID = filter_var($userID, FILTER_SANITIZE_MAGIC_QUOTES);
        $sessionID = filter_var($sessionID, FILTER_SANITIZE_MAGIC_QUOTES);
        // $rejectTime = filter_var($rejectTime,FILTER_SANITIZE_MAGIC_QUOTES);

        $sql = "SELECT count(*) FROM online WHERE SID='" . $sessionID . "'";
        $result = $this->runQuery($sql);
        $result->execute();
        $count = $result->fetchColumn();

        if ($count == "0") {

            $table = "online";
            $field = "SID,UserID,OnlineLastTime";
            $value = "'" . $sessionID . "','" . $userID . "','" . date("Y-m-d H:i:s") . "'";
            $this->insert($table, $field, $value);

        } else {

            $table = "online";
            $set = "OnlineLastTime = '" . date("Y-m-d H:i:s") . "'";
            $where = "SID = '" . $sessionID . "'";
            $this->update($table, $set, $where);
        }

        $table = "online";
        $where = "DATE_ADD(OnlineLastTime, INTERVAL " . $rejectTime . " MINUTE) <= '" . date("Y-m-d H:i:s") . "'";
        $this->delete($table, $where);

    }

    public function get_counter_visitor()
    {

        $sql = " SELECT COUNT(DATE) AS CounterToday FROM counter WHERE DATE = '" . date("Y-m-d") . "' ";
        $res = $this->fetch_assoc($sql);
        $output["CounterToday"] = $res;

        $sql = " SELECT NUM FROM daily WHERE DATE = '" . date('Y-m-d', strtotime("-1 day")) . "' ";
        $res = $this->fetch_assoc($sql);
        $output["CountYesterday"] = $res;

        $sql = " SELECT CountYear FROM count_all ";
        $res = $this->fetch_assoc($sql);
        $output["CountYear"] = $res;

        $sql = "SELECT COUNT(SID) FROM online";
        $result = $this->runQuery($sql);
        $result->execute();
        $output["UserOnline"] = $result->fetchColumn();

        return $output;
    }

    public function upload_images($new_folder)
    {
        $tmp_name = $_FILES["images"]["tmp_name"];
        $name = time() . "_" . $_FILES["images"]["name"];

        $oldmask = umask(0);
        if (!file_exists($new_folder)) {
            @mkdir($new_folder, 0777, true);
        }
        umask($oldmask);

        move_uploaded_file($tmp_name, $new_folder . $name);
        $images = 'upload/' . date('Y') . '/' . date('m') . '/' . $name;
        return $images;
    }

   

    public function uploadImages($path)
    {

        $oldmask = umask(0);
        if (!file_exists($path)) {
            @mkdir($path, 0777, true);
        }
        umask($oldmask);

        $images = array();
        $totalFile = count($_FILES['images']['name']);

        for ($i = 0; $i < $totalFile; $i++) {
            $handle = new Upload(
                array(
                    'name' => $_FILES['images']['name'][$i],
                    'type' => $_FILES['images']['type'][$i],
                    'tmp_name' => $_FILES['images']['tmp_name'][$i],
                    'error' => $_FILES['images']['error'][$i],
                    'size' => $_FILES['images']['size'][$i],
                )
            );

            if ($handle->uploaded) {
                $newname = uniqid() . uniqid() . $this->randomString(5); // . microtime(true)
                $ext = strchr($_FILES['images']['name'][$i], ".");

                $handle->file_new_name_body = $newname;
                $handle->Process($path);
                if ($handle->processed) {
                    $images[$i] = $newname . strtolower($ext);
                    $handle->clean();
                } else {
                    //echo 'error : ' . $handle->error;
                }
            }
        }
        return $images;
    }

    
    //ดึงข้อมูล Logo เว็บไซต์
    public function get_image_logo()
    {
        $sql = "SELECT * FROM ads WHERE (defaults = 'yes' OR language='" . $_SESSION['language'] . "' ) AND ad_position = 'logo_web' AND ad_display = 'yes'";
        $res = $this->query($sql);
        return current($this->translateQuery($res, 'ad_id'));
    }
 
}

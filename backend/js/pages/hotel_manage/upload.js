
 
  //ใส่ event เมื่อมีการเปลี่ยนรูป
  $(".page_hotel_manager").on("change",".room-product #edit-images-thumbnail", function (event) {
    files = event.target.files;
    var data = new FormData();
    data.delete("images[]");
    $.each(files, function (key, value) {
      data.append("images[]", ("images" + (key + 1), value));
    });
 
    data.append('action', 'uploadthumbnail');
    data.append('id', $(this).data('id'));
    $.ajax({
      url: site_url+ "ajax/ajax.hotel_manage.php",
      type: "POST",
      data: data,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false, 
      success: function (response) { 
        $(".room-product #edit-images-thumbnail-hidden").val(response['url']);
        $(".img_thumbnail .blog-preview-edit").html(response['image']);
      }  
    });
});


$(".page_hotel_manager").on("change",".room-product #edit-more-images",function (event) {
   
    files = event.target.files;
    var data = new FormData();
    data.delete("images[]");
    $.each(files, function (key, value) {
      data.append("images[]", ("images" + (key + 1), value));
    });

    data.append('action', 'uploadmoreimgproduct');
    data.append('id', $(this).data('id'));
    $.ajax({
      url: site_url+ "ajax/ajax.hotel_manage.php",
      type: "POST",
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function (event) {
        $('#prog-edit').progressbar({ value: 0 });
        $('#overlay-edit-more-img').css('display', 'block');
        // console.log(event);
      },
      progress: function (e) {
        if (e.lengthComputable) {
          var pct = (e.loaded / e.total) * 100;
          // console.log(pct);
          $('#prog-edit')
            .progressbar('option', 'value', pct)
            .children('.ui-progressbar-value')
            .html(pct.toPrecision(3) + '%');
        } else {
          // console.warn('Content Length not reported!');
        }
      },
      success: function (msg) {
        var obj = jQuery.parseJSON(msg),
          img_list = '';
        //not fix 
        for (i = 0; i < obj.length; i++) {
          img_list += '\
          <div class="blog-show-image" data-id="'+ obj[i].image_id + '">\
            <div class="iconimg id_imgmore-edit" id="img-delete" data-id="'+ obj[i].image_id + '" data-name="' + obj[i].image_link + '">\
              <i class="fa fa-times" alt="delete"></i>\
            </div>\
            <div id="image-preview">\
              <div class="col-img-preview">\
                <img class="preview-img" src="'+ root_url + obj[i].image_link + '">\
              </div>\
            </div>\
          </div>';
        }
        $("#show-img-more").append(img_list);
      },
      complete: function () {
        $('#prog-edit').progressbar({ value: 0 });
        $('#overlay-edit-more-img').css('display', 'none');
      }
    });
  });


$(".page_hotel_manager").on('click','.room-product #img-delete', function(){ 


  let param = {
    action: "delete_images_by_id",
    id: $(this).data('id')
  }
  $.ajax({
    url: url_ajax_request + "ajax/ajax.hotel_manage.php",
    type: 'POST',
    dataType: 'json',
    data: param,
    success: function(response){
      console.log(response)
      if(response['message'] == "OK"){
        $(".page_hotel_manager .room-product #show-img-more .blog-show-image[data-id='"+param.id+"']").remove();
      }
    },
    error:function(error){
      console.log('error')
      }
  })

});
 
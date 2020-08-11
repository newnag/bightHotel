<!-- Modal Add Content -->      
<div id="modalAddContent" class="modal fade blog-content blog-content-lg" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มข้อมูล</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">
          <div class="col-content col-md-3">
            <div class="box box-content-cate">
              <div class="box-header with-border">
                <h3 class="box-title">หมวดหมู่</h3>
              </div>
              <div class="box-body" id="add-blog-category-tree">
              </div>
            </div>
          </div>

          <div class="col-content col-md-9 scrollbar" id="scrollbar-add">
            <form id="form-add-content">

              <div class="col-md-12">
                <div class="form-group form-add-images">
                  <label>อัพโหลดรูปภาพ</label>
                  <div id="image-preview">
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <div class="blog-preview-add"></div>
                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add" />
                  </div>
                  <span class="help-block add-images-error">Please select images file!</span>
                  <input type="hidden" id="add-images-content-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-title">
                  <label>หัวเรื่อง</label>
                  <input type="text" class="form-control" id="add-title" name="add-title" placeholder="Title">
                  <span class="help-block add-title-error">Please fill out this field.</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-description">
                  <label>คำอธิบาย</label>
                  <input type="text" class="form-control" id="add-description" name="add-description" placeholder="Description">
                  <span class="help-block add-description-error">Please fill out this field.</span>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-price">
                  <label>ราคา</label>
                  <input type="number" class="form-control" id="add-price" name="add-price" placeholder="Price">
                  <span class="help-block add-price-error">Please fill out this field.</span>
                </div> 
              </div>

              <div class="col-md-12"> 
                <label>เส้นทาง</label>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <div class="input-group form-add-location-from">
                    <div class="input-group-addon">
                      จาก
                    </div>
                    <select class="form-control" name="add-location-from" id="add-location-from" style="width: 100%;">
                        <option id="add-location-from-0" value="0">Select Location</option>
                        <?php
                        foreach ($location as $key => $value) {
                        ?>
                          <option id="add-location-from-<?= $value['info_id'] ?>" value="<?= $value['info_id'] ?>"><?= $value['info_title'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                  </div>
                  <div class="form-add-location-from">
                    <span class="help-block add-location-from-error">Please select this field.</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <div class="input-group form-add-location-to">
                    <div class="input-group-addon">
                      ถึง
                    </div>
                    <select class="form-control" name="add-location-to" id="add-location-to" style="width: 100%;">
                        <option id="add-location-to-0" value="0">Select Location</option>
                        <?php
                        foreach ($location as $key => $value) {
                        ?>
                          <option id="add-location-to-<?= $value['info_id'] ?>" value="<?= $value['info_id'] ?>"><?= $value['info_title'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                  </div>
                  <div class="form-add-location-to">
                    <span class="help-block add-location-to-error">Please select this field.</span>
                  </div>
                </div>
              </div>

              <div class="col-md-12"> 
                <label>วันที่แสดง</label>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="add-date-display" placeholder="วันที่">
                        <input type="hidden" class="form-control pull-right" id="add-date-display-hidden">
                    </div>
                </div>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="bootstrap-timepicker">
                      <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" class="form-control timepicker" id="add-time-display" placeholder="เวลา">
                      </div>
                  </div> 
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label>แสดงผลบนเว็บไซต์</label>
                  <select class="form-control" name="add-display" id="add-display" style="width: 100%;">
                      <option id="add-display-yes" value="yes">แสดง</option>
                      <option id="add-display-no" value="no">ซ่อน</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <label>ปักหมุด</label>
                  <select class="form-control" name="add-pin" id="add-pin" style="width: 100%;">
                      <option id="add-pin-no" value="no">ไม่</option>
                      <option id="add-pin-yes" value="yes">ใช่</option>
                  </select>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <button type="submit" class="btn btn-default pull-left" id="reset-add">
          <i class="fa fa-repeat" aria-hidden="true"></i> ล้างค่า
        </button>
        <button type="submit" class="btn btn-success pull-right" id="save-add">
          <i class="fa fa-floppy-o"></i> บันทึก
        </button>
      </div>
    </div>
  </div>
</div>
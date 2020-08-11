<!-- Modal Edit Content -->      
<div id="modalEditContent" class="modal fade blog-content blog-content-lg" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> แก้ไขข้อมูล</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">
          <div class="col-content col-md-3">
            <div class="box box-content-cate">
              <div class="box-header with-border">
                <h3 class="box-title">หมวดหมู่</h3>
              </div>
              <div class="box-body" id="blog-category-tree">
              </div>
            </div>
          </div>

          <div class="col-content col-md-9 scrollbar" id="scrollbar-edit">
            <form id="form-edit-content">

              <div class="col-md-12">
                <div class="form-group form-edit-images">
                  <label>อัพโหลดรูปภาพ</label>
                  <div id="image-preview">
                    <div class="blog-preview-edit"></div>
                    <input type="file" name="imagesedit[]" class="exampleInputFile" id="edit-images-content" data-preview="blog-preview-edit" data-type="edit" />
                  </div>
                  <span class="help-block add-images-error">Please select images file!</span>
                  <input type="hidden" id="edit-images-content-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-title">
                  <label>หัวเรื่อง</label>
                  <input type="text" class="form-control" id="edit-title" name="edit-title" placeholder="Title">
                  <span class="help-block edit-title-error">Please fill out this field.</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-description">
                  <label>คำอธิบาย</label>
                  <input type="text" class="form-control" id="edit-description" name="edit-description" placeholder="Description">
                  <span class="help-block edit-description-error">Please fill out this field.</span>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-price">
                  <label>ราคา</label>
                  <input type="number" class="form-control" id="edit-price" name="edit-price" placeholder="Price">
                  <span class="help-block edit-price-error">Please fill out this field.</span>
                </div> 
              </div>

              <div class="col-md-12"> 
                <label>เส้นทาง</label>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <div class="input-group form-edit-location-from">
                    <div class="input-group-addon">
                      จาก
                    </div>
                    <select class="form-control" name="edit-location-from" id="edit-location-from" style="width: 100%;">
                        <option id="edit-location-from-0" value="0">Select Location</option>
                        <?php
                        foreach ($location as $key => $value) {
                        ?>
                          <option id="edit-location-from-<?= $value['info_id'] ?>" value="<?= $value['info_id'] ?>"><?= $value['info_title'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                  </div>
                  <div class="form-edit-location-from">
                    <span class="help-block edit-location-from-error">Please select this field.</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <div class="input-group form-edit-location-to">
                    <div class="input-group-addon">
                      ถึง
                    </div>
                    <select class="form-control" name="edit-location-to" id="edit-location-to" style="width: 100%;">
                        <option id="edit-location-to-0" value="0">Select Location</option>
                        <?php
                        foreach ($location as $key => $value) {
                        ?>
                          <option id="edit-location-to-<?= $value['info_id'] ?>" value="<?= $value['info_id'] ?>"><?= $value['info_title'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                  </div>
                  <div class="form-edit-location-to">
                    <span class="help-block edit-location-to-error">Please select this field.</span>
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
                        <input type="text" class="form-control pull-right" id="date-display" placeholder="วันที่">
                        <input type="hidden" class="form-control pull-right" id="date-display-hidden">
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
                          <input type="text" class="form-control timepicker" id="time-display" placeholder="เวลา">
                      </div>
                  </div> 
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label>แสดงผลบนเว็บไซต์</label>
                  <select class="form-control" name="edit-display" id="edit-display" style="width: 100%;">
                      <option id="edit-display-yes" value="yes">แสดง</option>
                      <option id="edit-display-no" value="no">ซ่อน</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <label>ปักหมุด</label>
                  <select class="form-control" name="edit-pin" id="edit-pin" style="width: 100%;">
                      <option id="edit-pin-no" value="no">ไม่</option>
                      <option id="edit-pin-yes" value="yes">ใช่</option>
                  </select>
                </div>
              </div>
              <input type="hidden" class="form-control" id="edit-content-id">
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <input type="hidden" id="submit-type">
        <input type="hidden" id="date-created">
        <button type="submit" class="btn btn-default pull-left" id="reset-edit">
          <i class="fa fa-repeat" aria-hidden="true"></i> ล้างค่า
        </button>
        <button type="submit" class="btn btn-success pull-right" id="save-edit">
          <i class="fa fa-floppy-o"></i> บันทึก
        </button>
      </div>
    </div>
  </div>
</div>
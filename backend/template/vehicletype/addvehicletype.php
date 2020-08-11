<!-- Modal Add Category -->      
<div id="modalAddCategory" class="modal fade blog-content blog-content-sm" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มประเภท</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">


          <div class="col-content col-md-12 scrollbar" id="scrollbar">
            <form id="form-add-category">
              <div class="col-md-12">
                <div class="form-group form-add-images">
                  <label>อัพโหลดรูปภาพ</label>
                  <div id="image-preview">
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <div class="blog-preview-add"></div>
                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-cate" data-preview="blog-preview-add" data-type="add" />
                  </div>
                  <span class="help-block add-images-error">Please select images file!</span>
                  <input type="hidden" id="add-images-cate-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-name">
                  <label>ชื่อหมวดหมู่</label>
                  <input type="text" class="form-control" id="add-name" name="add-name">
                  <span class="help-block add-name-error">Please fill out this field.</span>
                </div>
              </div>

              <input type="hidden" class="form-control" id="add-title" name="add-title">
              <input type="hidden" class="form-control" id="add-keyword" name="add-keyword">
              <input type="hidden" class="form-control" id="add-description" name="add-description">

              <div class="col-md-6">
                <div class="form-group">
                  <label>จำนวนที่นั่ง</label>
                  <input type="number" class="form-control" id="add-seats" name="add-seats" placeholder="Seats">
                </div> 
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>จำนวนประตู</label>
                  <input type="number" class="form-control" id="add-doors" name="add-doors" placeholder="Doors">
                </div> 
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>ถุงลมนิรภัย</label>
                  <input type="number" class="form-control" id="add-airbags" name="add-airbags" placeholder="Airbags">
                </div> 
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>เครื่องปรับอากาศ</label>
                  <input type="text" class="form-control" id="add-air" name="add-air" placeholder="Air Conditioner">
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-slug">
                  <label>URL</label>
                  <div class="input-group">
                    <span class="input-group-addon input-group-url"><?= ROOT_URL ?></span>
                    <input type="text" class="form-control" id="add-slug" name="add-slug" placeholder="">
                  </div>  
                  <span class="help-block add-slug-error">Please fill out this field.</span>   
                </div>
              </div>

              <div class="col-md-6" style="display: none;">  
                <div class="form-group">
                  <label>หมวดหมู่หลัก</label>
                  <select class="form-control" name="show-on-menu" id="show-on-menu" style="width: 100%;">
                    <option id="add-display-no" value="no">ไม่ใช่</option>
                    <option id="add-display-yes" value="yes">ใช่</option>
                  </select>
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

              <div class="col-md-6" style="display: none;">  
                <div class="form-group">
                  <label>ตำแหน่งในหน้าแรก</label>
                  <input type="number" class="form-control" id="add-position" name="add-position" value="0" min="0">
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label>ลำดับ</label>
                  <input type="number" class="form-control" id="add-priority" name="add-priority" value="0" min="0">
                </div>
              </div>
              
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <button type="submit" class="btn btn-default pull-left" id="reset-add-category">
          <i class="fa fa-repeat" aria-hidden="true"></i> ล้างค่า
        </button>
        <button type="submit" class="btn btn-success pull-right" id="save-add-category">
          <i class="fa fa-floppy-o"></i> บันทึก
        </button>
      </div>
    </div>
  </div>
</div>
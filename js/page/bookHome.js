// กดเพิ่มจำนวนคนผู้ใหญ่หรือเด็ก
function valuePeople(side,type){
    if(type === 'adult'){
        this.val = Number(document.querySelector('#adult').value)
    }
    else if(type === 'child'){
        this.val = Number(document.querySelector('#child').value)
    }

    

    if(side === 'l' && this.val > 0){
        this.val -= 1
    }
    else if(side === 'r' ){
        this.val += 1
    }
    document.querySelector(`#${type}`).value = this.val
}
document.querySelector('.input-box.adult .left').addEventListener('click',()=>valuePeople('l','adult'))
document.querySelector('.input-box.adult .right').addEventListener('click',()=>valuePeople('r','adult'))
document.querySelector('.input-box.child .left').addEventListener('click',()=>valuePeople('l','child'))
document.querySelector('.input-box.child .right').addEventListener('click',()=>valuePeople('r','child'))

// แสดงรีวิวรูปอัพสลิป
// document.querySelector('#slip-upload').addEventListener('change',function(e){
//     let slip = e.target.files[0]
//     var readerImg = new FileReader();
//     readerImg.onload = function(e) {
//         document.querySelector('.review>img').src = e.target.result
//         document.querySelector('.upload-slip .review').classList.add('addimg')
//     };
//     readerImg.readAsDataURL(slip)
// })

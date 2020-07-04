window.onload = ()=>{
    if(screen.width >= 1366){
        DragImg('.img-review .carousel .list-img')
    }
    if($('.room-page-zone')){
        openDialogRoom()
        closeDialogRoom()
    }
    clickImgChangeURL()
    clickToCloseDialog()
    if(screen.width < 1366){
        facilitiesIcon('.detail-room .inroom .item')
    }
    deleteListRoom()
    increaseNumberRoom()
}

function openDialogRoom(){
    const viewFull = document.querySelectorAll('.room-page-zone .gird-room .list-room .img-review .virwFull')
    viewFull.forEach(ele=>{
        ele.addEventListener('click',()=>{
            document.querySelector('.dialog-fullview').classList.add('active')
        })
    })
}

function closeDialogRoom(){
    try {
        document.querySelector('.dialog-fullview .inner-dialog .close button').addEventListener('click',()=>{
            document.querySelector('.dialog-fullview').classList.remove('active')
        })
    } catch (error) {
        console.log(error)
    }
}

function clickImgChangeURL(){
    let urlBig = document.querySelectorAll('.list-room .img-review')
    urlBig.forEach(big=>{
        let urlImg = big.querySelectorAll('.carousel .list-img figure img')
        urlImg.forEach(url=>{
            url.addEventListener('click',()=>{
                big.children[0].children[0].src = url.src
            })
        })
    })
}

function clickImgDialogChangeURL(){
    const slide = document.querySelector('.dialog-fullview .inner-dialog .image-review .carousel .list-img')
    const urlImg = slide.querySelectorAll('figure img')
    urlImg.forEach(url => {
        url.addEventListener('click',()=>{
            document.querySelector('.dialog-fullview .img-bigbox figure img').src = url.getAttribute('data-src')
        })
    });     
}

function clickToCloseDialog(){
    let element = document.querySelector('.dialog-fullview')
    element.addEventListener('click',(ev)=>{
        if(ev.target.className === 'dialog-fullview active'){
            element.classList.remove('active')
        }
    })
}

function deleteListRoom(){
    const button = document.querySelectorAll('.detial .detail-list .list-item .delete')
    button.forEach(X=>{
        X.addEventListener('click',()=>{
            X.parentElement.remove()
        })
    })
}

function increaseNumberRoom(){
    const room = document.querySelectorAll('.detail-list .list-item div.amound-room')
    room.forEach(element => {
        let txt = Number(element.querySelector('span.amound-room p').textContent)
        const left = element.querySelector('.minus')
        const right = element.querySelector('.plus')
        left.addEventListener('click',()=>{
            if(txt > 1){
                txt -= 1
                element.querySelector('span.amound-room p').textContent = txt
            }
        })
        right.addEventListener('click',()=>{
            alert('เช็คจำนวนห้องจากหลังบ้าน ถ้ามีจำนวนห้องให้กดเพิ่มได้')
            txt += 1
            element.querySelector('span.amound-room p').textContent = txt
        })
    })
}
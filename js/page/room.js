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
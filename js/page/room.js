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
    //deleteListRoom()
    //increaseNumberRoom()
    checkChild_detailList()
    selectRoomWarpToDetail()
}

$('.box-date .input-box .checkOut').flatpickr({
    minDate: new Date().fp_incr(1),
    dateFormat: "d-m-Y",
    disableMobile: "true",
})

function openDialogRoom(){
    const viewFull = document.querySelectorAll('.gird-room .list-room .img-review .virwFull')
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
                big.children[0].children[0].src = url.dataset.src
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
    try{
        let element = document.querySelector('.dialog-fullview')
        element.addEventListener('click',(ev)=>{
            if(ev.target.className === 'dialog-fullview active'){
                element.classList.remove('active')
            }
        })
    }
    catch(error){
        console.log(error)
    }
}

// function deleteListRoom(){
//     const button = document.querySelectorAll('.detial .detail-list .list-item .delete')
//     const list = document.querySelector('.detail-list')
//     button.forEach(X=>{
//         X.addEventListener('click',()=>{
//             //
//         })
//     })
// }

// function increaseNumberRoom(){
//     const room = document.querySelectorAll('.detail-list .list-item div.amound-room')
//     room.forEach(element => {
//         let txt = Number(element.querySelector('span.amound-room p').textContent)
//         const left = element.querySelector('.minus')
//         const right = element.querySelector('.plus')
//         left.addEventListener('click',()=>{
//             console.log('left')
//         })
//         right.addEventListener('click',()=>{
//             console.log('right')
//         })
//         // left.addEventListener('click',()=>{ 
//         //     if(txt > 1){
//         //         txt -= 1
//         //         element.querySelector('span.amound-room p').textContent = txt
//         //     }
//         // }) 
//         // right.addEventListener('click',()=>{
//         //     alert('เช็คจำนวนห้องจากหลังบ้าน ถ้ามีจำนวนห้องให้กดเพิ่มได้')
//         //     txt += 1
//         //     element.querySelector('span.amound-room p').textContent = txt
//         // })
//     })
// }

function checkChild_detailList(){
    //console.log('in')
    const list = document.querySelector('.detial .detail-list')
    let numList = false
    if(list){
        if(list.textContent !== ''){numList = true}
        if(numList === true){
            list.classList.remove('hide')
        }
        else{
            list.classList.add('hide')
        }
        const button = document.querySelectorAll('.btn_reserve')
        button.forEach(bt => {
            bt.addEventListener('click',()=>{
                if(list.hasChildNodes()){
                    list.classList.remove('hide')
                } 
            })
        })
    }
    else{
        console.log("not has")
    }
}

function selectRoomWarpToDetail(){
    const button = document.querySelectorAll('.btn_reserve')
    button.forEach(bt => {
        bt.addEventListener('click',()=>{
            // location.hash = '#' + "detail-order";
            const block = document.querySelector("#detail-order")
            block.scrollIntoView()
        })
    })
}
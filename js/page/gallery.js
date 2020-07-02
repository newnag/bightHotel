window.onload = ()=>{
    openBigImg()
    closeBigImg()
    clickImgUrlBig()
}

function clickImgUrlBig(){
    const img = document.querySelectorAll('.gallary-zone figure img')
    img.forEach(Img=>{
        Img.addEventListener('click',()=>{
            let url = Img.src
            document.querySelector('.showpic .bigpic figure img').src = url
        })
    })
}

function openBigImg(){
    const img = document.querySelectorAll('.gallary-zone figure img')
    img.forEach(Img=>{
        Img.addEventListener('click',()=>{
            document.querySelector('.showpic').classList.add('active')
        })
    })
}

function closeBigImg(){
    document.querySelector('.bigpic .close').addEventListener('click',()=>{
        document.querySelector('.showpic').classList.remove('active')
    })
}
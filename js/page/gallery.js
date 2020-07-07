window.onload = ()=>{
    openBigImg()
    closeBigImg()
    clickImgUrlBig()
}

function clickImgUrlBig(){
    const img = document.querySelectorAll('.gallary-zone figure img')
    console.log(img)
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

function get_more_images(){
    let amount = 15;
    let number = $(".gallary-zone figure").length / amount;
    let param = {
        action: 'get_image_gallery',
        amount,
        number
    }

    $.ajax({
        url: hostname+'api/myapi.php',
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            if(response['message'] === 'success'){
                $(".gallary-zone").append(response['images'])
                openBigImg()
                clickImgUrlBig()
                if((15 - response['amount'])  > 0){
                    $(".loadmore button").hide();
                }
            }
        },
        error: function(res){
            console.log('errr')
        }
    });

}

// function get_more_images(){
//     let formData = new FormData();
//     formData.append("action","get_image_gallery");
//     formData.append("set","me");
//     fetch('site_url+"api/myapi.php"', {
//         method: "POST",
//         body: formData 
//     }).then(function (response) {
// 	// The API call was successful!
// 	    console.log('success!', response);
//     }).catch(function (err) {
//         // There was an error
//         console.warn('Something went wrong.', err);
//     });
// }



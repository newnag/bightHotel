$('.detail-booking-zone .box-payment .right-box .date-box .dateCheck').flatpickr({
    disableMobile: "true",
    enableTime: true,
    dateFormat: "d-m-Y H:i",
})

window.onload = ()=>{
    valuePeopleBook()
}

function valuePeopleBook(){
    const val1 = document.querySelectorAll('.inputAdult')
    val1.forEach(value=>{
        const left = value.parentElement.querySelector('.left')
        const right = value.parentElement.querySelector('.right')
        let inputVal = Number(value.value)

        left.addEventListener('click',()=>{
            if(inputVal > 0){
                inputVal -= 1
            }
            value.value = inputVal
        })
        right.addEventListener('click',()=>{
            inputVal += 1
            value.value = inputVal
        })
    })

    const val2 = document.querySelectorAll('.inputChild')
    val2.forEach(value2=>{
        const left = value2.parentElement.querySelector('.left')
        const right = value2.parentElement.querySelector('.right')
        let inputVal = Number(value2.value)

        left.addEventListener('click',()=>{
            if(inputVal > 0){
                inputVal -= 1
            }
            value2.value = inputVal
        })
        right.addEventListener('click',()=>{
            inputVal += 1
            value2.value = inputVal
        })
    })
}
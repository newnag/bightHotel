window.onload = ()=>{
    deleteHistory()
}

function deleteHistory(){
    let element = document.querySelectorAll('.history-page-zone .grid-item .item .table-body .delete')
    element.forEach(ele=>{
        ele.addEventListener('click',(event)=>{
            event.preventDefault()
            ele.closest('.item').classList.add('hide')
            setTimeout(()=>{ele.closest('a').remove()},500)
        })
    })
}
function formatCurrency(value){
    value = parseInt(value.replace(/^Rp/, '').replace(/\./g, ''));
    return value.toLocaleString('id-ID');
}

function pad(s) { return (s < 10) ? '0' + s : s; }

function stringToInt(str){
   return parseInt(str.replace(/^Rp/, '').replace(/\./g, ''));
}

function changeFormatDate(id, date) {
    let new_date = new Date(date);
    $("#"+id).val([pad(new_date.getDate()), pad(new_date.getMonth()+1), new_date.getFullYear()].join('/'))
}
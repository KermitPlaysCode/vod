function update_div(div_name, data) {
    document.getElementById(div_name).innerHTML = data;
    return 0;
}

function update_nfo_viewer(theUrl, div_name) {
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.addEventListener("load", function() { update_div(div_name, xmlHttpReq.responseText); }, false);
    xmlHttpReq.open("GET", theUrl, false);
    xmlHttpReq.send(null);
   return 0;
}

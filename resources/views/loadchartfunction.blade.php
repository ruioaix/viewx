<script type="text/javascript">
function loadchart() {
    var fromh = document.getElementById('fromhour').value;
    var toh = document.getElementById('tohour').value;
    if (fromh == '') fromh = 0;
    else fromh = parseInt(fromh);
    if (toh == '') toh = 0;
    else toh = parseInt(toh);
    if (!isNaN(fromh) && !isNaN(toh) && toh >= 0 && fromh > toh) {
        document.getElementById("msg").innerHTML = "working...";
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                eval(xhttp.responseText)
            }
        };
        var url = "{{ $url }}";
        xhttp.open("GET", url.concat('/').concat(fromh).concat('-').concat(toh), true);
        xhttp.send();
    }
    else {
        document.getElementById("msg").innerHTML = "<strong> Error</strong>";
    }
}
</script>

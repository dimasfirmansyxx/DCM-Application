<?php
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=$namafile - DCM App.xls");
?>
<script>
	// var iframe = document.createElement('iframe');
 //    document.body.appendChild(iframe);
 //    iframe.style.display = 'none';
 //    iframe.onload = function() {
 //        setTimeout(function() {
 //            iframe.focus();
 //            iframe.contentWindow.print();
 //        }, 0);
 //    };
 //    iframe.src = _blobUrl;
 setTimeout(function(){
    window.print();
 },500)
</script>
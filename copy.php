
<script type="javascript">
    function copyURL() {
        console.log(window.location.href);
        return window.location.href;
    }
</script>


<?php
echo "<script type='javascript'>
        console.log(copyURL());
</script>";


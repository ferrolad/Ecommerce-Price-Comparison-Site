<?php
header("HTTP/1.0 404 Not Found");
?>

<html>

<?php
echo 'Hi, this page does not exist...  <img src="/img/logo_Giare.png" alt="logo" /><br/>';
echo 'You will get redirect back to homepage in 2 seconds';
?>

<script>
setTimeout(function() { window.location = "/";}, 2000);
</script>
</html>


<?php

if (empty($_SESSION['uuid'])) {
  $baseurl = base_url();
  header("Location:$baseurl");
  exit();
}
?>
<!DOCTYPE html>
<html class="no-js">
<?php
include_once("assets/engine/header.php");
?>
<?php
include_once("assets/engine/menu.php");
?>
<div class="content-wrapper">
    <section class="content">
        <?php
    $viewname = $data['template'];
    include($viewname . '.php');
    ?>
    </section>
</div>
<?php
include_once("assets/engine/footer.php");
?>
</body>

</html>
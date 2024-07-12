<?php
/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>
  <footer class="main-footer">
    <strong><?php echo date('03/07/2024'); ?> </strong>
    It was prepared by Team
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
<?php } ?>
<?php
if(isset($_FILES['document'])) {
  $name = time().'_'.$_FILES['document']['name'];
  move_uploaded_file($_FILES['document']['tmp_name'], "../uploads/".$name);
  header("Location: ../dashboard.php");
}

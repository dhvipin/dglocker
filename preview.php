<?php $file = $_GET['file']; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Preview</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="preview-container">
  <h2>Document Preview</h2>

  <iframe src="uploads/<?= $file ?>"></iframe>

  <div class="verified">✔ Verified</div>

  <a href="uploads/<?= $file ?>" download class="btn">Download</a>
</div>

</body>
</html>

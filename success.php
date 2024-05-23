<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Payment Successful</h1>
        <p>Thank you for your purchase. You can now download your customized book cover.</p>
        <?php if (isset($_GET['image'])): ?>
            <a href="<?php echo htmlspecialchars($_GET['image']); ?>" download>Download Cover</a>
        <?php endif; ?>
    </div>
</body>
</html>

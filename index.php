<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDP Book Cover Creator</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f0f4f7;
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .intro {
            margin-bottom: 20px;
        }
        .intro h1 {
            margin-top: 0;
        }
        .template-select {
            margin-bottom: 20px;
        }
        .template-select label {
            display: block;
            margin-bottom: 10px;
        }
        .template-select select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }
        .template-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .template-preview div {
            width: 100px;
            height: 150px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .template-preview div.selected {
            border-color: #4CAF50;
        }
        .start-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .start-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>KDP Book Cover Creator</h1>
    </div>
    <div class="container">
        <div class="intro">
            <h1>Welcome to the KDP Book Cover Creator</h1>
            <p>Design your own professional book cover with ease. Choose from a variety of templates, customize fonts, colors, and much more. Get started now!</p>
        </div>
        <form id="template-form" action="customize.php" method="get">
            <div class="template-select">
                <label for="template">Choose a Template:</label>
                <select name="template" id="template">
                    <option value="template1.json">Template 1</option>
                    <option value="template2.json">Template 2</option>
                </select>
            </div>
            <div class="template-preview">
                <div data-value="template1.json">Template 1</div>
                <div data-value="template2.json">Template 2</div>
            </div>
            <button type="submit" class="start-btn">Customize Your Cover</button>
        </form>
    </div>
    <script>
        document.querySelectorAll('.template-preview div').forEach(div => {
            div.addEventListener('click', function() {
                document.querySelectorAll('.template-preview div').forEach(i => i.classList.remove('selected'));
                div.classList.add('selected');
                document.getElementById('template').value = div.getAttribute('data-value');
            });
        });
    </script>
</body>
</html>

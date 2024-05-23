<?php
$templateFile = $_GET['template'];
$templateData = json_decode(file_get_contents("templates/$templateFile"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Your Cover</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.4.0/fabric.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Customize Your Cover</h1>
        <div class="editor">
            <canvas id="canvas" width="600" height="900"></canvas>
            <div class="controls">
                <label for="paper-width">Paper Width (inches):</label>
                <input type="number" id="paper-width" value="6" step="0.1">

                <label for="paper-height">Paper Height (inches):</label>
                <input type="number" id="paper-height" value="9" step="0.1">

                <label for="sheet-count">Number of Sheets:</label>
                <input type="number" id="sheet-count" value="100">

                <label for="font-family">Font:</label>
                <select id="font-family">
                    <option value="Arial">Arial</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Verdana">Verdana</option>
                    <option value="Comic Sans MS">Comic Sans MS</option>
                    <option value="Impact">Impact</option>
                    <option value="Tahoma">Tahoma</option>
                    <option value="Trebuchet MS">Trebuchet MS</option>
                    <option value="Lucida Sans">Lucida Sans</option>
                    <option value="Palatino">Palatino</option>
                    <option value="Garamond">Garamond</option>
                    <option value="Bookman">Bookman</option>
                    <option value="Avant Garde">Avant Garde</option>
                    <option value="Courier">Courier</option>
                    <option value="Candara">Candara</option>
                    <option value="Arial Black">Arial Black</option>
                    <option value="Gill Sans">Gill Sans</option>
                    <option value="Lucida Console">Lucida Console</option>
                    <option value="Futura">Futura</option>
                    <option value="Franklin Gothic">Franklin Gothic</option>
                    <option value="Corbel">Corbel</option>
                    <option value="Helvetica">Helvetica</option>
                    <option value="Calibri">Calibri</option>
                    <option value="Optima">Optima</option>
                </select>

                <label for="font-color">Font Color:</label>
                <input type="color" id="font-color" value="#000000">

                <input type="file" id="upload-image" accept="image/*">
                <input type="text" id="text-input" placeholder="Enter text here">
                <button id="add-text">Add Text</button>
                
                <label for="upload-font">Upload Custom Font:</label>
                <input type="file" id="upload-font" accept=".ttf,.otf">

                <button id="move-up">Move Up</button>
                <button id="move-down">Move Down</button>
                <button id="bring-front">Bring to Front</button>
                <button id="send-back">Send to Back</button>
                <button id="export">Export</button>
            </div>
        </div>
        <form action="process_payment.php" method="POST">
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="<?php echo STRIPE_PUBLISHABLE_KEY; ?>"
                data-amount="5000"
                data-name="KDP Book Cover"
                data-description="Customize and download your book cover"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-currency="usd">
            </script>
        </form>
    </div>
    <script src="assets/js/script.js"></script>
    <script>
        var canvas = new fabric.Canvas('canvas');
        
        var templateData = <?php echo json_encode($templateData); ?>;
        fabric.Image.fromURL(templateData.background, function(img) {
            img.set({ left: 0, top: 0 });
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
        });

        templateData.elements.forEach(function(element) {
            if (element.type === 'text') {
                var text = new fabric.Text(element.content, {
                    left: element.left,
                    top: element.top,
                    fontSize: element.fontSize,
                    fill: element.fill,
                    fontFamily: element.fontFamily
                });
                canvas.add(text);
            }
        });

        var watermark = new fabric.Text('WATERMARK', {
            left: canvas.width / 2,
            top: canvas.height / 2,
            fontSize: 40,
            fill: 'rgba(255,255,255,0.5)',
            angle: 45,
            originX: 'center',
            originY: 'center'
        });
        canvas.add(watermark);

        document.getElementById('add-text').addEventListener('click', function() {
            var text = new fabric.Text(document.getElementById('text-input').value, {
                left: 50,
                top: 100,
                fill: document.getElementById('font-color').value,
                fontSize: 20,
                fontFamily: document.getElementById('font-family').value
            });
            canvas.add(text);
        });

        document.getElementById('upload-image').addEventListener('change', function(e) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var imgObj = new Image();
                imgObj.src = event.target.result;
                imgObj.onload = function() {
                    var image = new fabric.Image(imgObj);
                    image.set({
                        left: 50,
                        top: 100
                    });
                    canvas.add(image);
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        });

        document.getElementById('export').addEventListener('click', function() {
            var dataURL = canvas.toDataURL('image/jpeg');
            $.ajax({
                type: "POST",
                url: 'export.php',
                data: {
                    imgBase64: dataURL
                }
            }).done(function(o) {
                console.log('saved');
            });
        });

        $('#paper-width, #paper-height').on('input', function() {
            var width = $('#paper-width').val() * 96; 
            var height = $('#paper-height').val() * 96;
            canvas.setWidth(width);
            canvas.setHeight(height);
            canvas.renderAll();
        });

        $('#move-up').click(function() {
            var activeObject = canvas.getActiveObject();
            if (activeObject) {
                activeObject.top -= 10;
                canvas.renderAll();
            }
        });

        $('#move-down').click(function() {
            var activeObject = canvas.getActiveObject();
            if (activeObject) {
                activeObject.top += 10;
                canvas.renderAll();
            }
        });

        $('#bring-front').click(function() {
            var activeObject = canvas.getActiveObject();
            if (activeObject) {
                canvas.bringToFront(activeObject);
                canvas.renderAll();
            }
        });

        $('#send-back').click(function() {
            var activeObject = canvas.getActiveObject();
            if (activeObject) {
                canvas.sendToBack(activeObject);
                canvas.renderAll();
            }
        });

        $('#upload-font').change(function(e) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var font = new FontFace('CustomFont', event.target.result);
                font.load().then(function(loadedFont) {
                    document.fonts.add(loadedFont);
                    $('#font-family').append(new Option('CustomFont', 'CustomFont'));
                });
            }
            reader.readAsArrayBuffer(e.target.files[0]);
        });
    </script>
</body>
</html>

$(document).ready(function() {
    var canvas = new fabric.Canvas('canvas');

    $('#add-text').click(function() {
        var text = new fabric.Text($('#text-input').val(), {
            left: 50,
            top: 100,
            fill: $('#font-color').val(),
            fontSize: 20,
            fontFamily: $('#font-family').val()
        });
        canvas.add(text);
    });

    $('#upload-image').change(function(e) {
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

    $('#export').click(function() {
        var dataURL = canvas.toDataURL('image/jpeg');
        $.ajax({
            type: "POST",
            url: 'export.php',
            data: {
                imgBase64: dataURL
            }
        }).done(function(o) {
            $('form').append('<input type="hidden" name="watermarkedImagePath" value="' + o + '">');
            $('form').submit();
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

    // Bring selected object to front
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
});

<?php

use app\Http\URL;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Registro de productos</title>
</head>
<body>
    <h1>Formulario de productos</h1>
    <p id="error"></p>
    <form action="" id="registro-producto">
        <div>
        <label for="codigo">Codigo</label>
        <input id="codigo" name="codigo" type="text">
        </div>
        <div>
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" type="text">
        </div>
        <div>
        <label for="bodega">Bodega</label>
        <select name="bodega" id="bodega">
            <option value="none">Seleccioné</option>
            <?php

 foreach ($data['bodegas'] as $bodega) {?>
                <option value="<?php echo $bodega->id; ?>"><?php echo $bodega->nombre; ?></option>
                <?php }?>
        </select>
        </div>
        <div>
        <label for="sucursal">Sucursal</label>
        <select name="sucursal" id="sucursales">
            <option value="">Seleccioné</option>
            <!-- agregar los demas con una consulta ajax -->
        </select>
        </div>
        <div>
        <label for="moneda">Moneda</label>
        <select name="moneda" id="moneda">
            <option value="none">Seleccioné</option>
            <?php foreach ($data['monedas'] as $moneda) {?>
                <option value="<?php echo $moneda->id; ?>"><?php echo $moneda->nombre; ?></option>
                <?php }?>
        </select>
        </div>
        <div>
            <label for="precio">Precio</label>
            <input type="text" name="precio" id="precio">
        </div>
        <div>
                Material del producto
        
        <label for="plastico">plastico</label>
        <input type="checkbox" name="plastico" id="plastico">
        <label for="metal">Metal</label>
        <input type="checkbox" name="metal" id="metal">
        <label for="madera">Madera</label>
        <input type="checkbox" name="madera" id="madera">
        <label for="vidrio">Vidrio</label>
        <input type="checkbox" name="vidrio" id="vidrio">
        <label for="textil">Textil</label>
        <input type="checkbox" name="textil" id="textil">
        </div>
        <div>
                <label for="descripcion">Descripcion</label>
                <textarea name="descripcion" id="descripcion"></textarea>
        </div>
        <div>
            <input type="submit" value="Votar"/>
        </div>
    </form>

    <script>

        $(document).ready(function () {

            $('#bodega').on('change', function () {
                let bodega = $(this).val();
                if(bodega == 'none'){
                    return false;
                }
                
                $.ajax({
                    method: 'GET',
                    url: '<?php echo URL::base(); ?>/sucursales',
                    dataType: 'json',
                    data: {
                        bodega
                    },
                    success: function (data) {
                        $('#sucursales').empty();
                    
                        $.each(data.data.sucursales, function (key, value) {
                            $('#sucursales').append('<option value="' + value.id + '">' + value.nombre + '</option>')
                        });             
                    }
                })
            });

            $('#registro-producto').on('submit', function(e){
                e.preventDefault();
                let codigo = $('input[name="codigo"]').val();
                let nombre = $('input[name="nombre"]').val();
                let bodega = $('select[name="bodega"]').val();
                let sucursal = $('select[name="sucursal"]').val();
                let moneda = $('select[name="moneda"]').val();
                let precio = $('input[name="region"]').val();
                let descripcion = $('textarea[name="descripcion"]').val();
                let plastico = $('input[name="plastico"]').is(':checked');
                let metal = $('input[name="metal"]').is(':checked');
                let madera = $('input[name="madera"]').is(':checked');
                let vidrio = $('input[name="vidrio"]').is(':checked');
                let textil = $('input[name="textil"]').is(':checked');

                $.ajax({
                    method: 'POST',
                    url: '<?php echo URL::base(); ?>/producto',
                    dataType: 'json',
                    data: {
                        codigo,
                        nombre,
                        bodega,
                        sucursal,
                        moneda,
                        precio,
                        descripcion,
                        checkboxs: {
                            plastico,metal,madera,vidrio,textil
                        }
                    },
                    success: function (data) {
                        
                    }
                })
            })
        })



    </script>
</body>
</html>
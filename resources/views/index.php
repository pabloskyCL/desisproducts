<?php

use app\Http\URL;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/resources/views/styles.css" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Registro de productos</title>
</head>
<body>
<div class="container">
      <form id="registro-producto" class="product-form">
        <h2>Formulario de Producto</h2>
        
        <div class="form-row">
          <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" id="codigo" name="codigo">
          </div>
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="bodega">Bodega</label>
            <select id="bodega" name="bodega">
            <option value="none"></option>
            <?php

 foreach ($data['bodegas'] as $bodega) {?>
                <option value="<?php echo $bodega->id; ?>"><?php echo $bodega->nombre; ?></option>
                <?php }?>
            </select>
          </div>
          <div class="form-group">
            <label for="sucursal">Sucursal</label>
            <select id="sucursales" name="sucursal">
            <option value="none"></option>
            <!-- agrega las sucursales con una consulta ajax -->
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
          <label for="moneda">Moneda</label>
        <select name="moneda" id="moneda">
            <option value="none"></option>
            <?php foreach ($data['monedas'] as $moneda) {?>
                <option value="<?php echo $moneda->id; ?>"><?php echo $moneda->nombre; ?></option>
                <?php }?>
        </select>
          </div>
          <div class="form-group">
            <label for="precio">Precio</label>
            <input type="text" id="precio" name="precio">
          </div>
        </div>

        <div class="form-group">
          <label>Material del Producto</label>
          <div class="checkbox-group">
            <label><input type="checkbox" id="plastico" name="plastico" value="plastico"> Plástico</label>
            <label><input type="checkbox" id="metal" name="metal" value="metal"> Metal</label>
            <label><input type="checkbox" id="madera" name="madera" value="madera"> Madera</label>
            <label><input type="checkbox" id="vidrio" name="vidrio" value="vidrio"> Vidrio</label>
            <label><input type="checkbox" id="textil" name="textil" value="textil"> Textil</label>
          </div>
        </div>

        <div class="form-group">
          <label for="descripcion">Descripción</label>
          <textarea id="descripcion" name="descripcion" rows="3"></textarea>
        </div>

        <button type="submit" class="submit-btn">Guardar Producto</button>
      </form>
    </div>
    <script>

        $(document).ready(function () {

            $('#bodega').on('change', function () {
                let bodega = $(this).val();
                if(bodega == "none"){
                    $('#sucursales')
                    .find('option')
                    .remove()
                    .end()
                    .append('<option value="none"></option>')
                    .val('none');
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
                        $('#sucursales').append('<option value="none"></option>').attr('selected', 'selected');
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
                let precio = $('input[name="precio"]').val();
                let descripcion = $('textarea[name="descripcion"]').val();
                let plastico = $('input[name="plastico"]').is(':checked');
                let metal = $('input[name="metal"]').is(':checked');
                let madera = $('input[name="madera"]').is(':checked');
                let vidrio = $('input[name="vidrio"]').is(':checked');
                let textil = $('input[name="textil"]').is(':checked');

                let countchecked = [plastico,metal,madera,vidrio,textil].reduce((acc, value) => {
                    if(value){
                        acc++;
                    }
                    return acc;
                },0);

                if(codigo == ''){
                    alert('el codigo es obligatorio');
                    return;
                }

                if(!validarCodigo(codigo)){
                    alert('el codigo debe tener solo caracteres y tener una longitud minima de 5 caracteres y maxima de 15');
                    return;
                }

                if(nombre == ''){
                    alert('el nombre es obligatorio');
                    return;
                }   

                if(!validarNombre(nombre)){
                    alert('el nombre debe tener solo caracteres y tener una longitud minima de 2 caracteres y maxima de 50');
                    return;
                }
                
                if(bodega == 'none'){
                    alert('debe seleccionar una bodega');
                    return;
                }

                if(sucursal == 'none'){
                    alert('debe seleccionar una sucursal');
                    return;
                }

                if(moneda == 'none'){
                    alert('debe seleccionar una moneda');
                    return;
                }

                if(precio == ''){
                    alert('el precio es obligatorio');
                    return;
                }
        
                if(!validarPrecio(precio)){
                    alert('el precio debe ser un numero y tener maximo 2 decimales');
                    return;
                }

                if(countchecked < 2){
                    alert('debe seleccionar al menos 2 materiales');
                    return;
                }
                
                if(descripcion == ''){
                    alert('la descripcion es obligatoria');
                    return;
                }

                if(!validarDescripcion(descripcion)){
                    alert('la descripcion debe tener una longitud minima de 10 caracteres y maxima de 1000');
                    return;
                }
         
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
                        alert('producto guardado con exito');
                        $('#registro-producto').each(function(){
                            this.reset();
                        });

                        $('#sucursales')
                            .find('option')
                            .remove()
                            .end()
                            .append('<option value="none"></option>')
                            .val('none');
                    },
                    error: function (error) {
                        let response = error.responseJSON;

                        if(response.message && response.data == null){
                            alert(response.message);
                        }

                        if(response.data.code == "23505"){
                            alert('el codigo ya existe');
                        }
                    }
                })
            })
        })


    function validarCodigo(codigo) {
        const regex = /^[a-zA-Z0-9]{5,15}$/;
        return regex.test(codigo);
    }

    function validarNombre(nombre){
        const regex = /^[a-zA-Z0-9 ]{2,50}$/;
        return regex.test(nombre);
    }

    function validarPrecio(precio) {
    const regex = /^\d+(\.\d{1,2})?$/;
    return regex.test(String(precio));
    }

    function validarDescripcion(descripcion) {
    const regex = /^.{10,1000}$/s;
    return regex.test(descripcion);
    }

    </script>
</body>
</html>
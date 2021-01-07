$( document ).ready(function() {

    $("#form_pedido").submit(
        function( event ) {
            let id_pedido = $("#id_pedido").val();
            event.preventDefault();
            $("#btn").hide();
            $("#spinner").show();
            $("#seccion_card_pedido").hide(800)
            $.ajax({
                method: "POST",
                url: $("#form_pedido").attr("data-path"),
                dataType: 'json',
                data: {
                    "id_pedido": id_pedido //Aquí van las variables que deseas enviar
                }
            }).done(function(data) {
                if(data.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Pedido encontrado',
                        text: 'El pedido que busca se ha encontrado en nuestro archivo',
                    }).then(() => {
                        rellenarCardPedido(data.data)
                    })
                }

                $("#btn").show();
                $("#spinner").hide();

            }).fail(function(res) {
                if(res.status=='404'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pedido no encontrado',
                        text: 'No exíste ningún pedido con ese ID',
                    })
                }
                $("#btn").show();
                $("#spinner").hide();
                })

        }
    )

    function rellenarCardPedido(data){

        $("#id_pedido_titulo").html(data.id_order)
        $("#img_producto").attr('src', 'http://prestashop.sergiocc.es'+data.url_image)
        $("#fecha_creacion").html(formatTime(data.creation_date))
        $(".estado_pedido").html(data.name)
        $(".nombre_producto").html(data.product_name)
        $(".cantidad_producto").html(data.product_quantity)
        $(".nombre_cliente").html(data.firstname+' '+ data.lastname)
        $(".direccion_cliente").html(data.address1+', '+ data.postcode+ ', '+data.city+ ', '+ data.country)
        $(".precio_total").html(parseFloat(data.total_price_tax_incl).toFixed(2)  + '€')
        $(".precio_producto").html(parseFloat(data.product_price).toFixed(2)  + '€')
        $("#seccion_card_pedido").show(800)

        $("#seccion_card_pedido").show(800)
    }

    function formatTime(fecha){
        let date = new Date(fecha)
        let day = date.getDate()
        let month = date.getMonth() + 1
        let year = date.getFullYear()

        if(month < 10){
            return(`${day}-0${month}-${year}`)
        }else{
            return(`${day}-${month}-${year}`)
        }
    }
    $(".only-numeric").bind("keypress", function (e) {
        var keyCode = e.which ? e.which : e.keyCode

        if (!(keyCode >= 48 && keyCode <= 57)) {
            $(".error").css("display", "inline-block");
            return false;
        }else{
            $(".error").css("display", "none");
        }
    });
});
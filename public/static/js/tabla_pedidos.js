
    $(document).ready(function() {

        $( function() {
            $( "#datepicker" ).datepicker();
        } );

        $("#form_datatable").submit(
            (event) => {
                event.preventDefault();

                $('#tabla').DataTable().destroy()

                $('#tabla').show()

                $('#tabla').DataTable( {
                    ajax: {
                        url: $("#form_datatable").attr("data-path"),
                        method: 'POST',
                        data: {
                            date_add : $("#datepicker").val(),
                            postcode: $("#codigo_postal").val(),
                            current_state: $("#estado").val()
                        },

                    },
                    "columns": [
                        { "data": "id_order" },
                        { "data": "firstname" },
                        { "data": "postcode" },
                        { "data": "country" , "width": "15%"},
                        { "data": "product_name" , "width": "50%" },
                        { "data": "product_price" },
                        { "data": "product_quantity" },
                        { "data": "total_price_tax_incl" },
                        { "data": "name" , "width": "15%"},
                        { "data": "creation_date" }
                    ]
                } );


            }
        )


        $(".only-numeric").bind("keypress", function (e) {
            var keyCode = e.which ? e.which : e.keyCode

            if (!(keyCode >= 48 && keyCode <= 57)) {
                $(".error").css("display", "inline-block");
                return false;
            }else{
                $(".error").css("display", "none");
            }
        });
    } );


var estado = 0;//para iniciar turno
var nOperario = 0;
var turno = {};
var datoeturno = "";
var operario = "";
var maquina = "";
//modulos.titulos = new Array();
//modulos.titulos.rss = "Lector RSS";

//proceso,numeroOperarios
$(function () {
    //validarReponer();
	//traerEtiqueta();
    $("#title").text("Código Operario de Mantenimiento");
    $(".number").each(function (index) {
        $(this).click(function () {
            var digIn = $(this).data("val");
            if ($("#dataIn").text() == "Digite" || $("#dataIn").text() == "Invalido!") {
                $("#dataIn").text("")
            }
            switch (digIn) {
                case "ok":
                    procesarInformacion($("#dataIn").text());
                    break;
                case "cancel":
                    $("#dataIn").text("Digite")
                    break;
                default:
                    var dataInput = $("#dataIn").text() + digIn;
                    $("#dataIn").text(dataInput);
            }
        });

        $(this).on({"touchstart mousedown": function onInteraction(e) {
                $(this).css("background-color", "#303030");
            }, "mouseup": function onInteractionEnd(e) {
                $(this).css("background-color", "black");
            }});

        $(this).mouseup(function () {
                    $(this).css("background-color", "black");
                })
                .mousedown(function () {
                    $(this).css("background-color", "#303030");
                });

    });
});

var traerEtiqueta = function () {
        $.ajax({
            url: "turno.php",
            type: "post",
            data: {estado: 13,
                maquina: proceso},
            datatype: 'json',
            success: function (data) {
                res = JSON.parse(data);
                if (res.resultado) {
                    var datosTurno = res.resultado;
					datoeturno = datosTurno.dturno;
                } else {

                }
            },
            error: function () {
                //alert('Error en datos turno!');
            }
        });
    };

var procesarInformacion = function (entrada) {
    switch (estado) {
        case 0:
            validarInformacion(entrada);
            break;
        default:
    }
}

var evolucionar = function (dato) {
    switch (estado) {
        case 0:
            operario = dato;
			maquina = proceso;
			estado = 1;
			avanzar(turno);
            break;
        default:
    }

}
var validarInformacion = function (codigo) {
    $.ajax({
        url: "turno.php",
        type: "post",
        data: {estado: estado,
            codigo: codigo},
        datatype: 'json',
        success: function (data) {
            res = JSON.parse(data);
            if (res.resultado) {
                evolucionar(res.dato);
            } else {
                $("#dataIn").text("Invalido!");
            }
        },
        error: function () {
            //alert('error al validar!');
        }
    });
};

  /*  var validarReponer = function () {
        $.ajax({
            url: "turno.php",
            type: "post",
            data: {estado: 5,
                maquina: proceso},
            datatype: 'json',
            success: function (data) {
                res = JSON.parse(data);
                if (res.resultado) {
                    var datosTurno = res.resultado;
                    if(datosTurno.estadoTurno==1){
                         window.location.replace("mic_contando.php?proceso="+proceso);
                    }
                } else {

                }
            },
            error: function () {
                //alert('Error en datos turno!');
            }
        });
    };
*/
var avanzar = function (turno) {
	window.location.replace("reg_orden?operario="+operario+"&maquina="+maquina);
};
// activar dropdown con transicion

(function($){

    var loadCalendar = function(object, monthDiff){

        var data = object.data('semanticCalendar');

        // Update month
        data.baseDate.add(monthDiff, 'months');

        // Calculate the visible period
        var start = data.baseDate.clone().startOf('month').hour(12);
        start.subtract(start.day() == 0 ? 7 : start.day(), 'days');
        start.locale(data.options.locale);

        var end = data.baseDate.clone().endOf('month').hour(12);
        end.day(6);
        end.add((6 * 7 - 1) - end.diff(start, 'days'), 'days');
        end.locale(data.options.locale);

        var trs = object.find("tbody tr");

        // Build days
        for(var i = 0; start.isBefore(end); start.add(1, 'days'), i++){
            var tr = $(trs[parseInt(i / 7)]);
            var td = $(tr.children("td")[i % 7]);

            td.html(start.date()).data('date', start.clone());

            // Update Css
            td.removeClass();
            td.toggleClass("current-month", start.month() == data.baseDate.month());

            if(start.isAfter(data.options.maxDate, "day")){
                td.addClass("disabled");
            }else if(start.isSame(data.selectedDate, "day")){
                td.addClass("selected");
            }else if(data.highlight && (start.isBetween(data.highlight.startDate, data.highlight.endDate, "day") || start.isSame(data.highlight.endDate, "day") || start.isSame(data.highlight.startDate, "day"))){
                td.addClass("highlight");
            }
        }

        // Update labels
        object.find("span.current-month").html(data.baseDate.locale(data.options.locale).format('MMMM'));
        object.find("span.current-year").html(data.baseDate.locale(data.options.locale).format('YYYY'));

        // Update buttons
        object.find(".button.previous-month").toggleClass("disabled", data.baseDate.isSame(data.options.minDate, "month"));
        object.find(".button.next-month").toggleClass("disabled", data.baseDate.isSame(data.options.maxDate, "month"));
    };

    $.fn.semanticCalendar = function(options){

        // Default options configuration
        var defaultOptions = {
            locale: 'en',
            name: 'calendar',
            minDate: moment().subtract(6, 'months'),
            maxDate: moment(),
            selectedDate: moment()
        };
        options = $.extend(defaultOptions, options);

        return this.each(function(index, object){
            var calendar = $(object);

            if(calendar.data("selectedDate")){
                var value = calendar.data("selectedDate")
                var format = moment.localeData(options.locale).longDateFormat("L");
                options.selectedDate = moment(value, format);
            }

            // Update selected date locale
            options.selectedDate.locale(options.locale);

            // Create data
            var data = {options: options};
            data.baseDate = options.selectedDate.clone();
            data.highlight = options.highlight;

            if(options.selectedDate.isAfter(options.maxDate)){
                data.selectedDate = options.maxDate.locale(options.locale).clone();
            }else{
                data.selectedDate = options.selectedDate.clone();
            }

            // Ajusting css and storing data

            calendar.data('semanticCalendar', data);
            calendar.addClass("ui compact collapsing table semantic-calendar");

            // Input
            var input = $("<input type='hidden'/>").attr("name", options.name)
            input.val(data.selectedDate.format("L"));
            calendar.append(input);

            // Thead
            var thead = $("<thead/>");
            calendar.append(thead);

            // Navigator
            var navigatorTr = $("<tr/>");
            thead.append(navigatorTr);

            // Previous month button
            var previousMonthButton = $("<div class='ui compact icon button previous-month'><i class='left arrow icon'></i></div>");
            previousMonthButton.on('click', calendar, function(e){
                loadCalendar(e.data, -1);
            });
            navigatorTr.append($("<th style='padding:0'/>").append(previousMonthButton));

            // Navigator's labels
            navigatorTr.append($("<th colspan='3'/>").append($("<span class='current-month'/>")));
            navigatorTr.append($("<th colspan='2'/>").append($("<span class='current-year'/>")));

            // Next month button
            var nextMonthButton = $("<div class='ui compact icon button next-month'><i class='right arrow icon'></i></div>");
            nextMonthButton.on('click', calendar, function(e){
                loadCalendar(e.data, 1);
            });
            navigatorTr.append($("<th style='padding:0'/>").append(nextMonthButton));

            // WeekDays
            var weekDaysTr = $("<tr/>");
            thead.append(weekDaysTr);

            // Load WeekDays from locale
            moment.locale(options.locale);
            var weekDays = moment.weekdaysShort();
            for(var i = 0; i < weekDays.length; i++){
                weekDaysTr.append($("<th/>").html(weekDays[i]));
            }

            // Tbody
            var tbody = $("<tbody/>");
            for(var i = 0; i < 6; i++){
                var tr = $("<tr/>");

                for(var j = 0; j < 7; j++){
                    var td = $("<td/>");

                    td.on('click', calendar, function(e){
                        // Update Css
                        $(this).parents('tbody').find('td.selected').removeClass('selected');
                        $(this).addClass('selected');

                        // Trigger change event and update selectedDate
                        var calendar = e.data;
                        var date = $(this).data('date');
                        calendar.data('semanticCalendar').selectedDate = date;

                        calendar.find("input[type='hidden']").val(date.format("L"));
                        calendar.trigger("semanticCalendar:change", [date.clone()]);
                    });

                    tr.append(td);
                }
                tbody.append(tr);
            }
            calendar.append(tbody);

            // Events
            calendar.on("semanticCalendar:updateDate", calendar, function(e, eventData){
                var calendar = e.data;
                var data = calendar.data("semanticCalendar");

                if(eventData.minDate){
                    data.options.minDate = eventData.minDate.clone();
                }

                if(eventData.maxDate){
                    data.options.maxDate = eventData.maxDate.clone();
                }

                if(eventData.selectedDate){
                    data.baseDate = eventData.selectedDate.clone();
                    data.selectedDate = eventData.selectedDate.clone();

                    calendar.find("input[type='hidden']").val(eventData.selectedDate.format("L"));
                    calendar.trigger("semanticCalendar:change", [eventData.selectedDate.clone()]);
                }

                data.highlight = eventData.highlight;

                loadCalendar(calendar, 0);
            });

            loadCalendar(calendar, 0);
        });
    };

}(jQuery));
(function displayCalendar2($){
    $.fn.semanticDatePicker = function(options){

        // Default options configuration
        var defaultOptions = {
            name: 'date'
        };
        options = $.extend(defaultOptions, typeof options !== 'undefined' ? options : {});

        return this.each(function(index, object) {
            var datepicker = $(object);

            // Update Css
            datepicker.addClass("ui top left pointing labeled icon button");
            datepicker.addClass("semantic-datepicker");

            // Build calendar
            var calendar = $("<table/>");

            if(datepicker.data("selectedDate")){
                calendar.data("selectedDate", datepicker.data("selectedDate"));
            }

            calendar.semanticCalendar(options);
            calendar.on("semanticCalendar:change", function(e, date){
                datepicker.find("span.text").html(date.format("L"));
                datepicker.trigger("semanticDatePicker:change", [date]);
            });

            // Build label
            datepicker.append($("<i class='calendar icon'/>"));
            datepicker.append($("<span class='text'/>").html(
                    calendar.data("semanticCalendar").selectedDate.format("L"))
            );

            var dropdownContent = $("<div class='menu' tabindex='-1'/>")
            dropdownContent.append($("<div class='ui segment'/>").append(calendar));
            datepicker.append(dropdownContent);

            datepicker.addClass("dropdown");
            datepicker.dropdown();
        });

    };
}(jQuery));
(function($){
  $.fn.semanticDateRangePicker = function(options){

    // Default options configuration
    var defaultOptions = {
      endDateName: 'endDate',
      startDateName: 'startDate',
      startDate: moment(),
      endDate: moment()
    };
    options = $.extend(defaultOptions, typeof options !== 'undefined' ? options : {});

    return this.each(function(index, object) {
      var datepicker = $(object);

      // Update Css
      datepicker.addClass("ui top left pointing labeled icon button");
      datepicker.addClass("semantic-daterangepicker");

      // Build calendar
      // Left calendar
      var leftCalendar = $("<table/>").semanticCalendar($.extend(options,{
        name: options.startDateName,
        selectedDate: options.startDate,
        highlight:{
          startDate: options.startDate,
          endDate: options.endDate
        }
      }));
      leftCalendar.on("semanticCalendar:change", datepicker, function(e, date){
        var data = {};

        if(rightCalendar.data("semanticCalendar").selectedDate.isAfter(date, "day")){
          data.highlight = {
            startDate: date,
            endDate: rightCalendar.data("semanticCalendar").selectedDate
          }
        }
        leftCalendar.trigger("semanticCalendar:updateDate", data);
        rightCalendar.trigger("semanticCalendar:updateDate", data);

        datepicker.find("span.left-calendar-label").html(date.format("L"));
        datepicker.trigger("semanticDateRangePicker:change", [date, rightCalendar.data("semanticCalendar").selectedDate]);
      });

      // Right calendar
      var rightCalendar = $("<table/>").semanticCalendar($.extend(options,{
        name: options.endDateName,
        selectedDate: options.endDate,
        highlight:{
          startDate: options.startDate,
          endDate: options.endDate
        }
      }));
      rightCalendar.on("semanticCalendar:change", datepicker, function(e, date){
        var data = {maxDate: date};

        if(leftCalendar.data("semanticCalendar").selectedDate.isAfter(date, "day")){
          data.selectedDate = date;
        }else{
          data.highlight = {
            startDate: leftCalendar.data("semanticCalendar").selectedDate,
            endDate: date
          }
        }

        leftCalendar.trigger("semanticCalendar:updateDate", data);
        rightCalendar.trigger("semanticCalendar:updateDate",{highlight: data.highlight});

        datepicker.find("span.right-calendar-label").html(date.format("L"));
        datepicker.trigger("semanticDateRangePicker:change", [leftCalendar.data("semanticCalendar").selectedDate, date]);
      });

      // Divider
      var divider = $("<div class='ui two column middle aligned relaxed fitted stackable grid'/>");
      divider.append($("<div class='column'/>").append(leftCalendar));
      divider.append($("<div class='ui vertical divider'><i class='calendar icon'></i></div>"));
      divider.append($("<div class='column'/>").append(rightCalendar));

      // Build label
      datepicker.append($("<i class='calendar icon'/>"));
      datepicker.append($("<span class='text left-calendar-label'/>").html(
        leftCalendar.data("semanticCalendar").selectedDate.format("L"))
      );
      datepicker.append($("<span class='text'>&nbsp;-&nbsp;</span>"));
      datepicker.append($("<span class='text right-calendar-label'/>").html(
        rightCalendar.data("semanticCalendar").selectedDate.format("L"))
      );

      // Dropdown
      var dropdownContent = $("<div class='menu' tabindex='-1'/>")
      dropdownContent.append($("<div class='ui segment'/>").append(divider));
      datepicker.append(dropdownContent);

      datepicker.addClass("dropdown");
      datepicker.dropdown();
    });

  };
}(jQuery));

$('.ui.dropdown')
  .dropdown({
    transition: 'fade down',
    duration  : 300
  })
;
// mostrar ventana modal con boton
$('.ui.basic.modal')
  .modal({
    transition: 'slide up',
    blurring  : false
  })
  .modal('attach events', '#ini_modal', 'show')
;
// validar formulario
$('.ui.form')
  .form({
    transition: 'slide down',
    duration: 300,
    on     : 'change',
    delay  : false,
    inline : true,
    fields : {
      nombre: {
        identifier  : 'nombre',
        rules: [
          {
            type   : 'empty',
            prompt : 'Por favor ingrese un {name}'
          }
        ]
      },
      usuario: {
        identifier  : 'usuario',
        rules: [
          {
            type   : 'text',
            prompt : 'Por favor ingrese un {name}'
          }
        ]
      },
      email: {
        identifier  : 'email',
        rules : [
          {
            type   : 'empty',
            prompt : 'Por favor ingrese un {name}'
          },
          {
            type   : 'email',
            prompt : 'Por favor ingrese un {name} valido'
          },
          {
            type   : 'containsExactly[@usc.edu.co]',
            prompt : 'el {name} debe ser institucional'
          }
        ]
      },
      password: {
        identifier  :'pass',
        rules: [
          {
            type   : 'empty',
            prompt : 'Por favor ingrese una {name}'
          },
          {
            type   : 'minLength[2]',
            prompt : 'La {name} debe tener minimo {ruleValue} caracteres'
          }
        ]
      },

    }
    // onSuccess: function () {
    //   Swal.fire({
    //     title   : 'Usuario Registrado con Exito!',
    //     text    : 'Bienvenido',
    //     type    : 'success',
    //     onAfterClose : function () {
    //       document.registro.submit()
    //     }
    //   })
    //   return false;
    // },
    // onFailure:  function () {
    //   Swal.fire(
    //     'Error al Registrar Usuario!',
    //     'Revise los campos!',
    //     'error'
    //   )
    //   return false;
    // }
    // onValid : function () {
    //   if ($('.ui.form.text').form('is valid', 'nombre')) {
    //     $("#input").after("<i id='gIcon' class='green smile outline icon'></i>");
    //   }
    //   $("#rIcon").remove();
    // },
    // onInvalid : function () {
    //   if ($('.ui.form.text').form('is valid', 'nombre') == false) {
    //     $("#gIcon").remove();
    //   }
    // }
  })
;
// Validar Form modal


$('#form_Modal')
  .form({
    transition: 'slide down',
    duration: 300,
    on     : 'change',
    delay  : false,
    inline : true,
    fields : {
      usuarioModal  : {
        identifier  : 'usuario_Modal',
        rules:[
          {
            type    : 'empty',
            prompt  : 'Ingrese un {name}'
          }
        ]
      },
      passwordModal : {
        identifier  : 'password_Modal',
        rules:[
          {
            type    : 'empty',
            prompt  : 'Ingrese una {name}'
          },
          {
            type    : 'minLength[6]',
            prompt  : 'La {name} debe tener minimo {ruleValue} caracteres'
          }
        ]
      }
    },
    onSuccess:  function () {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });

      Toast.fire({
        type: 'success',
        title: 'Inicio de Sesion exitoso!',
        onOpen: function () {
          $('.ui.basic.modal').modal('hide');
        }
      })
      return false;
    },
    onFailure:  function () {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });

      Toast.fire({
        type: 'error',
        title: 'Datos incorrectos!'
      })
      return false;
    }
  })
;
// Activar Rating
$('.ui.rating')
  .rating({
    initialRating: 5,
    maxRating: 5
  })
;
// Menu Activable
$(document).ready(function() {
  var inicio = "http://projectmaster.test/index.php";
  if (window.location.href == inicio) {
    $('#inicio').addClass("active");
  }
  else {
    $('#inicio').removeClass("active");
  }
});
// animacion plane
$('.ui.header.footer').mouseenter(function() {
  $('#plane').transition({
    animation   : 'drop',
    duration    : '1s',
    displayType : 'inline-block',
    onComplete  : function () {
      $('#plane')
        .transition('show')
    }
  })
})
;
$('ui.header.footer').mouseenter(function () {
  $('#plane').transition({
    animation : 'shake',
    duration  : '1s',
    displayType: 'inline-block'

  })
});

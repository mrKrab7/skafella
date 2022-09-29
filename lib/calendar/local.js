// Engine.js
// version 1.0.22
// © 2013 Denis Ineshin | IonDen.com
// =====================================================================================================================

var ion = ion || {};

// =====================================================================================================================
// Launch all modules

$(function(){
    $("#calendar-1").ionCalendar({
        lang: "ru",
        sundayFirst: false,
        years: "80",
        format: "DD.MM.YYYY",
        onClick: function(date){
            $("#result-1").html("onClick:<br/>" + date);
        }
    });
    $("#calendar-2").ionCalendar({
        lang: "en",
        years: "1915-1995",
        onClick: function(date){
            $("#result-2").html("onClick:<br/>" + date);
        }
    });
    $("#calendar-3").ionCalendar({
        lang: "ja",
        years: "20",
        format: "LLLL",
        onClick: function(date){
            $("#result-3").html("onClick:<br/>" + date);
        }
    });
    $("#calendar-4").ionCalendar({
        lang: "ru",
        sundayFirst: false,
        years: "30",
        startDate: "07.08.2009",
        format: "DD.MM.YYYY",
        hideArrows: true,
        onClick: function(date){
            $("#result-4").html("onClick: " + date);
        },
        onReady: function(date){
            $("#result-4").html("Каледарь готов!<br />" + date);
        }
    });


    $(".date").each(function(){
        $(this).ionDatePicker();
    });
});


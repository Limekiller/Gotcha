$(document).ready(function () {
        scroll();
        scroll2();
        scroll3();
        scroll4();
    //    $( "#bubble" ).animate({
        //        marginTop:"1000px",
    //    }, 10000, function() {
        //        // Animation complete.
        //    });
    });

function scroll() {
        var elem  = "#a".concat(Math.floor((Math.random() * 4) + 1));
        console.log(elem);
        $(elem).css("marginTop", "-650px");
        $(elem).animate({marginTop:"950px",}, Math.floor(Math.random()*(50000-10000+1)+10000), scroll);
}
function scroll2() {
        var elem  = "#b".concat(Math.floor((Math.random() * 4) + 1));
        console.log(elem);
        $(elem).css("marginTop", "-650px");
        $(elem).animate({marginTop:"950px",}, Math.floor(Math.random()*(50000-10000+1)+10000), scroll2);
}
function scroll3() {
        var elem  = "#c".concat(Math.floor((Math.random() * 4) + 1));
        console.log(elem);
        $(elem).css("marginTop", "-650px");
        $(elem).animate({marginTop:"950px",}, Math.floor(Math.random()*(50000-10000+1)+10000), scroll3);
}
function scroll4() {
        var elem  = "#d".concat(Math.floor((Math.random() * 4) + 1));
        console.log(elem);
        $(elem).css("marginTop", "-650px");
        $(elem).animate({marginTop:"950px",}, Math.floor(Math.random()*(50000-10000+1)+10000), scroll4);
}


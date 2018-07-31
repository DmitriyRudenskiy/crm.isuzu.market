function getData()
{
    var result = [];

    $('[role=row]').each(function(){

        var tmp = [];

        $(this).find('div').each(function(){
            tmp.push($(this).text().toString().trim())
        });

        result.push(tmp);
    });

    return result;
}

function getMenu()
{
    var result = [];

    $('.breadcrumb li').each(function(){
        var text = $(this).text().toString().trim();
        if (text.length > 0) {
            result.push(text);
        }
    });

    return result;
}

function saveData(filename, text) {

    var blob = new Blob([text], {type: 'application/json'});
    var anchor = document.createElement('a');

    anchor.download = filename;
    anchor.href = (window.webkitURL || window.URL).createObjectURL(blob);
    anchor.dataset.downloadurl = ['application/json', anchor.download, anchor.href].join(':');
    anchor.click();
}

function run()
{
    var marker = new Date().getTime();

    saveData(marker + "_content.json", JSON.stringify(getData(), null));
    saveData(marker + "_menu.json", JSON.stringify(getMenu(), null));
}


// подключаем jQuery
if (typeof(jQuery) === 'undefined') {
    var script   = document.createElement("script");
    script.type  = "text/javascript";
    script.src   = "https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js";

    script.onload = function () {
        run();
    };

    document.body.appendChild(script);
} else {
    run();
}







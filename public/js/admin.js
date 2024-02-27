function winOpen(url, name, w = 900, h = 600) {
    const left = screen.width ? (screen.width - w) / 2 : 0;
    const top = screen.height ? (screen.height - h) / 2 : 0;
    var settings = "";
    settings += "width=" + w;
    settings += ",";
    settings += "height=" + h;
    settings += ",";
    settings += "left=" + left;
    settings += ",";
    settings += "top=" + top;
    window.open(url, name, settings);
}

function setTable(id, columns, data, initialSort = []) {
    var table = new Tabulator("#" + id, {
        //selectable: true,
        //movableRows: true,
        layout: "fitDataFill",
        movableColumns: true,
        pagination: true,
        paginationSize: 10,
        paginationSizeSelector: [10, 30, 50, 100, true],
        columns: columns,
        data: data,
        initialSort: initialSort,
    });

    /*
    table.on("rowMoved", function (row) {
        console.log(row);
        console.log("Row: " + row.getData() + " has been moved");
    });
    */

    $("#excel-download").click(function () {
        const now = new Date();
        const filename =
            id +
            "_" +
            now.getFullYear() +
            "-" +
            (now.getMonth() + 1) +
            "-" +
            now.getDate() +
            ".xlsx";
        table.download("xlsx", filename, { sheetName: "MyData" });
    });
    return table;
}

function setTabulatorTable(id, columns, apiUrl, initialSort = []) {
    var table = new Tabulator("#" + id, {
        //selectable: true,
        //movableRows: true,
        layout: "fitDataFill",
        movableColumns: true,
        pagination: true,
        paginationSize: 10,
        paginationSizeSelector: [10, 30, 50, 100, true],
        columns: columns,
        initialSort: initialSort,
        ajaxURL: apiUrl,
    });

    /*
    table.on("rowMoved", function (row) {
        console.log(row);
        console.log("Row: " + row.getData() + " has been moved");
    });
    */

    //table.setData(apiUrl);

    $("#excel-download").click(function () {
        const now = new Date();
        const filename =
            id +
            "_" +
            now.getFullYear() +
            "-" +
            (now.getMonth() + 1) +
            "-" +
            now.getDate() +
            ".xlsx";
        table.download("xlsx", filename, { sheetName: "MyData" });
    });
    return table;
}

function formatDate(date) {
    var mymonth = date.getMonth() + 1;
    var myweekday = date.getDate();
    return (
        date.getFullYear() +
        "-" +
        (mymonth < 10 ? "0" : "") +
        mymonth +
        "-" +
        (myweekday < 10 ? "0" : "") +
        myweekday
    );
}

function formatTime(num) {
    var h = parseInt(num / 60);
    var m = num % 60;
    var str = "";
    if (h < 10) str += "0";
    str += h;
    str += ":";
    if (m < 10) str += "0";
    str += m;
    return str;
}

function formatCellphone(cellphone) {
    switch (cellphone.length) {
        case 8: // 1588-1111
            return cellphone.substr(0, 4) + "-" + cellphone.substr(4, 4);
        case 9: // 031-777-1111
            return (
                cellphone.substr(0, 3) +
                "-" +
                cellphone.substr(3, 3) +
                "-" +
                cellphone.substr(7, 4)
            );
        case 10: // 02-1588-1111
            return (
                cellphone.substr(0, 2) +
                "-" +
                cellphone.substr(2, 4) +
                "-" +
                cellphone.substr(7, 4)
            );
        case 11: // 010-1111-2222
            return (
                cellphone.substr(0, 3) +
                "-" +
                cellphone.substr(3, 4) +
                "-" +
                cellphone.substr(7, 4)
            );
        default:
            return cellphone;
    }
}

function commaNumber(num) {
    const str = num.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    return str;
}

function searchDate(beg, end, term) {
    var obj1 = document.getElementById(beg);
    var obj2 = document.getElementById(end);
    var date1 = new Date();
    var date2 = new Date();
    date1.setDate(date1.getDate() - term);
    obj1.value = formatDate(date1);
    obj2.value = formatDate(date2);
}

function checkDate(date) {
    const y = date.substr(0, 4);
    const m = date.substr(5, 2);
    const d = date.substr(8, 2);
    if (parseInt(y) <= 2000 || parseInt(y) >= 3000) {
        return null;
    } else {
        return date;
    }
}

function loadRequest(data) {
    for (var key in data) {
        const value = data[key];
        if (!!value) {
            const el = $("[name=" + key + "]");
            const tagName = el.prop("tagName");
            const inputType = el.attr("type");
            console.log(key, value, tagName, inputType);
            if (tagName == "SELECT") {
                el.val(value).prop("selected", true);
            } else if (inputType == "checkbox") {
                $("[name='" + key + "'][value='" + value + "']").prop(
                    "checked",
                    true
                );
                //el.val(value).prop("checked", true);
            } else if (inputType == "radio") {
                $("[name='" + key + "'][value='" + value + "']").prop(
                    "checked",
                    true
                );
            } else if (inputType == "file") {
            } else {
                el.val(value);
            }

            const next = el.next();
            if (next.hasClass("pre-view")) {
                next.attr("src", value);
                next.removeClass("dn");
                next.addClass("mart5");
            }
        }
    }
}

function loadData(data) {
    for (var key in data) {
        const value = data[key];
        if (!!value) {
            const el = $("[name=" + key + "]");
            const tagName = el.prop("tagName");
            const inputType = el.attr("type");
            //console.log(key, value, tagName, inputType);
            if (tagName == "SELECT") {
                el.val(value).prop("selected", true);
            } else if (inputType == "checkbox") {
                $("[name='" + key + "'][value='" + value + "']").prop(
                    "checked",
                    true
                );
            } else if (inputType == "radio") {
                $("[name='" + key + "'][value='" + value + "']").prop(
                    "checked",
                    true
                );
            } else if (inputType == "file") {
            } else {
                el.val(value);
            }

            const next = el.next();
            if (next.hasClass("pre-view")) {
                next.attr("src", value);
                next.removeClass("dn");
                next.addClass("mart5");
            }
        }
    }
}

function myFetch(url, success) {
    $.ajax({
        url: url,
        type: "GET",
        datatype: "json",
        success: function (data, textStatus, xhr) {
            success(JSON.parse(data));
        },
        error: function (xhr, textStatus, errorThrown) {},
        complete: function (xhr, status) {},
    });
}

function setFormData(controller, setData) {
    $("#frm-data").attr("action", url + "/" + controller + "/add");
    const pathlist = location.pathname.split("/");
    const ident = pathlist[4];
    if (!!ident) {
        $(".changeable-data").attr("disabled", false);
        $(".unchangeable-data").attr("disabled", true);
        myFetch(url + "/" + controller + "/get/" + ident, setData);
        $("#frm-data").attr("action", url + "/" + controller + "/set/" + ident);
        $("#frm-submit").val("수정");
    }
}

$(function () {
    myFetch(url + "/test/getList?rpp=1000&page=1", setData);
    /*
    var list = [];
    getData().then((res) => {
        setData(res);
    });
    */
});
var table;
function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "index_no",
            hozAlign: "center",
            editor: false,
            cellClick: function (e, cell) {
                winOpen(url + "/test/form/" + cell._cell.value, "test-popup");
            },
        },
        {
            title: "주문 일련 번호",
            field: "od_id",
        },
        {
            title: "주문 번호",
            field: "od_no",
        },
        {
            title: "판매자",
            field: "seller_id",
        },
        {
            title: "주문상태",
            field: "dan",
        },
        {
            title: "주문자",
            field: "name",
        },
        {
            title: "주문자 전화번호",
            field: "cellphone",
        },
        {
            title: "주문일시",
            field: "od_time",
        },
    ];
    table = setTable("test-list", columns, list, [
        { column: "od_time", dir: "desc" },
    ]);
    getData(table);
    //setTimeout(getData(), 1000);
}

var i = 2;
async function getData(table) {
    var list = [];
    for (var i = 2; i < 10; i++) {
        console.log("for");
        var res = await $.ajax({
            url: url + "/test/getList?rpp=1000&page=" + i,
            type: "GET",
            datatype: "json",
        });
        res = JSON.parse(res);
        //table.addData(res, false);
        //table.addRow(res, false);
        if (res.length < 1000) {
            break;
        }
    }
    return list;
}

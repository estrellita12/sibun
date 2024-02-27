$(function () {
    myFetch("/admin/popup/getList", setData);
});

function setData(list) {
    $("#cnt").html(list.length);

    var columns = [
        {
            title: "번호",
            field: "popup_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(url + "/popup/form/" + cell._cell.value, "popup-form");
            },
        },
        {
            title: "순서",
            field: "popup_orderby",
            visible: false,
        },
        {
            title: "제목",
            field: "popup_title",
        },
        {
            title: "시작일시",
            field: "popup_beg_dt",
            formatter: function (cell, formatterParams, onRendered) {
                return checkDate(cell.getValue());
            },
        },
        {
            title: "종료일시",
            field: "popup_end_dt",
            formatter: function (cell, formatterParams, onRendered) {
                return checkDate(cell.getValue());
            },
        },
        {
            title: "사용여부",
            field: "popup_use_yn",
            formatter: "lookup",
            formatterParams: { y: "사용", n: "미사용" },
        },
        {
            title: "등록일시",
            field: "popup_reg_dt",
            editor: false,
        },
    ];

    setTable("popup-list", columns, list, [
        { column: "popup_orderby", dir: "asc" },
    ]);
}

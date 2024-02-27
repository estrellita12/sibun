$(function () {
    myFetch("/admin/event/getList", setData);
});

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "event_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(url + "/event/form/" + cell._cell.value, "event-form");
            },
        },
        {
            title: "순서",
            field: "event_orderby",
            visible: false,
        },
        {
            title: "제목",
            field: "event_title",
        },
        {
            title: "썸네일",
            field: "event_simg",
            formatter: "image",
            formatterParams: { height: 25 },
        },
        {
            title: "시작일시",
            field: "event_beg_dt",
            formatter: function (cell, formatterParams, onRendered) {
                return checkDate(cell.getValue());
            },
        },
        {
            title: "종료일시",
            field: "event_end_dt",
            formatter: function (cell, formatterParams, onRendered) {
                return checkDate(cell.getValue());
            },
        },
        {
            title: "사용여부",
            field: "event_use_yn",
            formatter: "lookup",
            formatterParams: { y: "사용", n: "미사용" },
        },
        {
            title: "등록일시",
            field: "event_reg_dt",
        },
    ];

    setTable("event-list", columns, list, [
        { column: "event_reg_dt", dir: "asc" },
    ]);
}

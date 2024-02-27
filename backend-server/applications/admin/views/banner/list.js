$(function () {
    myFetch(url + "/banner/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "bn_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(
                    url + "/banner/form/" + cell._cell.value,
                    "banner-form"
                );
            },
        },
        {
            title: "순서",
            field: "bn_orderby",
            sorter: "number",
            visible: false,
        },
        {
            title: "제목",
            field: "bn_title",
        },
        {
            title: "이미지",
            field: "bn_img_src",
            formatter: "image",
            formatterParams: { height: 25 },
        },
        {
            title: "링크",
            field: "bn_link",
        },
        {
            title: "타겟",
            field: "bn_target",
        },

        {
            title: "시작일시",
            field: "bn_beg_dt",
            formatter: function (cell, formatterParams, onRendered) {
                return checkDate(cell.getValue());
            },
        },
        {
            title: "종료일시",
            field: "bn_end_dt",
            formatter: function (cell, formatterParams, onRendered) {
                return checkDate(cell.getValue());
            },
        },
        {
            title: "사용여부",
            field: "bn_use_yn",
            formatter: "lookup",
            formatterParams: { y: "사용", n: "미사용" },
        },
        {
            title: "등록일시",
            field: "bn_reg_dt",
        },
        {
            title: "수정일시",
            field: "bn_update_dt",
        },
    ];
    setTable("banner-list", columns, list, [
        { column: "bn_orderby", dir: "asc" },
    ]);
}

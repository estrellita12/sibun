$(function () {
    myFetch(url + "/media/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "media_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(url + "/media/form/" + cell._cell.value, "media-form");
            },
        },
        {
            title: "순서",
            field: "media_orderby",
            sorter: "number",
            visible: false,
        },
        {
            title: "제목",
            field: "media_title",
        },
        {
            title: "썸네일",
            field: "media_simg",
            formatter: "image",
            formatterParams: { height: 25 },
            download: false,
        },
        {
            title: "출처",
            field: "media_reference",
        },
        {
            title: "사용여부",
            field: "media_use_yn",
            formatter: "lookup",
            formatterParams: {
                y: "사용",
                n: "미사용",
            },
        },
        {
            title: "등록일시",
            field: "media_reg_dt",
        },
        {
            title: "수정일시",
            field: "media_update_dt",
        },
    ];

    setTable("media-list", columns, list, [
        { column: "media_orderby", dir: "asc" },
    ]);
}

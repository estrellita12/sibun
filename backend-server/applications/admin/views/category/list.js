$(function () {
    myFetch(url + "/category/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);

    var columns = [
        {
            title: "번호",
            field: "ctg_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(
                    url + "/category/form/" + cell._cell.value,
                    "category-form"
                );
            },
        },
        {
            title: "순서",
            field: "ctg_orderby",
            sorter: "number",
            visible: false,
        },
        {
            title: "이미지",
            field: "ctg_icon_img",
            hozAlign: "center",
            formatter: "image",
            formatterParams: {
                height: 25,
            },
            download: false,
        },
        {
            title: "컬러",
            field: "ctg_color_hex",
            formatter: function (cell) {
                return (
                    "<div style='width:20px;height:20px;border-radius:50%;display:inline-block;background-color:" +
                    cell.getValue() +
                    "'></div>"
                );
            },
            hozAlign: "center",
        },
        {
            title: "이름",
            field: "ctg_title",
        },
        {
            title: "상단 이미지",
            field: "ctg_head_img_src",
            formatter: "image",
            formatterParams: {
                height: 25,
            },
            visible: false,
        },
        {
            title: "하단 이미지",
            field: "ctg_tail_img_src",
            formatter: "image",
            formatterParams: {
                height: 25,
            },
            visible: false,
        },
        {
            title: "사용여부",
            field: "ctg_use_yn",
            formatter: "lookup",
            formatterParams: {
                y: "사용",
                n: "미사용",
            },
        },
        {
            title: "등록일시",
            field: "ctg_reg_dt",
        },
        {
            title: "수정일시",
            field: "ctg_update_dt",
        },
    ];

    setTable("category-list", columns, list, [
        { column: "ctg_orderby", dir: "asc" },
    ]);
}

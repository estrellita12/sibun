$(function () {
    const pathlist = location.pathname.split("/");
    const ident = pathlist[4];
    if (!!ident) {
        myFetch("/admin/storeimage/getList/" + ident, setData);
    }
});
function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "store_img_idx",
            editor: false,
            cellClick: function (e, cell) {
                winOpen(
                    "<?=_URL?>/store/form/" + cell._cell.value,
                    "store-popup"
                );
            },
            width: 70,
        },
        {
            title: "순서",
            field: "store_img_orderby",
            editor: false,
            visible: false,
        },

        {
            title: "매장 번호",
            field: "store_img_by_store_idx",
            editor: false,
            visible: false,
        },
        {
            title: "이미지",
            field: "store_img_data",
            hozAlign: "center",
            formatter: "image",
            formatterParams: {
                height: "50px",
                width: "50px",
            },
            editor: false,
            width: 80,
        },
        {
            title: "등록일시",
            field: "store_img_reg_dt",
        },
        {
            title: "수정일시",
            field: "store_img_update_dt",
        },
    ];
    setTable("storeimage-list", columns, list, [
        { column: "store_img_orderby", dir: "asc" },
    ]);
}

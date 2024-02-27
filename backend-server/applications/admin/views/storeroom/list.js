$(function () {
    const pathlist = location.pathname.split("/");
    const ident = pathlist[4];
    if (!!ident) {
        myFetch("/admin/storeroom/getList/" + ident, setData);
    }
});
function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "store_room_idx",
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
            field: "store_room_orderby",
            editor: false,
            visible: false,
        },

        {
            title: "매장 번호",
            field: "store_room_by_store_idx",
            editor: false,
            visible: false,
        },
        {
            title: "이름",
            field: "store_room_name",
            editor: false,
        },
        {
            title: "상세 설명",
            field: "store_room_desc",
            editor: false,
        },
        {
            title: "사용여부",
            field: "store_room_use_yn",
            editor: false,
            formatter: "lookup",
            formatterParams: {
                y: "사용",
                n: "미사용",
            },
        },

        {
            title: "등록일시",
            field: "store_room_reg_dt",
        },
        {
            title: "수정일시",
            field: "store_room_update_dt",
        },
    ];
    setTable("storeroom-list", columns, list, [
        { column: "store_room_orderby", dir: "asc" },
    ]);
}

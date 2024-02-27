$(function () {
    const pathlist = location.pathname.split("/");
    const ident = pathlist[4];
    if (!!ident) {
        myFetch("/admin/storevoucher/getList/" + ident, setData);
    }
});
function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "store_voucher_idx",
            editor: false,
            cellClick: function (e, cell) {
                winOpen(
                    "<?=_URL?>/storevoucher/form/" + cell._cell.value,
                    "storevoucher-popup"
                );
            },
            width: 70,
        },
        {
            title: "순서",
            field: "store_voucher_orderby",
            editor: false,
            visible: false,
        },
        {
            title: "매장 번호",
            field: "store_voucher_by_store_idx",
            editor: false,
            visible: false,
        },
        {
            title: "코드",
            field: "store_voucher_code",
            editor: false,
        },
        {
            title: "제목",
            field: "store_voucher_title",
            editor: false,
        },
        {
            title: "상세 설명",
            field: "store_voucher_desc",
            editor: false,
        },
        {
            title: "이용 가능 방 목록",
            field: "store_voucher_available_room_li",
            editor: false,
        },
        {
            title: "할인율",
            field: "store_voucher_discount_rate",
            editor: false,
        },
        {
            title: "시작일",
            field: "store_voucher_beg_date",
            editor: false,
        },
        {
            title: "종료일",
            field: "store_voucher_end_date",
            editor: false,
        },
        {
            title: "시작 시간",
            field: "store_voucher_beg_time",
            editor: false,
            formatter: function (cell, formatterParams, onRendered) {
                return formatTime(cell.getValue() * 30);
            },
        },
        {
            title: "종료 시간",
            field: "store_voucher_end_time",
            editor: false,
            formatter: function (cell, formatterParams, onRendered) {
                return formatTime(cell.getValue() * 30);
            },
        },
        {
            title: "하루 사용 갯수",
            field: "store_voucher_daily_total_cnt",
            editor: false,
        },
        {
            title: "사용여부",
            field: "store_voucher_use_yn",
            editor: false,
            formatter: "lookup",
            formatterParams: {
                y: "사용",
                n: "미사용",
            },
        },
        {
            title: "등록일시",
            field: "store_voucher_reg_dt",
        },
        {
            title: "수정일시",
            field: "store_voucher_update_dt",
        },
    ];
    setTable("storevoucher-list", columns, list, [
        { column: "store_voucher_orderby", dir: "asc" },
    ]);
}

$(function () {
    myFetch("/admin/reservation/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "reservation_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(
                    url + "/reservation/form/" + cell._cell.value,
                    "reservation-form"
                );
            },
            cellContext: function (e, cell) {
                winOpen(
                    url + "/reservation/loglist/" + cell._cell.value,
                    "reservation-log-list"
                );
            },
        },
        {
            title: "예약 코드",
            field: "reservation_code",
        },
        {
            title: "매장 번호",
            field: "reservation_store_idx",
        },
        {
            title: "매장 이름",
            field: "store_name",
        },
        {
            title: "매장 전화 번호",
            field: "store_tel",
        },
        {
            title: "방 번호",
            field: "reservation_room_idx",
        },
        {
            title: "할인권 번호",
            field: "reservation_voucher_idx",
        },
        {
            title: "예약 상태",
            field: "reservation_stt",
            formatter: "lookup",
            formatterParams: {
                1: "대기",
                2: "승인",
                3: "취소",
                4: "노쇼",
                5: "입장",
                undefined: "",
            },
            cellClick: function (e, cell) {
                winOpen(
                    url + "/reservation/loglist/" + cell._cell.value,
                    "reservation-log-list"
                );
            },
        },
        {
            title: "회원 번호",
            field: "reservation_mb_idx",
        },
        {
            title: "예약자 이름",
            field: "reservation_user_name",
        },
        {
            title: "예약자 전화번호",
            field: "reservation_user_cellphone",
        },
        {
            title: "예약 일자",
            field: "reservation_date",
        },
        {
            title: "예약 시간",
            field: "reservation_time",
            formatter: function (cell, formatterParams, onRendered) {
                return formatTime(cell.getValue() * 30);
            },
        },
        {
            title: "예약 타임",
            field: "reservation_period",
            formatter: function (cell, formatterParams, onRendered) {
                return cell.getValue() * 30 + "분";
            },
        },
        {
            title: "승인 일시",
            field: "reservation_confirm_dt",
        },
        {
            title: "취소 일시",
            field: "reservation_cancel_dt",
        },
        {
            title: "취소 사유",
            field: "reservation_cancel_reason",
        },
        {
            title: "입장 일시",
            field: "reservation_enter_dt",
        },
        {
            title: "등록 일시",
            field: "reservation_reg_dt",
        },
    ];

    setTable("reservation-list", columns, list, [
        { column: "reservation_reg_dt", dir: "desc" },
    ]);
}

$(function () {
    const pathlist = location.pathname.split("/");
    const ident = pathlist[4];
    if (!!ident) {
        myFetch("/admin/reservation/getLogList/" + ident, setData);
    }
});

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "rsvt_hty_idx",
            hozAlign: "center",
        },
        {
            title: "예약번호",
            field: "rsvt_hty_by_reservation_idx",
        },
        {
            title: "예약 상태",
            field: "rsvt_hty_stt",
            formatter: "lookup",
            formatterParams: {
                1: "대기",
                2: "승인",
                3: "취소",
                4: "노쇼",
                5: "입장",
                undefined: "",
            },
        },
        {
            title: "처리자",
            field: "rsvt_hty_by_user",
        },
        {
            title: "등록 일시",
            field: "rsvt_hty_reg_dt",
        },
    ];

    setTable("reservation-log-list", columns, list, [
        { column: "rsvt_hty_reg_dt", dir: "desc" },
    ]);
}

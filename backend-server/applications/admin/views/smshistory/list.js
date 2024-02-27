$(function () {
    myFetch("/admin/smshistory/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "sms_hty_idx",
            editor: false,
            hozAlign: "center",
        },
        {
            title: "발신자",
            field: "sms_hty_sender",
        },
        {
            title: "수신자",
            field: "sms_hty_receiver",
        },
        {
            title: "메세지",
            field: "sms_hty_message",
        },
        {
            title: "발송결과",
            field: "sms_hty_result",
            formatter: function (cell, formatterParams, onRendered) {
                const result = JSON.parse(JSON.parse(cell.getValue()));
                return result.message;
            },
        },
        {
            title: "발송일시",
            field: "sms_hty_reg_dt",
        },
    ];
    setTable("sms-hty-list", columns, list, [
        { column: "sms_hty_reg_dt", dir: "desc" },
    ]);
}

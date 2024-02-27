$(function () {
    myFetch("/admin/notihistory/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "noti_hty_idx",
            editor: false,
            hozAlign: "center",
        },
        {
            title: "토큰",
            width:10,
            field: "noti_hty_message",
            formatter: function (cell, formatterParams, onRendered) {
                if(cell.getValue().length > 10){
                    const result = JSON.parse(cell.getValue());
                    return result['token'];
                }else{
                    return "";
                }
            },
        },
        {
            title: "제목",
            field: "noti_hty_message",
            formatter: function (cell, formatterParams, onRendered) {
                if(cell.getValue().length > 10){
                    const result = JSON.parse(cell.getValue());
                    return result['notification']['title'];
                }else{
                    return "";
                }
            },
        },
        {
            title: "내용",
            field: "noti_hty_message",
            formatter: function (cell, formatterParams, onRendered) {
                if(cell.getValue().length > 10){
                    const result = JSON.parse(cell.getValue());
                    return result['notification']['body'];
                }else{
                    return "";
                }
            },
        },
        {
            title: "발송결과",
            field: "noti_hty_result",
            formatter: function (cell, formatterParams, onRendered) {
                return cell.getValue();
            },
        },
        {
            title: "발송일시",
            field: "noti_hty_reg_dt",
        },
    ];
    setTable("sms-hty-list", columns, list, [
        { column: "noti_hty_reg_dt", dir: "desc" },
    ]);
}

$(function () {
    myFetch(url + "/partner/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);

    var columns = [
        {
            title: "번호",
            field: "pt_idx",
            cellClick: function (e, cell) {
                winOpen(
                    url + "/partner/form/" + cell._cell.value,
                    "partner-popup"
                );
            },
        },
        {
            title: "아이디",
            field: "pt_id",
        },
        {
            title: "상태",
            field: "pt_stt",
            formatter: "lookup",
            formatterParams: {
                1: "승인",
                undefined: "",
            },
        },
        {
            title: "이름",
            field: "pt_name",
        },
        {
            title: "닉네임",
            field: "pt_nickname",
        },
        {
            title: "프로필",
            field: "pt_profile_img",
            formatter: "image",
            formatterParams: { height: 25 },
        },
        {
            title: "토큰 유무",
            field: "pt_device_token",
            formatter: function (cell, formatterParams, onRendered) {
                var data = (String)(cell.getValue());
                if(data.length > 5){
                    return "O";
                }else{
                    return "X";
                }
            },
        },
        {
            title: "등급",
            field: "pt_grade",
            visible: false,
        },
        {
            title: "전화번호",
            field: "pt_cellphone",
            formatter: function (cell, formatterParams, onRendered) {
                return formatCellphone(cell.getValue());
            },
        },
        {
            title: "생년월일",
            field: "pt_birth",
        },
        {
            title: "성별",
            field: "pt_gender",
            formatter: "lookup",
            formatterParams: {
                m: "남성",
                w: "여성",
                undefined: "",
            },
        },
        {
            title: "이메일",
            field: "pt_email",
        },
        {
            title: "마케팅 여부",
            field: "pt_marketing_yn",
            formatter: "lookup",
            formatterParams: {
                y: "허용",
                n: "거절",
                undefined: "거절",
            },
        },
        {
            title: "가입일시",
            field: "pt_reg_dt",
        },
        {
            title: "로그인일시",
            field: "pt_login_dt",
        },
        {
            title: "로그인IP",
            field: "pt_login_ip",
        },
    ];

    setTable("partner-list", columns, list, [
        { column: "pt_reg_dt", dir: "asc" },
    ]);
}

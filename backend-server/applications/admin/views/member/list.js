$(function () {
    myFetch(url + "/member/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);

    var columns = [
        {
            title: "번호",
            field: "mb_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(
                    url + "/member/form/" + cell._cell.value,
                    "member-form"
                );
            },
        },
        {
            title: "아이디",
            field: "mb_id",
        },
        {
            title: "상태",
            field: "mb_stt",
            formatter: "lookup",
            formatterParams: {
                1: "승인",
                undefined: "",
            },
        },
        {
            title: "프로필",
            field: "mb_profile_img",
            hozAlign: "center",
            formatter: "image",
            formatterParams: { height: 25 },
        },
        {
            title: "회원명",
            field: "mb_name",
        },
        {
            title: "회원명",
            field: "mb_nickname",
        },
        {
            title: "등급",
            field: "mb_grade",
            visible: false,
        },
        {
            title: "포인트",
            field: "mb_point",
            visible: false,
        },
        {
            title: "토큰 유무",
            field: "mb_device_token",
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
            title: "전화번호",
            field: "mb_cellphone",
            formatter: function (cell, formatterParams, onRendered) {
                return formatCellphone(cell.getValue());
            },
        },
        {
            title: "생년월일",
            field: "mb_birth",
        },
        {
            title: "성별",
            field: "mb_gender",
            formatter: "lookup",
            formatterParams: {
                m: "남성",
                w: "여성",
                undefined: "",
            },
        },
        {
            title: "이메일",
            field: "mb_email",
        },
        {
            title: "마케팅 여부",
            field: "mb_marketing_yn",
            formatter: "lookup",
            formatterParams: {
                y: "허용",
                n: "거부",
                undefined: "",
            },
        },
        {
            title: "가입일시",
            field: "mb_reg_dt",
        },
        {
            title: "로그인일시",
            field: "mb_login_dt",
        },
        {
            title: "로그인IP",
            field: "mb_login_ip",
        },
    ];

    setTable("member-list", columns, list, [
        { column: "mb_reg_dt", dir: "desc" },
    ]);
}

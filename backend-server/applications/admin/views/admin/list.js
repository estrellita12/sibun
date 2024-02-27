$(function () {
    myFetch(url + "/admin/getList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);

    var columns = [
        {
            title: "번호",
            field: "adm_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(url + "/admin/form/" + cell._cell.value, "admin-form");
            },
        },
        {
            title: "아이디",
            field: "adm_id",
        },
        {
            title: "이름",
            field: "adm_name",
        },
        {
            title: "가입일시",
            field: "adm_reg_dt",
        },
        {
            title: "로그인일시",
            field: "adm_login_dt",
        },
        {
            title: "로그인IP",
            field: "adm_login_ip",
        },
    ];

    setTable("admin-list", columns, list, [
        { column: "adm_reg_dt", dir: "desc" },
    ]);
}

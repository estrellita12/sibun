$(function () {
    myFetch(url + "/member/getLeaveList" + location.search, setData);
});

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "mb_leave_idx",
            cellClick: function (e, cell) {
                winOpen(url + "/member/form/" + cell._cell.value, "memberInfo");
            },
        },
        {
            title: "아이디",
            field: "mb_leave_mb_id",
        },
        {
            title: "전화번호",
            field: "mb_leave_mb_cellphone",
        },
        {
            title: "사유",
            field: "mb_leave_reason",
        },
        {
            title: "탈퇴 일시",
            field: "mb_leave_dt",
        },
    ];
    setTable("member-leave-list", columns, list, [
        { column: "mb_leave_dt", dir: "desc" },
    ]);
}

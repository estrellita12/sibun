$(function () {
    myFetch(url + "/review/getList" + location.search, setData);
});
function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "review_idx",
            hozAlign: "center",
            editor: false,
            cellClick: function (e, cell) {
                winOpen(
                    url + "/review/form/" + cell._cell.value,
                    "review-popup"
                );
            },
        },
        {
            title: "매장 번호",
            field: "review_by_store_idx",
        },
        {
            title: "회원 번호",
            field: "review_by_mb_idx",
        },
        {
            title: "제목",
            field: "review_title",
        },
        {
            title: "태그",
            field: "review_tags",
        },
        {
            title: "내용",
            field: "review_content",
            width: 250,
        },
        {
            title: "이미지1",
            field: "review_img1",
            formatter: "image",
            formatterParams: { width: 25 },
            download: false,
        },
        {
            title: "이미지2",
            field: "review_img2",
            formatter: "image",
            formatterParams: { width: 25 },
            download: false,
        },
        {
            title: "이미지3",
            field: "review_img3",
            formatter: "image",
            formatterParams: { width: 25 },
            download: false,
        },
        {
            title: "별점",
            field: "review_rating",
            formatter: "star",
        },
        {
            title: "답변 여부",
            field: "review_answer_yn",
            visible: false,
        },
        {
            title: "답변",
            field: "review_answer",
            width: 250,
        },
        {
            title: "예약번호",
            field: "review_reservation_idx",
        },
        {
            title: "차단여부",
            field: "review_block_yn",
            formatter: "lookup",
            formatterParams: {
                y: "차단",
                n: "",
            },
        },
        {
            title: "등록일시",
            field: "review_reg_dt",
        },
        {
            title: "수정일시",
            field: "review_update_dt",
        },
    ];
    setTable("review-list", columns, list, [
        { column: "review_reg_dt", dir: "asc" },
    ]);
}

$(function () {
    const pathlist = location.pathname.split("/");
    const ident = pathlist[4];
    if (!!ident) {
        myFetch("/admin/storepricing/getList/" + ident, setData);
    }
});
function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "store_pricing_idx",
            editor: false,
            cellClick: function (e, cell) {
                winOpen(
                    url + "/storepricing/form/" + cell._cell.value,
                    "store-pricing-popup"
                );
            },
            width: 70,
        },
        {
            title: "순서",
            field: "store_pricing_orderby",
            visible: false,
        },
        {
            title: "매장 번호",
            field: "store_pricing_by_store_idx",
            visible: false,
        },
        {
            title: "",
            field: "store_pricing_usage",
            visible: false,
        },
        {
            title: "요일",
            field: "store_pricing_days",
        },
        {
            title: "이용시간",
            field: "store_pricing_time",
        },
        {
            title: "이용횟수",
            field: "store_pricing_cnt",
        },
        {
            title: "가격",
            field: "store_pricing_price",
            formatter: function (cell, formatterParams, onRendered) {
                return commaNumber(cell.getValue());
            },
        },
        {
            title: "정렬 순서",
            field: "store_pricing_orderby",
            visible: false,
        },
        {
            title: "사용 여부",
            field: "store_pricing_use_yn",
            formatter: "lookup",
            formatterParams: {
                y: "사용",
                n: "미사용",
            },
        },
        {
            title: "등록일시",
            field: "store_pricing_reg_dt",
        },
    ];
    setTable("store-pricing-list", columns, list, [
        { column: "store_pricing_orderby", dir: "asc" },
    ]);
}

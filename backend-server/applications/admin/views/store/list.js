var ctgList;
$(function () {
    myFetch("/admin/store/ctgList", setCategory);
    myFetch("/admin/store/getList" + location.search, setData);
});

function setCategory(ctg) {
    ctgList = ctg;
    //   myFetch("/admin/store/getList", setData);
}

function setData(list) {
    $("#cnt").html(list.length);
    var columns = [
        {
            title: "번호",
            field: "store_idx",
            hozAlign: "center",
            cellClick: function (e, cell) {
                winOpen(url + "/store/form/" + cell._cell.value, "store-form");
            },
        },
        {
            title: "가맹점",
            field: "store_pt_idx",
        },
        {
            title: "카테고리",
            field: "store_ctg_idx",
            formatter: "lookup",
            formatterParams: ctgList,
        },
        {
            title: "상태",
            field: "store_stt",
            visible: false,
        },
        {
            title: "이미지",
            field: "store_main_simg",
            formatter: "image",
            formatterParams: {
                height: 25,
            },
            hozAlign: "center",
            download: false,
        },
        {
            title: "매장명",
            field: "store_name",
        },
        {
            title: "전화번호",
            field: "store_tel",
        },
        {
            title: "우편번호",
            field: "store_addr_zip",
        },
        {
            title: "주소",
            field: "store_addr",
        },
        {
            title: "경도",
            field: "store_addr_x",
        },
        {
            title: "위도",
            field: "store_addr_y",
        },
        {
            title: "영업 시작 시간",
            field: "store_open_time",
            formatter: function (cell, formatterParams, onRendered) {
                return formatTime(cell.getValue() * 30);
            },
        },
        {
            title: "영업 종료 시간",
            field: "store_close_time",
            formatter: function (cell, formatterParams, onRendered) {
                return formatTime(cell.getValue() * 30);
            },
        },
        {
            title: "휴무 요일",
            field: "store_closed_days",
        },
        {
            title: "휴무 공지",
            field: "store_closed_notice",
        },
        {
            title: "할인권 사용 여부",
            field: "store_voucher_use_yn",
            formatter: "lookup",
            formatterParams: {
                y: "사용",
                n: "미사용",
            },
        },
        {
            title: "편의시설",
            field: "store_amenities",
        },
        {
            title: "이미지",
            field: "store_idx",
            hozAlign: "center",
            formatter: function (cell, formatterParams, onRendered) {
                return "<button type='button' class='btn-small btn-white'>관리</button>";
            },
            cellClick: function (e, cell) {
                winOpen(
                    url + "/storeimage/list/" + cell._cell.value,
                    "store-popup"
                );
            },
            download: false,
        },
        {
            title: "가격",
            field: "store_idx",
            hozAlign: "center",
            formatter: function (cell, formatterParams, onRendered) {
                return "<button type='button' class='btn-small btn-white'>관리</button>";
            },
            cellClick: function (e, cell) {
                winOpen(
                    url + "/storepricing/list/" + cell._cell.value,
                    "store-popup"
                );
            },
            download: false,
        },
        {
            title: "방",
            field: "store_idx",
            hozAlign: "center",
            formatter: function (cell, formatterParams, onRendered) {
                return "<button type='button' class='btn-small btn-white'>관리</button>";
            },
            cellClick: function (e, cell) {
                winOpen(
                    url + "/storeroom/list/" + cell._cell.value,
                    "store-popup"
                );
            },
            download: false,
        },
        {
            title: "할인권",
            field: "store_idx",
            hozAlign: "center",
            formatter: function (cell, formatterParams, onRendered) {
                return "<button type='button' class='btn-small btn-white'>관리</button>";
            },
            cellClick: function (e, cell) {
                winOpen(
                    url + "/storevoucher/list/" + cell._cell.value,
                    "store-popup"
                );
            },
            download: false,
        },
        {
            title: "등록일시",
            field: "store_reg_dt",
        },
        {
            title: "수정일시",
            field: "store_update_dt",
        },
    ];
    setTable("store-list", columns, list, [
        { column: "store_reg_dt", dir: "desc" },
    ]);
}

$(function () {
    $("#frm-data").attr("action", url + "/store/add");
    const pathlist = location.pathname.split("/");
    const ident = pathlist[4];
    if (!!ident) {
        myFetch("/admin/store/get/" + ident, setData);
        $("#frm-data").attr("action", url + "/store/set/" + ident);
        $("#frm-submit").val("수정");
    }
});

function setData(data) {
    loadData(data);

    var storeIdx = $("input[name='store_pt_idx']").val();
    myFetch("/admin/partner/get/" + storeIdx, setStore);
}
function setStore(data) {
    console.log(data);
    var name = data.pt_name + "(" + data.pt_id + ")";
    $("#store-name").val(name);
}

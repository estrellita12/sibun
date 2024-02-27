function storeDescPopup(type) {
    var idx = $("input[name='store_idx']").val();
    if (!!!idx) return;
    console.log(type, idx);
    winOpen(url + "/store" + type + "/list/" + idx, type + "-popup");
}

$(function () {
    setFormData("store", setData);
});

function setData(data) {
    loadData(data);
    var storeIdx = $("input[name='store_pt_idx']").val();
    myFetch("/admin/partner/get/" + storeIdx, setStore);
}

function setStore(data) {
    var name = data.pt_name + "(" + data.pt_id + ")";
    $("#store-name").val(name);
}

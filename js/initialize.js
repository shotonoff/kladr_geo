var ZPayment = ZPayment || {};
ZPayment.Map = ZPayment.Map || {};
ZPayment.Location = ZPayment.Location || {};

ZPayment.init = function () {
    ZPayment.Map = new ymaps.Map("zpaymnet-map", {
            center:[ZPayment.location.lat, ZPayment.location.lng],
            zoom:14
        }
    );
    ZPayment.Map.controls.add("zoomControl");
    ZPayment.Map.controls.add("mapTools");
}

ZPayment.geosearch = function (point) {
    var pointGeocoder = ymaps.geocode(point,
        {
            json:true,
//            kind:'house',
            results:1,
//            boundedBy:ZPayment.Map.getBounds(),
            strictBounds:true
        });

    return pointGeocoder;
}

$(function () {
    ymaps.ready(ZPayment.init);
});

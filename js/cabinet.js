/** reference initialize.js */
$(function () {
    ymaps.ready(function () {
        var button = new ymaps.control.Button({
            data:{
                content:'Показать место',
                title:'Укажите ваше место'
            }
        });
        ZPayment.Map.controls.add(button, {top:5, right:5});

        ZPayment.Map.controls.add("typeSelector");

        ZPayment.Map.events.add('click', function (e) {
            if (button.isSelected()) {
                button.deselect();

                ZPayment.Map.geoObjects.remove(ZPayment.Location);
                ZPayment.Location = new ymaps.Placemark(e.get("coordPosition"));
                ZPayment.Map.geoObjects.add(ZPayment.Location);
                ZPayment.geosearch(e.get("coordPosition")).then(
                    function (res) {
                        if (res.GeoObjectCollection.featureMember.length > 0)
                            $("[name=location]").val(res.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.text);
                    }
                );
            }
        });

        if ($("[name=location]").val() != '')
            $("[name=btn-search]").trigger('click');

    });

    $("[name=btn-search]").on('click', function (e) {

        var searchStr = $("[name=location]").val().split(" ").join("+");
        var myGeocoder = ymaps.geocode(
            searchStr, {
                strictBounds:false,
                results:1
            }
        );
        myGeocoder.then(
            function (res) {
                ZPayment.Map.geoObjects.remove(ZPayment.Location);
                ZPayment.Location = res.geoObjects.get(0);
                ZPayment.Map.geoObjects.add(ZPayment.Location);
                ZPayment.Map.panTo(ZPayment.Location.properties.get("boundedBy"), {flying:true});
//                ZPayment.Map.panTo(res.geoObjects.getIterator().getNext().properties.get("boundedBy"), {flying:true});
            }
        );
    });

    $("#loading").ajaxStart(function () {
        $(this).show();
    }).ajaxStop(function () {
            $(this).hide();
        });

    ZPayment.AutocompleteParams = {
        focusElement:null,
        street:{
            type:'street',
            cacheItems:[],
            selectCode:null
        },
        home:{
            type:'home',
            cacheItems:[],
            selectCode:null
        },
        zip:{
            type:'zip',
            cacheItems:[],
            selectCode:null
        }
    };

    var o = $(".autobox").autoboxselect({url:'autobox.php', completeCallback:function (e) {
//      var geolocation = new Array('Россия');
        $(e.target).parents('form').find('select').not(':last').each(function (index, el) {
//            geolocation.push($(el).text());
        })
    }});

    $("[name=street], [name=home]").focus(function (event) {
        ZPayment.AutocompleteParams.focusElement = $(this);
    }).autocomplete('autocomlete.php', {
            delay:10,
            width:300,
            zIndex:9999,
            dataType:"json",
            max: 99,
            extraParams:{
                type:function () {
                    return ZPayment.AutocompleteParams[ZPayment.AutocompleteParams.focusElement.attr('name')].type;
                },
                code:function () {
                    switch (ZPayment.AutocompleteParams.focusElement.attr('name')) {
                        case 'street':
                            return $("select[name=CITYCODE]").find(":selected").val();
                            break;
                        case 'home':
                            return ZPayment.AutocompleteParams["street"].selectCode;
                            break;
                    }
                }
            },
            parse:function (data) {
                ZPayment.AutocompleteParams[ZPayment.AutocompleteParams.focusElement.attr('name')].cacheItems = data;
                return $.map(data, function (row) {
                    return {
                        data:row,
                        value:row.code,
                        result:row.name
                    }
                });
            },
            formatItem:function (item) {
                if (item.name != undefined)
                    return item.name.toString();
                return null;
            }
        }).result(function (e) {
            $.each(ZPayment.AutocompleteParams[$(e.target).attr('name')].cacheItems, function (index, el) {
                if (el.name == $(e.target).val()) {
                    ZPayment.AutocompleteParams[$(e.target).attr('name')].selectElement = el.code;
                    ZPayment.AutocompleteParams[$(e.target).attr('name')].selectCode = el.code;
                }
            });
        });

    $("[name=home]").blur(function () {
        if (ZPayment.AutocompleteParams['street'].selectCode == undefined || ZPayment.AutocompleteParams['street'].selectCode == null)
            return false
        $.ajax({
            url:'autocomlete.php',
            dataType:'json',
            data:{
                q:$(this).val(),
                type:'zip',
                code:ZPayment.AutocompleteParams['street'].selectCode
            },
            success:function (response) {
                if (response.zip != undefined)
                    $("[name=zip]").val(response.zip);
            }
        });

    });

    $("#delivery-calculate").ajaxForm({
        dataType:'html',
        success:function (response) {
            $("#delivery-calculate").find(".calc-response").html(response);
        }
    });

});

$.fn.autoboxselect = function (_options) {
    var self = $(this);
    var options = {};
    var dfr = null;

    var methods = {
        init:function () {
            (typeof _options == 'object') && $.extend(options, _options);

            $(this).on('change', function (e) {
                /** e.target instaceof HTMLSelectElement */
                var q = $(e.target).serialize();

                methods.load(q).then(function (response) {

                    if (!response instanceof Array) {
                        response = [response];
                    }

                    for (var i = 0; i < response.length; i++)
                        self.find(response[i].wrapElement).html(response[i].view);
                    options.completeCallback.call(this, e);
                });
            });
            return this;
        },
        load:function (q) {
            return $.ajax(
                {
                    url:options['url'],
                    dataType:'json',
                    type:'post',
                    data:q,
                    beforeSend:function () {
                        self.find("select").each(function (index, item) {
                            $(item).attr("disabled", "disabled");
                        });
                    },
                    complete:function () {
                        self.find("select").each(function (index, item) {
                            $(item).removeAttr("disabled");
                        });
                    }
                }
            )
        }
    }

    if (_options == undefined || typeof _options == 'object')
        method = "init";
    else if (typeof _options == 'string') {
        method = _options;
    } else
        throw "метод не может принимать такое значение  " + method;

    return methods[method].call(this);
}

var autocomplete = function () {

}

$.fn.kaldr_autocomplete = function (options) {
    var dfr = $.Deferred();
    dfr.done(function (e) {
        $.ajax({
            url:options.url,
            dataType:options.dataType

        });
    });

    options = $.extend({
        success:function () {
        },
        error:function () {
        }
    }, options);

    $(this).keypress(function (e) {
        dfr.resolve();
    });
}
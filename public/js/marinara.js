const tabs= {
    'users': 'пользователи',
    'storages': 'хранение',
};

const storagesData= {
//    total:12,
    rows:[
        {name:"ПІБ",value:"",group:"Послуг зі зберігання",editor:"text"},
        {name:"Дод. ПІБ",value:"",group:"Послуг зі зберігання",editor:"text"},
        {name:"Телефон",value:"",group:"Послуг зі зберігання",editor:"text"},
        {name:"Дод. телефон",value:"",group:"Послуг зі зберігання",editor:"text"},
        {name:"Авто",value:"",group:"Послуг зі зберігання",editor:"text"},
        {name:"Термін зберігання",value:"",group:"Послуг зі зберігання",editor:"datebox"},
        {name:"Оплата",value:"",group:"Послуг зі зберігання",editor:"text"},
        {name:"Сплачено",value:false,group:"Послуг зі зберігання",editor:{
                type:"checkbox",
                options:{
                    on:true,
                    off:false
                }
            }},
        {name:"Категорія",value:"Шини",group:"Опис",editor:"text"},
        {name:"Найменування",value:"",group:"Опис",editor:"text"},
        {name:"Дод. інфо",value:"","group":"Опис",editor:{
                type:"textbox",
                options:{
                    multiline:true,
                    height:50
                }
            }},
        {name:"К-сть",value:"0",group:"Опис",editor:"numberbox"},
    ]};

$.extend($.fn.textbox.methods, {
    addClearBtn: function (jq, iconCls) {
        return jq.each(function () {
            var t = $(this);
            var opts = t.textbox('options');
            opts.icons = opts.icons || [];
            opts.icons.unshift({
                iconCls: iconCls,
                handler: function (e) {
                    $(e.data.target).textbox('clear').textbox('textbox').focus();
                    $(this).css('visibility', 'hidden');
//                    media_search(); // reload list with empty search param
                    let controller= $(e.data.target).attr('data-controller');
                    search(controller, e.data.target.value);
                }
            });
            t.textbox();
            if (!t.textbox('getText')) {
                t.textbox('getIcon', 0).css('visibility', 'hidden');
            }
            t.textbox('textbox').bind('keyup', function () {
                var icon = t.textbox('getIcon', 0);
                if ($(this).val()) {
                    icon.css('visibility', 'visible');
                } else {
                    icon.css('visibility', 'hidden');
                }
            });
        });
    }
});

function messageDecode(data){
    let content= '';
    if(isJson(data)) data= JSON.parse(data)
    for (let key in data) {
        content+= data[key][0]+ "<br>";
    }
    return content;
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

// function submitForm(){
//     $('#login_form').form('submit');
// }
function loginClearForm(){
    $('#login_form').form('clear');
}

function storagesList(){
}

function storages2Back(data= {}){
    let row= {};
    let comparison= {
        "ПІБ": 'name',
        "Дод. ПІБ": 'name_alt',
        "Телефон": 'phone',
        "Дод. телефон": 'phone_alt',
        "Авто": 'car_info',
        "Термін зберігання": 'storage_time',
        "Оплата": 'price',
        "Сплачено": 'paid',
        "Категорія": 'descr_category',
        "Найменування": 'descr_name',
        "Дод. інфо": 'descr_notise',
        "К-сть": 'descr_amount',
    };

    for (let i in data.rows) {
        let key= data.rows[i].name;
        let k= comparison[key];
        row[k]= data.rows[i].value;
    }

return row;
}
function storagesMutator(row, data= {}){
//row.data.name= data.rows[0].value;
    row.name=           data[0].value;
    row.name_alt=       data[1].value;
    row.phone=          data[2].value;
    row.phone_alt=      data[3].value;
    row.car_info=       data[4].value;
    row.storage_time=   data[5].value;
    row.price=          data[6].value;
    row.paid=           data[7].value;
    row.descr_category= data[8].value;
    row.descr_name=     data[9].value;
    row.descr_notise=   data[10].value;
    row.descr_amount=   data[11].value;

    return row;
}

function storagesAcessor(row, data= {}){
//data.rows[0].value=  row.data.name;
    data[0].value=   row.name;
    data[1].value=   row.name_alt;
    data[2].value=   row.phone;
    data[3].value=   row.phone_alt;
    data[4].value=   row.car_info;
    data[5].value=   row.storage_time;
    data[6].value=   row.price;
    data[7].value=   row.paid;
    data[8].value=   row.descr_category;
    data[9].value=   row.descr_name;
    data[10].value=  row.descr_notise;
    data[11].value=  row.descr_amount;

    return data;
}

function storagesGet(id= 0, data= ''){
    if(id){
        $.ajax({
            type: 'get',
            url: '/api/storages/'+ id,
//            data: param,
            dataType: 'json',
            beforeSend: function (xhr) {
                let bearer= $('#userBearer').val();
                xhr.setRequestHeader('Authorization', 'Bearer '+ bearer);
            },
            success: function(response){
//data.rows[0].value=  row.data.name;
                data.rows= storagesAcessor(response.data, data.rows);
                data.total= data.rows.length;
                $("#storages_item").propertygrid('loadData', data);
                $('#storages_item-button_save').linkbutton('enable');
            },
            error: function(){ error.apply(this, arguments); }
        });
    }
    else{
        for (let key in data.rows) {
            data.rows[key].value = '';
        }

        $("#storages_item").propertygrid('loadData', data);
    }
}

function storagesDel(){
    row = $('#storages_list').datagrid('getSelected');
    console.log(row);
}

function storagesSave(){
    let id= 0;
    let url= '/api/storages/';
    let method= 'post';
    let row = $('#storages_list').datagrid('getSelected');
    let data= $('#storages_item').propertygrid('getData');
    data= storages2Back(data);

    if(row.id) {
        method= 'put';
        url += row.id;
    }

    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: 'json',
        beforeSend: function (xhr) {
            let bearer = $('#userBearer').val();
            xhr.setRequestHeader('Authorization', 'Bearer ' + bearer);

            console.log(data);
        },
        complete: function(jqXHR, textStatus) {
//            console.log(jqXHR.status);
        },
        success: function (response) {
            console.log(response);
        },
        error: function(jqXHR, textStatus, errorThrown){
            let response= JSON.parse(jqXHR.responseText);

            $.messager.alert(textStatus+ " status: "+ jqXHR.status, messageDecode(response.message), textStatus);
//            console.log("exception: "+ textStatus+ ", error: "+ jqXHR.responseText+ ", status:"+ jqXHR.status);
            console.log(JSON.parse(response.message));
        },
    });

//    console.log(row);
}

function storagesEdit(row= ''){
    let data= {rows:[]};
    let id= 0;

// let data = Object.assign({}, storagesData);
    for (let key in storagesData.rows) {
        data.rows[key] = storagesData.rows[key];
    }
    data.total= data.rows.length;

    if(row.id){
        $('#storages_list-button_del').linkbutton('enable');
        id = row.id;
    }

    storagesGet(id, data);
}

function mainTabAdd(tab){
    if(tabs[tab]){
        let title= tabs[tab];
        if($('#mainTabs').tabs('exists', title)){
            $('#mainTabs').tabs('select', title)
        }
        else{
            $('#mainTabs').tabs('add', {
                title: title,
                href: '/' + tab,
                closable: true,
                plain: true,
                border: false,
                onLoad: function(title){
                    $('#'+ tab+ '_search').textbox().textbox('addClearBtn', 'icon-clear');
                }
            });
        }
    }
}

function search(route, value){
    $('#'+ route+ '_list').datagrid('load', {
        search: $('#'+ route+ '_search').val(),
    });
}
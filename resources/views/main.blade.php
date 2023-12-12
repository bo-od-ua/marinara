@extends('layouts.app')

@section('content')

<div id="mainLayout" class="easyui-layout" style="height:250px;" data-options="fit:true">
    <input id="userBearer" type="hidden" value="{{Auth::user()->bearer}}">
    <div region="north" style="height:36px;">
        <div style="padding:2px 5px;">
<!--				<a href="#" id="notesLinkButtonSave" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-save',toggle:false,disabled:true" onclick="getJSON();">Save</a>-->
            <a href="#" class="easyui-menubutton" data-options="menu:'#mainUserSubMenu',iconCls:'icon-help'" style="float:right;">
                {{Auth::user()->email}}
            </a>
        </div>
        <div id="mainUserSubMenu" style="width:100px;">
<!--
            <div onclick="$('#notesUserProfile').window('open')">edit profile</div>
            <div class="menu-sep"></div>
-->
            <div>logout</div>
        </div>
    </div>
    <div data-options="region:'west',split:true" style="width:20%">

        <div class="easyui-accordion" style="width:100%;">
            <div title="Резина" style="overflow:auto;padding:10px;">
                <a href="javascript:void(0)" onclick="mainTabAdd('storages')">хранение</a>
            </div>
            <div title="настройки" style="overflow:auto;padding:10px;">
                <a href="javascript:void(0)" onclick="mainTabAdd('users')">пользователи</a>
            </div>
        </div>

    </div>
    <div region="center" style="padding:5px;">
        <div id="mainTabs" class="easyui-tabs" style="width:100%;height:100%;"></div>
    </div>
</div>

<script>
const tabs= {
    'users': 'пользователи',
    'storages': 'хранение',
}

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

function storagesList(){
}

function storagesGet(id= '', data= ''){

    if(id){
        $.ajax({
            type: 'get',
            url: '/api/storages'+ id,
            data: param,
            dataType: 'json',
            beforeSend: function (xhr) {
                let bearer= $('#userBearer').val();
                xhr.setRequestHeader('Authorization', 'Bearer '+ bearer);
            },
            success: function(data){ success(data); },
            error: function(){ error.apply(this, arguments); }
        });
    }
    else{
        $("#storages_item").propertygrid('loadData', data);
    }
}

function storagesEdit(row= ''){
    let id= '';
    let data= {
        total:11,
        rows:[
            {name:"ПІБ",value:"",group:"Послуг зі зберігання",editor:"text"},
            {name:"Дод. ПІБ",value:"",group:"Послуг зі зберігання",editor:"text"},
            {name:"Телефон",value:"",group:"Послуг зі зберігання",editor:"text"},
            {name:"Дод. телефон",value:"",group:"Послуг зі зберігання",editor:"text"},
            {name:"Авто",value:"",group:"Послуг зі зберігання",editor:"text"},
            {name:"Термін зберігання",value:"",group:"Послуг зі зберігання",editor:"datebox"},
            {name:"Сплачено",value:false,group:"Послуг зі зберігання",editor:{
                type:"checkbox",
                options:{
                    on:true,
                    off:false
                }
            }},
            {name:"Категорія",value:"Шини",group:"Опис",editor:"text"},
            {name:"Найменування",value:"",group:"Опис",editor:"text"},
            {name:"Дод. інфо",value:"",group:"Опис",editor:"text"},
            {name:"К-сть",value:"0",group:"Опис",editor:"numberbox"},
            {name:"Нотатки",value:"","group":"Опис",editor:{
                type:"textbox",
                options:{
                    multiline:true,
                    height:50
                }
            }}
        ]};

//    if(row.id) id= row.id;

    storagesGet(id, data);

    console.log(row);
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
</script>

@endsection

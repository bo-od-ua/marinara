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
            <div onclick="window.location.href='/out';">logout</div>
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
</script>

@endsection

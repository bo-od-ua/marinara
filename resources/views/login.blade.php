@extends('layouts.app')

@section('content')
<form id="login_form" method="POST" action="/login">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="welcome-form">
        <div style="margin-bottom:20px">
            <input class="easyui-textbox" prompt="Username" iconWidth="28" style="width:100%;height:34px;padding:10px;" name="email">
        </div>
        <div style="margin-bottom:20px">
            <input class="easyui-passwordbox" prompt="Password" iconWidth="28" style="width:100%;height:34px;padding:10px" name="password" >
        </div>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()" style="width:80px">Submit</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearForm()" style="width:80px">Clear</a>
        </div>
    </div>
</form>

<script>
    function submitForm(){
        $('#login_form').form('submit');
    }
    function clearForm(){
        $('#login_form').form('clear');
    }
</script>
@endsection

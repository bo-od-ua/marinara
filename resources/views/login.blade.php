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
            <input class="easyui-linkbutton l-btn-text" type="submit" value="Submit" data-options="width:'70px', height:'30px'">
{{--            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()" style="width:80px">Submit</a>--}}
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="loginClearForm()" style="width:80px">Clear</a>
        </div>
    </div>
</form>

<script>
</script>
@endsection

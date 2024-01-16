@php
$styleTDname=  'border: 1px solid #000000;width:30%;padding:5px;';
$styleTDvalue= 'border: 1px solid #000000;padding:5px;';
$styleID= 'font-size:95px;font-weight:bold;';
@endphp
<!DOCTYPE HTML>

<html lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>blank</title>
</head>
<style>
    body {
        font-family: DejaVu Sans;
        font-size:12px;
    }
</style>
<body>
<table style="width:100%;" cellspacing="0">
	<tr>
		<td style="width:50%;border: 0px solid #000000;">
			<img src="{{public_path().'/logo.png'}}">
		</td>
		<td style="border: 0px solid #000000;text-align:center;">
			Шинный центр<br>
			ул. Люстдорфская дорога 121/1а<br>
			тел. (097) 217-63-61<br>
		</td>
	</tr>
	<tr>
		<td style="border: 0px solid #000000;text-align:center;" colspan="2">
			<b>АКТ ПРИЕМА-ПЕРЕДАЧИ №{{$storage->article}}</b>
			<p style="padding-bottom:10px;">услуг по хранению</p>
			<table style="width:100%;text-align:left;" cellspacing="0">
				<tr>
					<td style="{{$styleTDname}}">ФИО</td>
					<td style="{{$styleTDvalue}}">{{$storage->name}}</td>
				</tr>
				<tr>
					<td style="{{$styleTDname}}">телефон</td>
					<td style="{{$styleTDvalue}}">{{$storage->phone}}</td>
				</tr>
				<tr>
					<td style="{{$styleTDname}}">машина</td>
					<td style="{{$styleTDvalue}}">{{$storage->car_info}}</td>
				</tr>
				<tr>
					<td style="{{$styleTDname}}">доп. ФИО</td>
					<td style="{{$styleTDvalue}}">{{$storage->name_alt}}</td>
				</tr>
				<tr>
					<td style="{{$styleTDname}}">доп. телефон</td>
					<td style="{{$styleTDvalue}}">{{$storage->phone_alt}}</td>
				</tr>
				<tr>
					<td style="{{$styleTDname}}">срок хранения</td>
					<td style="{{$styleTDvalue}}">{{$storage->storage_time}}</td>
				</tr>
				<tr>
					<td style="{{$styleTDname}}">сумма</td>
					<td style="{{$styleTDvalue}}">{{$storage->price}}</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="border: 0px solid #000000;padding:5px;" colspan="2">
			<b>описание</b>
			<table style="width:100%;" cellspacing="0">
				<tr>
					<td style="border: 1px solid #000000;padding:10px;">категория</td>
					<td style="border: 1px solid #000000;padding:10px;">наименование</td>
					<td style="border: 1px solid #000000;padding:10px;">доп. инфо</td>
					<td style="border: 1px solid #000000;padding:10px;">кол-во</td>
				</tr>
				<tr>
					<td style="border: 1px solid #000000;padding:10px;width:20%">{{$storage->descr_category}}</td>
					<td style="border: 1px solid #000000;padding:10px;width:40%">{{$storage->descr_name}}</td>
					<td style="border: 1px solid #000000;padding:10px;;width:30%">{{$storage->descr_notise}}</td>
					<td style="border: 1px solid #000000;padding:10px;;width:10%">{{$storage->descr_amount}}</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr><td style="padding:5px;" colspan="2">&nbsp;</td></tr>

	<tr>
		<td style="width:50%;border: 0px solid #000000;padding:10px;">
			Дата сдачи __________________________
		</td>
		<td style="border: 0px solid #000000;text-align:left;padding:10px;">
			Подпись клиента __________________________
		</td>
	</tr>

	<tr><td style="padding:5px;" colspan="2">
            <p>Обращаем внимание, что крайне желательной является предварительная запись. Это позволит сохранить Ваше время, избежав очереди, и ускорить процесс приемки резины на хранение.</p>

            <p>Хранитель не несет ответственность за утрату, недостачу или повреждение, автошин и дисков, если они произошли вследствие непреодолимых сил (форс-мажор).</p>

    </td></tr>
</table>
<br>
<table style="width:100%;text-align:center;padding:5px;">
	<tr>
		<td style="border: 1px dashed #000000;">
			<span style="{{$styleID}}">№{{$storage->article}}</span>
		</td>
        <td style="border: 1px dashed #000000;">
            <span style="{{$styleID}}">№{{$storage->article}}</span>
        </td>
	</tr>
	<tr>
		<td style="border: 1px dashed #000000;">
			<span style="{{$styleID}}">№{{$storage->article}}</span>
		</td>
        <td style="border: 1px dashed #000000;">
            <span style="{{$styleID}}">№{{$storage->article}}</span>
        </td>
	</tr>
</table>

</body>

</html>

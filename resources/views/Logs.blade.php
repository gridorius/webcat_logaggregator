<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Логи</title>
    <link rel="stylesheet" href="/public/logs.css">
  </head>
  <body>
    <center>
      <h1> Логи </h1>
    </center>
    <form  method="post">
      @csrf
      <input type="date" name="from">
      <input type="date" name="to">
      Группировать по
      <select name="group">
        <option value="ip">по ip</option>
        <option value="date">по дате</option>
      </select>
      <input type="submit" value="обновить">
      <br><br><br>
    </form>
    <table class="logs">
      <tr>
        @if(empty($group))
        <th>Ip</th>
        <th>Дата</th>
        <th>Метод</th>
        <th>Пользовательский клиент</th>
        @elseif($group == 'ip')
        <th>Ip</th>
        <th>Запросов</th>
        @else
        <th>Дата</th>
        <th>Запросов</th>
        @endif
      </tr>
      @foreach($logs as $log)
      <tr>
        <td>{{$log->ip}}</td>
        <td>{{$log->date}}</td>
        <td>{{$log->method}}</td>
        <td>{{$log->user_agent}}</td>
      </tr>
      @endforeach
    </table>
  </body>
</html>

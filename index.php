<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widht=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <title>Получение данных о курсе валют</title>
</head>
<body>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Тестовое задание часть 1</h5>
</div>
<div class="container ml-5" >
    <form id="getData" method="get" style="width: 500px;">
        <h3>Получить данные о курсе валют</h3><br>
        <div class="form-group">
            <button id="getDataButton" type="submit" name="getData" class="btn btn-success" >Получить данные о курсе валют</button>
        </div>
    </form>
    <div class="table-responsive"  id="dataRate">

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js" integrity="sha256-sPB0F50YUDK0otDnsfNHawYmA5M0pjjUf4TvRJkGFrI=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        //событие нажатия кнопки
        $('#getData').on('submit',function (event) {
            event.preventDefault();
            //выключаем кнопку и изменяем ее тектс
            $('#getDataButton').attr("disabled", true);
            $('#getDataButton').text("Подождите идет получение данных...");

            //получаем данные не обновляя страницу
            $.ajax({
                url:'getData.php',
                method: 'GET',
                success: function (data) {
                    //выводим полученные данные от php скрипта getData.php
                    $('#dataRate').html(data);
                    //возвращаем кнопку в исходное состояние
                    $('#getDataButton').text("Получить данные о курсе валют");
                    $('#getDataButton').attr("disabled", false);
                }
            })
        })
    })
</script>
</body>
</html>
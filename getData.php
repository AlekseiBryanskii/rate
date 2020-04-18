<?php
//соединение с БД
require_once 'config.php';

//получаем данные о всех валютах
$valutes = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?date_req=16/04/2020') or die;

//получаем сегодняшнюю дату и дату - 29 дней
$dateEnd = new DateTime();
$dateStart = $dateEnd->modify('-29 day');
$dateEnd = new DateTime();

//подготавливаем запрос
$sql = "INSERT INTO currency (valuteID, numCode, charCode, name, value, date) VALUES (?,?,?,?,?,?)";
$query = $pdo->prepare($sql);

//проверяем корректность xml данных
if (!$valutes)
{
    echo 'Ошибка получение данных....';
}
else
{
    //Подготавливаем таблицу для отображения полученных данных
    echo
    '<table id="tableDataRate" class="table table-sm table-dark" border="1">
            <thead >
                <tr>
                    <th>ID валюты</th>
                    <th>Числовой код</th>
                    <th>Буквенный код</th>
                    <th>Название валюты</th>           
                    <th>Курс</th>  
                    <th>Дата публикации</th>           
                </tr>
            </thead>
        <tbody>';

    //Формируем данные в цикле для записи в бд за 30 дней
    foreach ($valutes->Valute as $val)
    {
        //получаем даннеы о валюте
        $valuteID = $val->attributes()->ID;
        $numCode = $val->NumCode;
        $charCode = $val->CharCode;
        $name = $val->Name;

        //получаем данные об изменениях курса валют за 30 дней по ID валюты
        $kursValute = simplexml_load_file('http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=' . $dateStart->format('d/m/Y') . '&date_req2=' . $dateEnd->format('d/m/Y') . '&VAL_NM_RQ='. $valuteID) or die;

        //проверяем корректность xml данных
        if (!$kursValute)
        {
            echo 'Ошибка получение данных....';
        }
        else
        {
            foreach ($kursValute->Record as $kursDates)
            {
                //получаем данные о валюте
                $date = $kursDates->attributes()->Date;
                $date = strtotime($date);
                $date = date('Y-m-d', $date);
                $value = $kursDates->Value;
                $value = floatval(str_replace(',','.',$value));

                //выполняем запись в БД
                $query->execute([$valuteID, $numCode, $charCode, $name, $value, $date]);

                //формируем строки таблицы
                echo '<tr>' .
                    '<td>' . $valuteID  . '</td>' .
                    '<td>' . $numCode . '</td>' .
                    '<td>' . $charCode . '</td>' .
                    '<td>' . $name .'</td>' .
                    '<td>' . $value .'</td>' .
                    '<td>' . $date .'</td>' .
                    '</tr>';
            }
        }
    }

    //закрываем таблицу
    echo '
        </tbody>
        </table>';
}

?>
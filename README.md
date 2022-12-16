# Train Route PHP Test Task

## Usage

1. Clone: `git clone https://bitbucket.org/atoumus/test_php_train_route.git && cd ./test_php_train_route`
1. Install PHP dependencies: `composer install`
1. Install JS/CSS dependencies: `npm install --prefix ./public`
1. Run tests: `vendor/bin/phpunit`
1. Run PHP built-in server: `php -S 0.0.0.0:8000 -t public ./public/index.php`
1. Go to browser: http://{your-ip-address}:8000

## Task Description

1. Создать web-приложение которое по входным параметрам: номер поезда, станциям
   отправления/прибытия и дате, показывает маршрут движения поезда.
1. Основной функционал приложения должен:<br/>
   a. реализовывать первый экран приложения с формой для ввода входных
   данных: поле для ввода номера поезда, станции отправления, станции
   прибытия, дату и месяц отправления;<br/>
   b. валидировать входные данные;<br/>
   c. отправлять данные на бэкенд используя Ajax-запрос;<br/>
   d. получать ответ от бэкенда;<br/>
   e. обрабатывать и выводить на страницу сообщения об ошибках бэкенда (без
   перезагрузки страницы);<br/>
   f. обрабатывать и выводить на страницу маршрут следования поезда (без
   перезагрузки страницы).<br/>
1. Бэкенд приложения (PHP) должен:<br/>
   a. реализовать приём входных данных от фронтенда;<br/>
   b. валидировать входные данные;<br/>
   c. отправлять данные на веб-сервис
   https://test-api.starliner.ru/Api/connect/Soap/Train/1.1.0?wsdl используя
   протокол SOAP;<br/>
   d. получать и обрабатывать ответ от веб-сервиса и передавать данные на
   фронтенд;<br/>
   e. обрабатывать ошибки и транслировать их на фронтенд;<br/>
   f. логировать свои действия;<br/>
1. Написать unit тесты, используя phpUnit или Codeception, проверяющие работу
   бэкенда.

Если проект использует внешние библиотеки они должны быть подключены к проекту
через composer и npm.

### Описание железнодорожного веб-сервиса

WSDL: https://test-api.starliner.ru/Api/connect/Soap/Train/1.1.0?wsdl <br/>
Метод отдающий маршрут поезда: `trainRoute`

### Входные параметры

**Для аутентификации (auth):**<br/>
login: test<br/>
psw: bYKoDO2it<br/>
terminal: htk_test<br/>
represent_id: 22400<br/>
access_token - не используется.

**Запроса расписания**<br/>
train: номер поезда. Например: 016А, 020У (Из Москвы). 019У 037А (Из Питера)

**TravelInfo**<br/>
from: Станция отправления. Например: Санкт-Петербург, Москва<br/>
to: Станция прибытия<br/>
day: Дата отправления<br/>
month: Месяц отправления<br/>
Остальные параметры (time_dep, time_sw, time_from и time_to) использовать не надо.

### Выходные параметры

Пример ответа:
```xml
<return>
   <ns1:train_description> <!--Описание поезда-->
      <ns1:number>016А</ns1:number> <!--Номер поезда-->
      <ns1:name/> <!--Название поезда-->
      <ns1:from>МОСКВА ОКТ</ns1:from> <!--Станция отправления-->
      <ns1:to>МУРМАНСК</ns1:to> <!--Станция назначения-->
      <ns1:departure_date xsi:nil="true"/>
      <ns1:arrival_date xsi:nil="true"/>
      <ns1:travel_time xsi:nil="true"/>
      <ns1:distance xsi:nil="true"/>
      <ns1:schedule xsi:nil="true"/>
      <ns1:category xsi:nil="true"/>
      <ns1:category_desc xsi:nil="true"/>
      <ns1:checkin xsi:nil="true"/>
      <ns1:note xsi:nil="true"/>
   </ns1:train_description>
   <ns1:route_list>
      <ns1:name>ОСНОВНОЙ МАРШРУТ</ns1:name> <!--Название маршрута-->
      <ns1:from>МОСКВА ОКТ</ns1:from> <!--Начало маршрута-->
      <ns1:to>МУРМАНСК</ns1:to> <!--Окончание маршрута-->
      <ns1:stop_list> <!--Описание станции-->
         <ns1:stop>МОСКВА</ns1:stop> <!--Название станции-->
         <ns1:arrival_time></ns1:arrival_time> <!--Дата прибытия-->
         <ns1:departure_time>00:43</ns1:departure_time> <!--Дата
отправления-->
         <ns1:stop_time>0</ns1:stop_time> <!--Время стоянки-->
      </ns1:stop_list>
      <ns1:stop_list>
         <ns1:stop>ТВЕРЬ</ns1:stop>
         <ns1:arrival_time>02:32</ns1:arrival_time>
         <ns1:departure_time>02:33</ns1:departure_time>
         <ns1:stop_time>1</ns1:stop_time>
      </ns1:stop_list>
   </ns1:route_list>
</return>
```
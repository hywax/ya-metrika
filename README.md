# Yandex Metrika API
Библиотека для удобного взаимодействия с Yandex Metrika API

## Использование
Для форматирования данных необходимо вызвать `format()`. Для произвольных запросов данный метод также работает.

### Установка
##### Composer
```
composer require axp-dev/ya-metrika
```
##### Получение токена
1. Переходим на страницу [oauth.yandex.ru](https://oauth.yandex.ru/)
2. Нажимаем "Зарегистрировать новое приложение"
3. Вписываем название и выбираем "Получение статистики, чтение параметров своих и доверенных счетчиков"
4. Выбираем "Подставить URL для разработки"
5. Копируем ID
6. Переходим по ссылке: `https://oauth.yandex.ru/authorize?response_type=token&client_id=ВАШ ID`
7. Подтверждаем запрос

### Инициализация
```php
$token = '';
$counter_id = '';
$YaMetrika = new YaMetrika($token, $counter_id);

// Пример использования без форматирвоания
$traffic = $YaMetrika->getPreset('traffic')
                     ->data;

// Пример использования с форматированием
$traffic = $YaMetrika->getPreset('traffic', 30, 15)
                     ->format()
                     ->formatData;
                     
// Пример произвольного запроса
$data = [
    'date1'     => Carbon::yesterday()->format('Y-m-d'),
    'date2'     => Carbon::today()->format('Y-m-d'),
    'metrics'   => 'ym:s:visits',
];
$visits = $YaMetrika->customQuery($data)
                    ->data;
```

### Данные по посещаемости
Будут получены данные: визитов, просмотров, уникальных посетителей по дням.

#### За последние N дней
```php
getVisitors($days = 30) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 30

#### За указанный период
```php
getVisitorsForPeriod(DateTime $startDate, DateTime $endDate) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата

### Самые просматриваемые страницы
#### За последние N дней
```php
getMostViewedPages($days = 30, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 30
$limit | integer | Лимит записей. По умолчанию 10

#### За указанный период
```php
getMostViewedPagesForPeriod($template, DateTime $startDate, DateTime $endDate, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата
$limit | integer | Лимит записей. По умолчанию 10

### Браузеры пользователей
#### За последние N дней
```php
getBrowsers($days = 30, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 30
$limit | integer | Лимит записей. По умолчанию 10

#### За указанный период
```php
getBrowsersForPeriod($template, DateTime $startDate, DateTime $endDate, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата
$limit | integer | Лимит записей. По умолчанию 10

### Данные по шаблону
Шаблоны (preset) автоматически задают метрики и группировки, которые необходимы для того или иного отчета. 
Список всех шаблонов доступен по ссылке - [tech.yandex.ru/metrika/../presets-docpage](https://tech.yandex.ru/metrika/doc/api2/api_v1/presets/presets-docpage/).
#### За последние N дней
```php
getPreset($template, $days = 30, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$template | string | Название шаблона
$days | integer | Кол-во дней. По умолчанию 30
$limit | integer | Лимит записей. По умолчанию 10

#### За указанный период
```php
getPresetForPeriod($template, DateTime $startDate, DateTime $endDate, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$template | string | Название шаблона
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата
$limit | integer | Лимит записей. По умолчанию 10

### Произвольный запрос
Параметры `ids` и `oauth_token` передавать не нужно.
```php
public function customQuery($params) : self
```
Название | Тип | Описание
---------|-----|----------------------
$params | array | Параметры запроса
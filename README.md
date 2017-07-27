# Yandex Metrika API
Библиотека для удобного взаимодействия с Yandex Metrika API

## Использование
Для форматирования данных необходимо вызвать `format()`. Для произвольных запросов данный метод также работает.
### Инициализация
```php
$token = '';
$counter_id = '';
$YaMetrika = new YaMetrika($token, $counter_id);

// Пример использования без форматирвоания
$traffic = $YaMetrika->getPreset('traffic', 30)
                     ->data;

// Пример использования с форматированием
$traffic = $YaMetrika->getPreset('traffic', 30)
                     ->format()
                     ->formatData;
                     
// Пример произвольного запроса
$data = [
    'date1'     => Carbon::yesterday(),
    'date2'     => Carbon::today(),
    'metrics'   => 'ym:s:visits',
];
$visits = $YaMetrika->customQuery($data)
                    ->data;
```

### Данные по шаблону
Шаблоны (preset) автоматически задают метрики и группировки, которые необходимы для того или иного отчета. 
Список всех шаблонов доступен по ссылке - [tech.yandex.ru/metrika/../presets-docpage](https://tech.yandex.ru/metrika/doc/api2/api_v1/presets/presets-docpage/).
#### За последние N дней
```php
getPreset($template, $days = 30) : self
```
Название | Тип | Описание
---------|-----|----------------------
$template | string | Название шаблона
$days | integer | Кол-во дней. По умолчанию 30

#### За указанный период
```php
getPresetForPeriod($template, DateTime $startDate, DateTime $endDate, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$template | string | Название шаблона
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата

### Произвольный запрос
Параметры `ids` и `oauth_token` передавать не нужно.
```php
public function customQuery($params) : self
```
Название | Тип | Описание
---------|-----|----------------------
$params | array | Параметры
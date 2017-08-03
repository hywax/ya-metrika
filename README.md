# Yandex Metrika API
Библиотека для удобного взаимодействия с Yandex Metrika API

[![Latest Stable Version](https://poser.pugx.org/axp-dev/ya-metrika/v/stable)](https://packagist.org/packages/axp-dev/ya-metrika)
[![Latest Unstable Version](https://poser.pugx.org/axp-dev/ya-metrika/v/unstable)](https://packagist.org/packages/axp-dev/ya-metrika)
[![License](https://poser.pugx.org/axp-dev/ya-metrika/license)](https://packagist.org/packages/axp-dev/ya-metrika)

## Оглавление
1. [Старт](#Старт)
    + [Composer](#Установка-через-composer)
    + [Получение токена](#Получение-токена)
    + [Инициализация](#Инициализация)
2. [Использование](#Использование)
    + [Данные по посещаемости](#Данные-по-посещаемости)
    + [Самые просматриваемые страницы](#Самые-просматриваемые-страницы)
    + [Браузеры пользователей](#Браузеры-пользователей)
    + [Пользователи из поисковых систем](#Пользователи-из-поисковых-систем)
    + [Пользователи по странам и регионам](#Пользователи-по-странам-и-регионам)
    + [Пол и возраст пользователей](#Пол-и-возраст-пользователей)
    + [Данные по шаблону](#Данные-по-шаблону)
    + [Произвольный запрос](#Произвольный-запрос)
3. [Автор](#Автор)
4. [Лицензия](#Лицензия)

## Старт
### Установка через composer
```
$ composer require axp-dev/ya-metrika
```
### Получение токена
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
## Использование
Для форматирования данных необходимо вызвать `format()`. Для произвольных запросов данный метод также работает.
### Данные по посещаемости
Будут получены данные: визитов, просмотров, уникальных посетителей по дням.

#### За последние N дней
```php
public function getVisitors($days = 30) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 30

#### За указанный период
```php
public function getVisitorsForPeriod(DateTime $startDate, DateTime $endDate) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата

### Самые просматриваемые страницы
#### За последние N дней
```php
public function getMostViewedPages($days = 30, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 30
$limit | integer | Лимит записей. По умолчанию 10

#### За указанный период
```php
public function getMostViewedPagesForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата
$limit | integer | Лимит записей. По умолчанию 10

### Браузеры пользователей
#### За последние N дней
```php
public function getBrowsers($days = 30, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 30
$limit | integer | Лимит записей. По умолчанию 10

#### За указанный период
```php
public function getBrowsersForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата
$limit | integer | Лимит записей. По умолчанию 10

### Пользователи из поисковых систем
#### За последние N дней
```php
public function getUsersSearchEngine($days = 30, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 30
$limit | integer | Лимит записей. По умолчанию 10

#### За указанный период
```php
public function getUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата
$limit | integer | Лимит записей. По умолчанию 10

### Пользователи по странам и регионам
#### За последние N дней
```php
public function getGeo($days = 7, $limit = 20) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 7
$limit | integer | Лимит записей. По умолчанию 20

#### За указанный период
```php
public function getGeoForPeriod(DateTime $startDate, DateTime $endDate, $limit = 20) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата
$limit | integer | Лимит записей. По умолчанию 20

### Пол и возраст пользователей
#### За последние N дней
```php
public function getAgeGender($days = 30, $limit = 20) : self
```
Название | Тип | Описание
---------|-----|----------------------
$days | integer | Кол-во дней. По умолчанию 30
$limit | integer | Лимит записей. По умолчанию 20

#### За указанный период
```php
public function getAgeGenderForPeriod(DateTime $startDate, DateTime $endDate, $limit = 20) : self
```
Название | Тип | Описание
---------|-----|----------------------
$startDate | DateTime | Начальная дата
$endDate | DateTime | Конечная дата
$limit | integer | Лимит записей. По умолчанию 20

### Данные по шаблону
Шаблоны (preset) автоматически задают метрики и группировки, которые необходимы для того или иного отчета. 
Список всех шаблонов доступен по ссылке - [tech.yandex.ru/metrika/../presets-docpage](https://tech.yandex.ru/metrika/doc/api2/api_v1/presets/presets-docpage/).
#### За последние N дней
```php
public function getPreset($template, $days = 30, $limit = 10) : self
```
Название | Тип | Описание
---------|-----|----------------------
$template | string | Название шаблона
$days | integer | Кол-во дней. По умолчанию 30
$limit | integer | Лимит записей. По умолчанию 10

#### За указанный период
```php
public function getPresetForPeriod($template, DateTime $startDate, DateTime $endDate, $limit = 10) : self
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

## Автор
[Alexander Pushkarev](https://github.com/axp-dev), e-mail: [axp-dev@yandex.com](mailto:axp-dev@yandex.com)

## Лицензия
Основой Cinema Park API являет открытый исходный код, в соответствии [MIT license](https://opensource.org/licenses/MIT)
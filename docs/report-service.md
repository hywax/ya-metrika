# Отчеты

**Русский** | [English](./report-service.en.md)

API отчетов позволяет получать информацию о статистике посещений сайта и другие данные.
Более подробно о методах можно прочитать в [документации](https://yandex.ru/dev/metrika/doc/api2/api_v1/intro.html).

## Оглавление

* [Использование](#Использование)
  * [Передача counterId](#Передача-counterid)
  * [Переобразование результата](#Переобразование-результата)
* [Методы](#Методы)
  * [Данные по посещаемости](#Данные-по-посещаемости)
  * [Самые просматриваемые страницы](#Самые-просматриваемые-страницы)
  * [Браузеры пользователей](#Браузеры-пользователей)
  * [Пользователи из поисковых систем](#Пользователи-из-поисковых-систем)
  * [Пользователи по странам и регионам](#Пользователи-по-странам-и-регионам)
  * [Пол и возраст пользователей](#Пол-и-возраст-пользователей)
  * [Поисковые фразы](#Поисковые-фразы)
  * [Данные по шаблону](#Данные-по-шаблону)
  * [Произвольный запрос](#Произвольный-запрос)

## Использование

```php
use Hywax\YaMetrika\Service\ReportService;
use Hywax\YaMetrika\Transformer\ReportDataTransformer;

$reportService = new ReportService([
    'token' => 'ВАШ_ТОКЕН',
    'counterId' => 'ВАШ_ID_СЧЕТЧИКА',
    'resultTransformer' => new ReportDataTransformer(),
]);
```

### Передача counterId

Для удобства работы с отчетами, есть 2 способа передачи `counterId`.

1. В конструкторе класса `ReportService` параметром `counterId`.
2. С помощью метода `setCounterId`.

Оба способа будут передавать ID счетчика в запросы к API. Поддерживает как один ID, так и массив счетчиков.

### Переобразование результата

Для преобразования данных из ответа API в удобный формат, передав параметр `resultTransformer`, можно использовать 2 класса:

1. `ReportDataTransformer::class` - преобразует данные в удобный формат.
2. `ReportRawTransformer::class` - возвращает данные в "сыром виде".

Помимо этого, можно создать свой класс, который будет реализовывать интерфейс `Transformer`.

## Методы

### Данные по посещаемости
Будут получены данные: визитов, просмотров, уникальных посетителей по дням.


#### За последние N дней

```php
public function getVisitors(int $days = 30): array
```

| Название | Тип     | Описание                     |
|----------|---------|------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30 |


#### За указанный период

```php
public function getVisitorsForPeriod(DateTime $startDate, DateTime $endDate): array
```

| Название   | Тип      | Описание       |
|------------|----------|----------------|
| $startDate | DateTime | Начальная дата |
| $endDate   | DateTime | Конечная дата  |

### Самые просматриваемые страницы

#### За последние N дней

```php
public function getMostViewedPages(int $days = 30, int $limit = 10): array
```

| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 10 |


#### За указанный период

```php
public function getMostViewedPagesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): array
```

| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 10 |

### Браузеры пользователей

#### За последние N дней

```php
public function getBrowsers(int $days = 30, int $limit = 10): array
```

| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 10 |


#### За указанный период

```php
public function getBrowsersForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): array
```

| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 10 |

### Пользователи из поисковых систем

#### За последние N дней

```php
public function getUsersSearchEngine(int $days = 30, int $limit = 10): array
```

| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 10 |


#### За указанный период

```php
public function getUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): array
```

| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 10 |

### Пользователи по странам и регионам

#### За последние N дней

```php
public function getGeo($days = 7, $limit = 20): array
```

| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 7    |
| $limit   | integer | Лимит записей. По умолчанию 20 |


#### За указанный период

```php
public function getGeoForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
```

| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 20 |

### Пол и возраст пользователей

#### За последние N дней

```php
public function getAgeGender($days = 30, $limit = 20): array
```

| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 20 |


#### За указанный период

```php
public function getAgeGenderForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
```

| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 20 |

### Поисковые фразы

#### За последние N дней

```php
public function getSearchPhrases($days = 30, $limit = 20): array
```

| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 20 |


#### За указанный период

```php
public function getSearchPhrasesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
```

| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 20 |

### Данные по шаблону
Шаблоны (preset) автоматически задают метрики и группировки, которые необходимы для того или иного отчета.
Список всех шаблонов доступен в [документации](https://tech.yandex.ru/metrika/doc/api2/api_v1/presets/presets-docpage/).

#### За последние N дней

```php
public function getPreset(string $preset, int $days = 30, int $limit = 10): array
```

| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $preset  | string  | Название шаблона               |
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 10 |


#### За указанный период

```php
public function getPresetForPeriod(string $preset, DateTime $startDate, DateTime $endDate, int $limit = 10): array
```

| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $preset    | string   | Название шаблона               |
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 10 |

#### Произвольный запрос

Если передать `ids`, то ID счетчика будет заменен на время одного запроса.

```php
public function getCustomQuery(array $params): array
```

| Название | Тип   | Описание          |
|----------|-------|-------------------|
| $params  | array | Параметры запроса |

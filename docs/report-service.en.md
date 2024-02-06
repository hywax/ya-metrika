# Reports

[Русский](./report-service.md) | **English**

The reports API allows you to get information about site visit statistics and other data.
More details about the methods can be found in [documentation](https://yandex.ru/dev/metrika/doc/api2/api_v1/intro.html).

## Table of contents

* [Usage](#usage)
  * [Transfer counterId](#transfer-counterid)
  * [Result transform](#result-transform)
* [Methods](#methods)
  * [Visitation data](#visitation-data)
  * [Most viewed pages](#most-viewed-pages)
  * [User browsers](#user-browsers)
  * [Users from search engines](#users-from-search-engines)
  * [Users by country and region](#users-by-country-and-region)
  * [Gender and age of users](#gender-and-age-of-users)
  * [Search phrases](#search-phrases)
  * [Data from preset](#data-from-preset)
  * [Custom query](#custom-query)

## Usage

```php
use Hywax\YaMetrika\Service\ReportService;
use Hywax\YaMetrika\Transformer\ReportDataTransformer;

$reportService = new ReportService([
    'token' => 'YOUR_TOKEN',
    'counterId' => 'YOUR_COUNTER_ID',
    'resultTransformer' => new ReportDataTransformer(),
]);
```

### Transfer counterId

For ease of use with reports, there are 2 ways to pass `counterId`.

1. In constructor of `ReportService` class, `counterId` parameter. 
2. Using `setCounterId` method.

Both methods will pass the counter ID to API requests. Supports both a single ID and an array of counters.

### Result transform

You can use 2 classes to convert data from the API response into a convenient format by passing the `resultTransformer` parameter:

1. `ReportDataTransformer::class` - converts data into a convenient format.
2. `ReportRawTransformer::class` - returns data in "raw form".

In addition, you can create your own class that implements the `Transformer` interface.

## Methods

### Visitation data

The following data will be obtained: visits, views, unique visitors by day.

#### For last N days

```php
public function getVisitors(int $days = 30): array
```

| Name  | Type    | Description                |
|-------|---------|----------------------------|
| $days | integer | Number of days. Default 30 |


#### For this period

```php
public function getVisitorsForPeriod(DateTime $startDate, DateTime $endDate): array
```

| Название   | Тип      | Описание   |
|------------|----------|------------|
| $startDate | DateTime | Start date |
| $endDate   | DateTime | End date   |

### Most viewed pages

#### For last N days

```php
public function getMostViewedPages(int $days = 30, int $limit = 10): array
```

| Name   | Type    | Description                |
|--------|---------|----------------------------|
| $days  | integer | Number of days. Default 30 |
| $limit | integer | Record limit. Default 10   |


#### For this period

```php
public function getMostViewedPagesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): array
```

| Name       | Type     | Description              |
|------------|----------|--------------------------|
| $startDate | DateTime | Start date               |
| $endDate   | DateTime | End date                 |
| $limit     | integer  | Record limit. Default 10 |

### User browsers

#### For last N days

```php
public function getBrowsers(int $days = 30, int $limit = 10): array
```

| Name   | Type    | Description                |
|--------|---------|----------------------------|
| $days  | integer | Number of days. Default 30 |
| $limit | integer | Record limit. Default 10   |


#### For this period

```php
public function getBrowsersForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): array
```

| Name       | Type     | Description              |
|------------|----------|--------------------------|
| $startDate | DateTime | Start date               |
| $endDate   | DateTime | End date                 |
| $limit     | integer  | Record limit. Default 10 |

### Users from search engines

#### For last N days

```php
public function getUsersSearchEngine(int $days = 30, int $limit = 10): array
```

| Name   | Type    | Description                |
|--------|---------|----------------------------|
| $days  | integer | Number of days. Default 30 |
| $limit | integer | Record limit. Default 10   |


#### For this period

```php
public function getUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): array
```

| Name       | Type     | Description              |
|------------|----------|--------------------------|
| $startDate | DateTime | Start date               |
| $endDate   | DateTime | End date                 |
| $limit     | integer  | Record limit. Default 10 |

### Users by country and region

#### For last N days

```php
public function getGeo($days = 7, $limit = 20): array
```

| Name   | Type    | Description               |
|--------|---------|---------------------------|
| $days  | integer | Number of days. Default 7 |
| $limit | integer | Record limit. Default 20  |


#### For this period

```php
public function getGeoForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
```

| Name       | Type     | Description              |
|------------|----------|--------------------------|
| $startDate | DateTime | Start date               |
| $endDate   | DateTime | End date                 |
| $limit     | integer  | Record limit. Default 20 |

### Gender and age of users

#### For last N days

```php
public function getAgeGender($days = 30, $limit = 20): array
```

| Name   | Type    | Description                |
|--------|---------|----------------------------|
| $days  | integer | Number of days. Default 30 |
| $limit | integer | Record limit. Default 20   |


#### For this period

```php
public function getAgeGenderForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
```

| Name       | Type     | Description              |
|------------|----------|--------------------------|
| $startDate | DateTime | Start date               |
| $endDate   | DateTime | End date                 |
| $limit     | integer  | Record limit. Default 20 |

### Search phrases

#### For last N days

```php
public function getSearchPhrases($days = 30, $limit = 20): array
```

| Name   | Type    | Description                |
|--------|---------|----------------------------|
| $days  | integer | Number of days. Default 30 |
| $limit | integer | Record limit. Default 20   |


#### For this period

```php
public function getSearchPhrasesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
```

| Name       | Type     | Description              |
|------------|----------|--------------------------|
| $startDate | DateTime | Start date               |
| $endDate   | DateTime | End date                 |
| $limit     | integer  | Record limit. Default 20 |

### Data from preset

Templates (preset) automatically define the metrics and groupings that are required for a particular report.
A list of all templates is available in [documentation](https://tech.yandex.ru/metrika/doc/api2/api_v1/presets/presets-docpage/).

#### For last N days

```php
public function getPreset(string $preset, int $days = 30, int $limit = 10): array
```

| Name    | Type    | Description                |
|---------|---------|----------------------------|
| $preset | string  | Present name               |
| $days   | integer | Number of days. Default 30 |
| $limit  | integer | Record limit. Default 10   |


#### For this period

```php
public function getPresetForPeriod(string $preset, DateTime $startDate, DateTime $endDate, int $limit = 10): array
```

| Name       | Type     | Description              |
|------------|----------|--------------------------|
| $preset    | string   | Present name             |
| $startDate | DateTime | Start date               |
| $endDate   | DateTime | End date                 |
| $limit     | integer  | Record limit. Default 10 |

#### Custom query

If you pass `ids`, the counter ID will be replaced for the duration of one request.

```php
public function getCustomQuery(array $params): array
```

| Name    | Type  | Description    |
|---------|-------|----------------|
| $params | array | Request params |

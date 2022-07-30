# Yandex Metrika API
Библиотека для удобного взаимодействия с Yandex Metrika API

[![Latest Stable Version](https://poser.pugx.org/axp-dev/ya-metrika/v/stable)](https://packagist.org/packages/axp-dev/ya-metrika)
[![PHP Version Require](http://poser.pugx.org/axp-dev/ya-metrika/require/php)](https://packagist.org/packages/axp-dev/ya-metrika)
[![License](https://poser.pugx.org/axp-dev/ya-metrika/license)](https://packagist.org/packages/axp-dev/ya-metrika)

## Оглавление
1. [Старт](#Старт)
    + [Composer](#Установка-через-composer)
    + [Получение токена](#Получение-токена)
    + [Инициализация](#Инициализация)
    + [Примеры](#Примеры)
2. [Использование](#Использование)
    + [Данные по посещаемости](#Данные-по-посещаемости)
    + [Самые просматриваемые страницы](#Самые-просматриваемые-страницы)
    + [Браузеры пользователей](#Браузеры-пользователей)
    + [Пользователи из поисковых систем](#Пользователи-из-поисковых-систем)
    + [Пользователи по странам и регионам](#Пользователи-по-странам-и-регионам)
    + [Пол и возраст пользователей](#Пол-и-возраст-пользователей)
    + [Поисковые фразы](#Поисковые-фразы)
    + [Данные по шаблону](#Данные-по-шаблону)
    + [Произвольный запрос](#Произвольный-запрос)
3. [Ответ](#Ответ)
    + [Чистые данные](#Чистые-данные)
    + [Отформатированные данные](#Отформатированные-данные)
    + [Свой формат данных](#Свой-формат-данных)
    + [Группировка по ключам](#Группировка-по-ключам)
4. [Автор](#Автор)
5. [Лицензия](#Лицензия)

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
$token = getenv('TOKEN');
$counterId = getenv('COUNTER_ID');

$client = new Client($token, $counterId);
$metrika = new YaMetrika($client);
```

### Примеры
+ [Данные о посещаемости](https://github.com/axp-dev/ya-metrika/blob/master/example/visitors.php)
+ [Логирование запросов](https://github.com/axp-dev/ya-metrika/blob/master/example/logger.php)
+ [Использование Proxy](https://github.com/axp-dev/ya-metrika/blob/master/example/proxy.php)
+ [Использование custom format](https://github.com/axp-dev/ya-metrika/blob/master/example/format.php)

## Использование
### Данные по посещаемости
Будут получены данные: визитов, просмотров, уникальных посетителей по дням.

#### За последние N дней
```php
public function getVisitors(int $days = 30): Response
```
| Название | Тип     | Описание                     |
|----------|---------|------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30 |

#### За указанный период
```php
public function getVisitorsForPeriod(DateTime $startDate, DateTime $endDate): Response
```
| Название   | Тип      | Описание       |
|------------|----------|----------------|
| $startDate | DateTime | Начальная дата |
| $endDate   | DateTime | Конечная дата  |

### Самые просматриваемые страницы
#### За последние N дней
```php
public function getMostViewedPages(int $days = 30, int $limit = 10): Response
```
| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 10 |

#### За указанный период
```php
public function getMostViewedPagesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): Response
```
| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 10 |

### Браузеры пользователей
#### За последние N дней
```php
public function getBrowsers(int $days = 30, int $limit = 10): Response
```
| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 10 |

#### За указанный период
```php
public function getBrowsersForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): Response
```
| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 10 |

### Пользователи из поисковых систем
#### За последние N дней
```php
public function getUsersSearchEngine(int $days = 30, int $limit = 10): Response
```
| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 10 |

#### За указанный период
```php
public function getUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): Response
```
| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 10 |

### Пользователи по странам и регионам
#### За последние N дней
```php
public function getGeo($days = 7, $limit = 20): Response
```
| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 7    |
| $limit   | integer | Лимит записей. По умолчанию 20 |

#### За указанный период
```php
public function getGeoForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): Response
```
| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 20 |

### Пол и возраст пользователей
#### За последние N дней
```php
public function getAgeGender($days = 30, $limit = 20): Response
```
| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 20 |

#### За указанный период
```php
public function getAgeGenderForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): Response
```
| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 20 |

### Поисковые фразы
#### За последние N дней
```php
public function getSearchPhrases($days = 30, $limit = 20): Response
```
| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 20 |

#### За указанный период
```php
public function getSearchPhrasesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): Response
```
| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 20 |

### Данные по шаблону
Шаблоны (preset) автоматически задают метрики и группировки, которые необходимы для того или иного отчета.
Список всех шаблонов доступен по ссылке - [tech.yandex.ru/metrika/../presets-docpage](https://tech.yandex.ru/metrika/doc/api2/api_v1/presets/presets-docpage/).
#### За последние N дней
```php
public function getPreset(string $preset, int $days = 30, int $limit = 10): Response
```
| Название | Тип     | Описание                       |
|----------|---------|--------------------------------|
| $preset  | string  | Название шаблона               |
| $days    | integer | Кол-во дней. По умолчанию 30   |
| $limit   | integer | Лимит записей. По умолчанию 10 |

#### За указанный период
```php
public function getPresetForPeriod(string $preset, DateTime $startDate, DateTime $endDate, int $limit = 10): Response
```
| Название   | Тип      | Описание                       |
|------------|----------|--------------------------------|
| $preset    | string   | Название шаблона               |
| $startDate | DateTime | Начальная дата                 |
| $endDate   | DateTime | Конечная дата                  |
| $limit     | integer  | Лимит записей. По умолчанию 10 |

### Произвольный запрос
Параметры `ids` и `oauth_token` передавать не нужно.
```php
public function customQuery(array $params): Response
```
| Название | Тип   | Описание          |
|----------|-------|-------------------|
| $params  | array | Параметры запроса |

## Ответ
### Чистые данные
Возвращает исходные данные, которые были получены напрямую из api.
```php
public function rawData(): array
```

### Отформатированные данные
Возвращает отформатированные данные. Будут переименованы поля, удалены ненужные префиксы.
```php
public function formatData(): array
```

### Свой формат данных
Возвращает отформатированные данные. Будут переименованы поля, удалены ненужные префиксы.
```php
public function customFormat(callable $callback): array
```

### Группировка по ключам
По умолчанию отформатированные данные группируются по ключам:
```
ym:s:, ym:pv:, ym:ad:, ym:sp:
```
Если вам требуется добавить/удалить группировку, то воспользуйтесь `setCombineKeys` у `Response`

## Автор
[Alexander Pushkarev](https://github.com/axp-dev), e-mail: [axp-dev@yandex.com](mailto:axp-dev@yandex.com)

## Лицензия
Основой Yandex Metrika API являет открытый исходный код, в соответствии [MIT license](https://opensource.org/licenses/MIT)
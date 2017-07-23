# Yandex Metrika API #
Библиотека для удобного взаимодействия с Yandex Metrika API

## Документация: ##
#### Инициализация: ####
```php
$token = '';
$counter_id = '';
$YaMetrika = new YaMetrika($token, $counter_id);
```

#### Данные по шаблону: ####
За последние N дней:
```php
getPreset($template, $days = 30)
```
За указанный период:
```php
getPresetForPeriod($template, DateTime $startDate, DateTime $endDate, $limit = 10)
```
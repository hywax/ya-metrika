![Yandex Metrika API](https://raw.githubusercontent.com/hywax/ya-metrika/main/.github/static/cover.png)

# Yandex Metrika API

**Русский** | [English](./README.en.md)

Библиотека для удобного взаимодействия с Yandex Metrika API.

## Оглавление

* [Начало работы](#Начало-работы)
  * [Получение токена](#Получение-токена)
  * [Composer](#composer)
* [Сервисы](#Сервисы)
  * [Отчеты](./docs/report-service.md)
* [Участники](#Участники)
* [Лицензия](#Лицензия)

## Начало работы

### Получение токена

1. Переходим на страницу [oauth.yandex.ru](https://oauth.yandex.ru/)
2. Нажимаем "Зарегистрировать новое приложение"
3. Вписываем название и выбираем "Получение статистики, чтение параметров своих и доверенных счетчиков"
4. Выбираем "Подставить URL для разработки"
5. Копируем ID приложения
6. Переходим по ссылке: `https://oauth.yandex.ru/authorize?response_type=token&client_id=ВАШ_ID`

### Composer

```shell
$ composer require hywax/ya-metrika
```

## Сервисы

* [Отчеты](./docs/report-service.md) - позволяет получать информацию о статистике посещений сайта и другие данные.
  * [Данные по посещаемости](./docs/report-service.md#Данные-по-посещаемости)
  * [Самые просматриваемые страницы](./docs/report-service.md#Самые-просматриваемые-страницы)
  * [Браузеры пользователей](./docs/report-service.md#Браузеры-пользователей)
  * [Пользователи из поисковых систем](./docs/report-service.md#Пользователи-из-поисковых-систем)
  * [Пользователи по странам и регионам](./docs/report-service.md#Пользователи-по-странам-и-регионам)
  * [Пол и возраст пользователей](./docs/report-service.md#Пол-и-возраст-пользователей)
  * [Поисковые фразы](./docs/report-service.md#Поисковые-фразы)
  * [Данные по шаблону](./docs/report-service.md#Данные-по-шаблону)
  * [Произвольный запрос](./docs/report-service.md#Произвольный-запрос)

## Участники

![Участники](https://raw.githubusercontent.com/hywax/ya-metrika/main/.github/static/contributors.svg)

## Лицензия

Основой Yandex Metrika API являет открытый исходный код, в соответствии [MIT license](./LICENSE)
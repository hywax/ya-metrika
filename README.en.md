![Yandex Metrika API](https://raw.githubusercontent.com/hywax/ya-metrika/main/.github/static/cover.png)

# Yandex Metrika API

[Русский](./README.md) | **English**

Library for convenient interaction with Yandex Metrika API.

## Table of contents

* [Getting started](#getting-started)
  * [Receiving a token](#receiving-a-token)
  * [Composer](#composer)
* [Services](#services)
  * [Reports](./docs/report-service.en.md)
* [Contributors](#contributors)
* [License](#license)

## Getting started

### Receiving a token

1. Go to page [oauth.yandex.ru](https://oauth.yandex.ru/)
2. Click "Register new application"
3. Enter a name and select "Obtain statistics, read parameters of your own and trusted counters"
4. Select "Substitute URL for development".
5. Copy application ID
6. Follow the link: `https://oauth.yandex.ru/authorize?response_type=token&client_id=YOUR_ID`

### Composer

```shell
$ composer require hywax/ya-metrika
```

## Services

* [Reports](./docs/report-service.en.md) - allows you to get information about the statistics of site visits and other data.
  * [Visitation data](./docs/report-service.en.md#visitation-data)
  * [Most viewed pages](./docs/report-service.en.md#most-viewed-pages)
  * [User browsers](./docs/report-service.en.md#user-browsers)
  * [Users from search engines](./docs/report-service.en.md#users-from-search-engines)
  * [Users by country and region](./docs/report-service.en.md#users-by-country-and-region)
  * [Gender and age of users](./docs/report-service.en.md#gender-and-age-of-users)
  * [Search phrases](./docs/report-service.en.md#search-phrases)
  * [Data from preset](./docs/report-service.en.md#data-from-preset)
  * [Custom query](./docs/report-service.en.md#custom-query)

## Contributors

![Contributors](https://raw.githubusercontent.com/hywax/ya-metrika/main/.github/static/contributors.svg)

## License

The Yandex Metrika API is based on open source code, according to [MIT license](./LICENSE)
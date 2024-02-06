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

## Contributors

![Contributors](https://raw.githubusercontent.com/hywax/ya-metrika/master/.github/static/contributors.svg)

## License

The Yandex Metrika API is based on open source code, according to [MIT license](./LICENSE)
# Google Drive API Quickstart

Complete the steps described in the [Google Drive API Quickstart](https://developers.google.com/drive/v3/web/quickstart/php), and in about five minutes you'll have a simple PHP command-line application that makes requests to the Google Drive V3 API.

## Set up

### Install Composer Globally

Before running this quickstart, be sure you have [Composer installed globally](https://getcomposer.org/doc/00-intro.md#globally).

```sh
composer install
```

### Download Service Account Credentials

- Go to [APIs and Services](https://pantheon.corp.google.com/apis/credentials) and click "Create Credentials".
  Select "Service Account Key" using the JSON key type, and select "Create".
  Once downloaded, rename the credentials file to `~/credentials.json`

## Run

```sh
php quickstart.php
```

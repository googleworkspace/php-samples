# Google Docs API Samples

To run the samples in this directory, complete the steps described in the [Google Docs API Quickstart](https://developers.google.com/docs/api/quickstart/php), and in about five minutes you'll have a simple PHP command-line application that makes requests to the Google Docs API.

### Install Composer Globally

Before running this quickstart, be sure you have [Composer installed globally](https://getcomposer.org/doc/00-intro.md#globally).

```sh
composer install
```

### Download Developer Credentials

- Follow the steps in the quickstart instructions to download your developer
  credentials and save them in a file called `credentials.json` in this
  directory.

## Run

Run the samples by executing `extract_text.php` or `output_as_json.php` and providing
your [Document ID](https://developers.google.com/docs/api/how-tos/overview#document_id) as
the first argument.

```sh
php extract_text.php YOUR_DOCUMENT_ID
php output_as_json.php YOUR_DOCUMENT_ID
```

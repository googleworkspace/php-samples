#!/bin/bash
export GOOGLE_APPLICATION_CREDENTIALS="$(pwd)/../../application_credentials.json";
./vendor/bin/phpunit tests

# aws-reservation-report
Small php CLI tool to check if running AWS reservations matches build on php

# Usage

1. Setup AWS CLI [https://aws.amazon.com/cli/]()
2. Download composer [https://getcomposer.org/download/]()
3. Run `composer install`
4. Run `bin/get-data.sh`
5. Run `bin/console generate-report data/instances.json data/reserved.json`
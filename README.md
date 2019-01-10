# aws-reservation-report
Small php CLI tool to check if running AWS reservations matches running instances. Build on php

# Usage

1. Setup AWS CLI [https://aws.amazon.com/cli/]()
2. Download composer [https://getcomposer.org/download/]()
3. Run `composer install`
4. Run `bin/get-data.sh`
5. Run `bin/console generate-report data/instances.json data/reserved.json`
6. To get unused reservations count use`bin/console unused-reservations data/instances.json data/reserved.json`

# Options

- `--csv=out.csv` Output report into csv
- `--type=c4.xlarge` Filter only lines for specific instance type. Just instance family can be used `--type=c4` 

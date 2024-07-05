# Commission Calculator

This project is a commission calculator built using the Symfony framework. 
It reads transaction data from an input file, calculates commissions based on BIN and exchange rate information,
and prints the results. The system is designed to be flexible, allowing for easy switching of BIN and exchange rate providers.

## Installation
1. **Clone the repository:**
```
git clone https://github.com/ualexey/commission-calc.git
cd commission-calc
```

2. **Build Docker image:**
```
docker-compose build
```
3. **Install dependencies:**
```
composer install
```

4. **Run tests:**
```
composer test
```

## File Format
```
{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}
```

## Usage
**Run the console command:**
```
php bin/console process:commissions path/to/your/input.txt
```

## Services Configuration
The services are configured in `config/services.yaml`

## Extending Providers
1. Create a new implementation of `BinProviderInterface` or `ExchangeRateProviderInterface`
2. Update the service configuration in `config/services.yaml` to use the new provider


# Sylius Quickpay Refund Bridge Plugin

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Code Coverage][ico-code-coverage]][link-code-coverage]

## Installation

1. `composer require setono/sylius-quickpay-refund-bridge-plugin:dev-master`

2. Import plugin's routes **after** the routes of Sylius Refund Plugin
```
setono_sylius_quickpay_refund_bridge:
    resource: "@SetonoSyliusQuickpayRefundBridgePlugin/Resources/config/routes.yaml"
```

## Development and testing

1. Run `composer create-project setono/sylius-quickpay-refund-bridge-plugin:dev-master setono-sylius-quickpay-refund`.

2. From the plugin skeleton root directory, run the following commands:

    ```bash
    $ php init
    $ (cd tests/Application && yarn install)
    $ (cd tests/Application && yarn build)
    $ (cd tests/Application && bin/console assets:install)
    
    $ (cd tests/Application && bin/console doctrine:database:create)
    $ (cd tests/Application && bin/console doctrine:schema:create)
   
    $ (cd tests/Application && bin/console sylius:fixtures:load -n)
    ```
   
3. Start your local PHP server: `symfony serve` (see https://symfony.com/doc/current/setup/symfony_server.html for docs)

To be able to setup a plugin's database, remember to configure you database credentials in `tests/Application/.env` and `tests/Application/.env.test`.

[ico-version]: https://poser.pugx.org/setono/sylius-quickpay-refund-bridge-plugin/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/sylius-quickpay-refund-bridge-plugin/v/unstable
[ico-license]: https://poser.pugx.org/setono/sylius-quickpay-refund-bridge-plugin/license
[ico-github-actions]: https://github.com/Setono/SyliusQuickpayRefundBridgePlugin/workflows/build/badge.svg
[ico-code-coverage]: https://codecov.io/gh/Setono/SyliusQuickpayRefundBridgePlugin/branch/master/graph/badge.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-quickpay-refund-bridge-plugin
[link-github-actions]: https://github.com/Setono/SyliusQuickpayRefundBridgePlugin/actions
[link-code-coverage]: https://codecov.io/gh/Setono/SyliusQuickpayRefundBridgePlugin

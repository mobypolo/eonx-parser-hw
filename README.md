<p align="center">
  <img src="https://habrastorage.org/webt/7z/uh/zd/7zuhzdxqpv1ljqyc0cytw5ercj0.png" alt="logo" width="420" />
</p>

# Eonx Parser

[![License][badge_license]][link_license]
![badge_php_v]

## Video if you have no time
[`Youtube presentation`](https://youtu.be/YkMk9S3bpT0)

## Install

Clone this repo by:

```shell script
$ git clone git@github.com:mobypolo/eonx-parser-hw.git .
```
Then simple run:
```shell script
$ make up
``` 
> Installed `make` command and `docker` with `docker-compose` is required ([how to install make][getmake] or [how to install docker][getdocker]).

&nbsp;

## Usage parser

Get into fpm container:

```shell script
$ docker exec -it $1 /bin/sh
```
> Where `$1` id or name `fpm-eonx`.

Then simple run:
```shell script
$ php bin/console app:parse-users
``` 
Parser has three optional arguments:
- url for endpoint to parce, default = https://randomuser.me/api?nat=AU&results=10
- count of elements for parse, default = 50
- root element in json result, default = results
For additional tips, please refer to [`ParserCommand`](./src/Command/ParseUsersCommand.php)
&nbsp;

## Existed endpoints

List of users:

```
/customers
```
> Return list of existed users in base.

Detail about user:
```
/customers/{customerId}
``` 
> `customerId` id of existed user, overwhise - 404 error.

&nbsp;

## PHPUnit Test

App have three test:
- two functional test for each endpoint
- one mock for parser test

App already build in test mode, for check test, simply run
```shell script
$ php ./vendor/bin/phpunit
```
&nbsp;

## License

MIT License (MIT).

[badge_packagist_version]:https://img.shields.io/packagist/v/spiral/roadrunner-laravel.svg?maxAge=180
[badge_php_version]:https://img.shields.io/packagist/php-v/spiral/roadrunner-laravel.svg?longCache=true
[badge_build_status]:https://img.shields.io/github/workflow/status/spiral/roadrunner-laravel/tests?maxAge=30
[badge_coverage]:https://img.shields.io/codecov/c/github/spiral/roadrunner-laravel/master.svg?maxAge=180
[badge_downloads_count]:https://img.shields.io/packagist/dt/spiral/roadrunner-laravel.svg?maxAge=180
[badge_license]:https://img.shields.io/packagist/l/spiral/roadrunner-laravel.svg?maxAge=256
[badge_php_v]:https://img.shields.io/badge/php-8.x-green
[badge_lumen_v]:https://img.shields.io/badge/lumen-8.x-brightgreen
[badge_release_date]:https://img.shields.io/github/release-date/spiral/roadrunner-laravel.svg?style=flat-square&maxAge=180
[badge_commits_since_release]:https://img.shields.io/github/commits-since/spiral/roadrunner-laravel/latest.svg?style=flat-square&maxAge=180
[badge_issues]:https://img.shields.io/github/issues/spiral/roadrunner-laravel.svg?style=flat-square&maxAge=180
[badge_pulls]:https://img.shields.io/github/issues-pr/spiral/roadrunner-laravel.svg?style=flat-square&maxAge=180
[link_releases]:https://github.com/spiral/roadrunner-laravel/releases
[link_packagist]:https://packagist.org/packages/spiral/roadrunner-laravel
[link_build_status]:https://github.com/spiral/roadrunner-laravel/actions
[link_coverage]:https://codecov.io/gh/spiral/roadrunner-laravel/
[link_changes_log]:https://github.com/spiral/roadrunner-laravel/blob/master/CHANGELOG.md
[link_issues]:https://github.com/spiral/roadrunner-laravel/issues
[link_create_issue]:https://github.com/mobypolo/docker-roadrunner-lumen-clean-boilerplate/issues/new
[link_commits]:https://github.com/spiral/roadrunner-laravel/commits
[link_pulls]:https://github.com/spiral/roadrunner-laravel/pulls
[link_license]:https://github.com/mobypolo/roadrunner-lumen/blob/main/LICENSE
[getcomposer]:https://getcomposer.org/download/
[getmake]:https://askubuntu.com/questions/161104/how-do-i-install-make
[getdocker]:https://docs.docker.com/engine/install/
[roadrunner]:https://github.com/spiral/roadrunner
[roadrunner_config]:https://github.com/spiral/roadrunner-binary/blob/master/.rr.yaml
[laravel]:https://lumen.laravel.com/
[laravel_events]:https://laravel.com/docs/events
[roadrunner-cli]:https://github.com/spiral/roadrunner-cli
[roadrunner-binary-releases]:https://github.com/spiral/roadrunner-binary/releases
[#10]:https://github.com/spiral/roadrunner-laravel/issues/10
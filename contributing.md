# Contributing Guide

To clone the repository
``` bash
$ git clone --recursive git@github.com:kienstra/bootstrap-widget-styling.git
```

## PHPUnit Testing

These tests will run in an environment with the WordPress unit tests, such as [VVV](https://github.com/Varying-Vagrant-Vagrants/VVV).

Run the PHPUnit tests:

``` bash
$ phpunit
```

Run the PHPUnit tests with a coverage report:

``` bash
$ phpunit --coverage-html /tmp/report
```

## Branching Strategy
This uses the [GitHub Flow](https://guides.github.com/introduction/flow/) branching strategy. Generally, please branch off the `develop` branch, and open a pull request to that branch. If a release is approaching and there are 2 release branches, it'll probably better to branch off the release branch, and open a PR back to it.

## Creating A Release
To make a new release, please follow these steps (modified from the [AMP plugin](https://github.com/Automattic/amp-wp))

1. Make a `.zip` file of the plugin directory, and test it on a WordPress environment with the most recent version.
2. Bump the plugin versions in `package.json`, `package-lock.json` (just run `npm install`), `composer.json`, and in `bootstrap-widget-styling.php` (the metadata block in the header and `Plugin::VERSION`).
3. Add a section for the version in the changelog in the readme.
4. Open a pull request from the release branch to `master`. Review it and merge it.
5. Run `grunt deploy` to to commit the plugin to WordPress.org.
6. [Add new release](https://github.com/Automattic/amp-wp/releases/new) on GitHub with `master` as the target, and use the new version for the tag and release title. Attach the `bootstrap-widget-styling.zip` to it and publish.

# Palacify

This WordPress plugin allows you to easily add HTML to pages and posts using shortcodes.

This plugin is developed using Preact.js for the frontend and [ESbuild](https://esbuild.github.io/) (a fast next-generation JavaScript bundler) has been used as a bundler.

## Required for development

1. Node.js 18+
2. Yarn
3. PHP 7+
4. Composer

## Build

```bash
composer install
yarn install
yarn release
```

It will output `palacify-x.x.x.zip`

## Dependencies

Each of the dependencies may have different licenses; please visit the respective links for more details.

#### Bundle dependencies

* [preact](https://npmjs.com/package/preact)
* [redaxios](https://npmjs.com/package/redaxios)
* [vanillatoasts](https://npmjs.com/package/vanillatoasts)
* [wirec](https://npmjs.com/package/wirec)

#### Dev dependencies

* [esbuild](https://npmjs.com/package/esbuild)
* [nodemon](https://npmjs.com/package/nodemon)
* [uniqcss](https://npmjs.com/package/uniqcss)
# Woo Demo

You can find a live version of this demo at https://iapiwoo.wpcomstaging.com.

## What this project does?

This repository provides a plugin and a theme to demo and test the Interactivity API in WooCommerce.

The plugin provides temporary solutions for some missing functionalities in the WooCommerce plugin:

-   Enables the full client-side navigation experiment.
-   Adds support for client-side sorting.
-   Adds support for categories filtering.
-   Adds support for instant search in the query loop.
-   Handles reviews submissions in the client.

The idea is to explore how those could work in WooCommerce and migrate to them once they are ready.

Apart from that, it creates a theme consuming the different interactive experiences enabled by Woo.

## How to test this locally?

#### 0. Override the `wp.env` configuration by adding a `.wp-env.override.json` file with this changes:

> This step won't be necessary once the migration has been finished and merged in WooCommerce.

```json
{
	"plugins": [
		"../interactivity-api",
		"../woocommerce/plugins/woocommerce",
		"../woocommerce/plugins/woocommerce-beta-tester",
		"./woo-demo-plugin"
	]
}
```

Those plugins point to local repos because it needs to use different branches until some issues are solved:

-   Automattic Interactivity API plugin: This enables the full page client-side navigation.
-   WooCommerce: It needs to point to `trunk` where the migration of the Interactivity API has been merged.
-   WooCommerce Beta Tester: It needs to point to `trunk`, which contains the latest changes.

#### 1. Install dependencies by running `npm install`.

#### 2. Build the project by running `npm run build`.

#### 3. Import the products by uploading the CSV from the assets folder into your site.

#### 4. Add the Woo Logo from the assets folder as the site icon.

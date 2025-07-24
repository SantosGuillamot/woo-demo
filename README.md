# Woo Demo

You can find a live version of this demo at https://iapiwoo.wpcomstaging.com.

## What this project does?

This repository provides a plugin and a theme to demo and test the Interactivity API in WooCommerce.

The plugin provides temporary solutions for some missing functionalities in the WooCommerce plugin:

-   Adds support for client-side sorting.
-   Adds support for categories filtering.
-   Adds support for instant search in the query loop.
-   Handles reviews submissions in the client.

The idea is to explore how those could work in WooCommerce and migrate to them once they are ready.

Apart from that, it creates a theme consuming the different interactive experiences enabled by Woo.

## How to test this locally?

1. Install dependencies by running `npm install`.
2. Build the project by running `npm run build`.
3. Import the products by uploading the CSV from the assets folder into your site.
4. Add the Woo Logo from the assets folder as the site icon.
5. Go to Gutenberg -> Experiments, and enable the full-page client-side navigation.
6. Go to WooCommerce > Settings > Advanced > Features and enable the iAPI powered minicart.

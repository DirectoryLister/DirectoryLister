# Configuration Overview

Directory Lister is customizable through configuration. You can configure Directory Lister in a few different ways.

## The `.env` File

::: tip
This is the recommended method of configuring Directory Lister.
:::

Most configuration changes are possible via a custom `.env` file. This file defines environment variables and their value. To get started:

1. Copy `.env.example` to `.env`
2. Edit the configuration values in `.env`

Your default `.env` file should look something like this:

::: code-group

```bash [.env]
APP_DEBUG=false
APP_LANGUAGE=en

# FILES_PATH=

DISPLAY_READMES=true
READMES_FIRST=false
ZIP_DOWNLOADS=true

SORT_ORDER=type
REVERSE_SORT=false
```

:::

See the [App Config Reference](./configuration-reference.md) for additional configuration options.

## Hiding Files

By default Directory Lister will look for a `.hidden` file in the app root directory (the same place as `index.php`). If found, each line of this file will be used as a [file matching pattern](./file-matching-patterns.md) and every matched file/folder will be hidden from the directory listing.

Each line of the `.hidden` file should contain a single file or path pattern with no end-of-line delimiter. A pattern matching a folder path will cause all files and folders within that folder to be hidden as well as the folder itself.

::: info
The `.hidden` file does not exist by default and must be created to be used.
:::

### Custom JavaScript and CSS

Directory Lister allows for including arbitrary CSS and JavaScript into your Directory Listing through a `.customizations.html` file. This is particularly useful for injecting analytics tracking script (e.g. [Google Analytics](https://analytics.google.com), [Matomo Analytics](https://matomo.org/), [Umami Analytics](https://umami.is/), etc.) into the HTML output of your directory listing.

To inject your code into your page create a file named `.customizations.html` in the base project directory (the same folder as `index.php`) and place your analytics tracking script code into this file.

## Icon Configuration

You can control your application icon mapping through the icon config file.

Reference the [Icon Configuration](./configuration-reference.md#icon-configuration) for more information on configuring icons.

## Advanced Configuration

More control can be achieved via the application config files located in `app/config`. Here you have full control over each option and can even write full PHP code if desired. The application configs are broken up into separate files based on their use. Reference the individual config documentation for more information about individual application configuration options.

::: warning
Take care when upgrading your Directory Lister installation as the files in `app/config` may be overwritten if you copy/paste and replace all files.
:::

See the [Configuration Reference](./configuration-reference.md) for more information about individual options.

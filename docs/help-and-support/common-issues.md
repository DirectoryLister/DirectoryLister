# Common Issues

The following are common issues and some information on why these occur and how to solve them.

## `open_basedir restriction in effect`

### Symptoms

You may see an error like the following:

```
PHP Fatal error:  Uncaught RuntimeException: SplFileInfo::isFile(): open_basedir restriction in effect.
```

### Explanation

Directory Lister has security restrictions in place to mitigate [directory traversal
attacks](https://owasp.org/www-community/attacks/Path_Traversal). Specifically,
the [`open_basedir`](https://www.php.net/manual/en/ini.core.php#ini.open-basedir)
directive is set to the application root directory. Thus, any attempt to access
files outside of the application root will be denied and cause an error similar
to the one above. This applies to symbolic links pointing to files outside of
the application root as well.

## Configuration changes don't take effect when changed

### Symptoms

Configuration changes made in the `.env` file aren't reflected in your application.

### Explanation

This was an issue with the v3.4.0 release. The issue was promptly resolved with the v3.4.1 bug fix, however, the bug could persist through upgrades if the application cache wasn't cleared. To resolve this issue, first ensure you're running v3.4.1 or later then clear your application cache as per the [Upgrade Guide](../upgrade-guide.md).

```bash
rm -rf app/cache/*
```

## `Class 'DOMDocument' not found`

### Symptoms

You may see an error like the following:

```
Fatal error: Uncaught Error: Class 'DOMDocument' not found
```

### Explanation

This error occurs when your server is missing the PHP [DOM extension](https://www.php.net/en/dom). This extension is required for rendering README files on the page. You will need to install that extension.

#### Ubuntu / Debian

```bash
sudo apt install php-dom
```

#### Fedora / Redhat

```bash
sudo yum install php-xml
```

Alternatively you can disable READMEs by setting [`DISPLAY_READMES`](../configuration/configuration-reference.md#display_readmes) to `false` in your `.env` file.

## `Call to undefined function mime_content_type()`

### Symptoms

You may see an error like the following:

```
Fatal error: Uncaught Error: Call to undefined function mime_content_type()
```

### Explanation

This error occurs when your server is missing the PHP [fileinfo](https://www.php.net/manual/en/book.fileinfo.php) extension. This extension is required for rendering README files on the page. You will need to enable or install that extension.

The fileinfo extension is bundled with PHP and enabled by default, and is included with the PHP packages provided by most Linux distributions. If it is missing, enable or install it:

#### Ubuntu / Debian

```bash
sudo phpenmod fileinfo
```

#### Fedora / Redhat

```bash
sudo dnf install php-common
```

Alternatively you can disable READMEs by setting [`DISPLAY_READMES`](../configuration/configuration-reference.md#display_readmes) to `false` in your `.env` file.

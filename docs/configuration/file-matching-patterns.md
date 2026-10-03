# File Matching Patterns

File matching patterns are used in various configuration options including specifying [hidden files](./#hiding-files) and [direct links](./configuration-reference.md#direct_links). A file matching pattern is a path to a file or folder that may contain one or more of the following special expressions.

::: warning
File matching patterns are _case sensitive_. This means that `foo.txt` and `Foo.txt` are _not_ the same.
:::

## Matching Expressions

* `?` matches any single character
* `*` matches zero or more characters excluding `/`
* `**` matches zero or more characters including `/`
* `[abc]` matches a single character from the set (i.e. `a`, `b` or `c`)
* `[a-c]` matches a single character in the range (i.e. `a`, `b` or `c`)
* `[^abc]` matches any character not in the set (i.e. not `a`, `b` or `c`)
* `[^a-c]` matches any character not in the range (i.e. not `a`, `b` or `c`)
* `{foo,bar,baz}` matches any pattern in the set (i.e. `foo`, `bar` or `baz`)

## Assertions

The following assertions can be used to assert that a path is followed by or not followed by another pattern.

* `(=foo)` matches any file name that also contains `foo`
* `(!foo)` matches any file name that does not also contain `foo`

## Examples

**`foo`** Match the literal file or folder `foo` in the root folder

**`foo/bar`** Matches the literal file or folder `bar` in the `foo` folder

**`*.txt`** Matches any file or folder ending with `.txt` in the root folder

**`**/*.txt`** Matches any file or folder ending with `.txt` one or more folders deep (e.g. `foo/bar.txt` or `foo/bar/baz.txt`)

**`**.txt`** Matches any file or folder ending with `.txt` (e.g. `foo.txt`, `foo/bar.txt`, `foo/bar/baz.txt`, etc.)

**`foo/bar/*.txt`** Matches all `.txt` files or folders in the `foo/bar` folder

**`foo/bar/**.txt`** Matches all `.txt` files or folders in the `foo/bar` folder and sub-folders

**`file.{yml,yaml}`** Matches a file or folder named `file.yml` or `file.yaml` in the root folder

**`file.tar(!.{gz,xz})`** Matches a file named `file.tar` or `file.tar.bz` but not `file.tar.gz` or `file.tar.xz`

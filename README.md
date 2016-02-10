# Direct Contents plugin for CraftCMS

Adds `craft.directoryContents.read("path/to/directory")` variable to templates allowing passing of a directory.

The variable returns a recursive array of the directories contents.

Currently supported parameters are `path` and `ignoreFiles`. Only `path` is required.

By Default `ignoreFiles` only ignores `.DS_Store`

Very early release.

```
// Template Tag
{% set files = craft.directoryContents.read("path/to/directory") %} // return array

// or
{% set options = {
    path: "path/to/dir",
    ignoreFiles: [".DS_Store", ".gitignore", "example.txt"]
} %}
{% set files = craft.directoryContents.read(options) %}

```

## TODO
- ADD ALL THE OPTIONS (e.g. depth, file type etc)
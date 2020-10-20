# wp-cli-delete-missing-attachments
Delete missing attachments from the media libray using WPCLI. Based on [wp-cli-media-restore
](https://github.com/wpup/wp-cli-media-restore).

## Installation 

If you're using WP-CLI v0.23.0 or later, you can install this package with:

```
wp package install unapersona/wp-cli-delete-missing-attachments
```

Or, using Composer directly:

```
composer require unapersona/wp-cli-delete-missing-attachments
```

## Options

#### `[--dry-run]`
Run the media library scan and show report, but don't delete attachments.


## Examples

```
wp media delete-missing --dry-run
```

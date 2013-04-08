Dumping the sitemap to files
=========================================

You can dump the sitemap by executing `berriart:sitemap:dump`.
Depending on the options specified, it will generate the sitemap files and the index in the specified directory.

dump_gzip is true if you want to gzip the sitemap files. It will append '.gz' to the filenames.
dump_dir configuration is the target directory where the generated gzipped sitemap files are stored.
dump_url is the url where the gzipped sitemap files can be accessed from. If it is a relative url, the base_url is used as base.
dump_index is the name of the index file.
dump_file_pattern is the pattern to use as filename for the gzipped sitemap files.

``` yaml
# app/config/config.yml
berriart_sitemap:
    dump_gzip: true
    dump_dir: /mydirectory/
    gump_url: http://example.org/mysitemap/
    dump_index: sitemap.index.xml
    dump_file_pattern: sitemap.%d.xml
```
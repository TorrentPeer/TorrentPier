<p align="center" dir="auto">
  <a href="https://torrentpier.site/" rel="nofollow">
    <img src="https://i.ibb.co/xLRMF24/Torrent-Pier-1.png" width="300px" style="max-width: 100%;">
  </a>
</p>

## About TorrentPier

TorrentPier — bull-powered BitTorrent tracker engine, written in php. High speed, simple modification, high load
architecture, built-in support for alternative compiled announcers (Ocelot, XBT). In addition we have very helpful
[official support forum](https://torrentpier.site), where among other things it is possible to test the live demo,
get any support and download modifications for engine.

## Current status

TorrentPier is currently in active development. The goal is to remove all legacy code and rewrite existing to modern
standards. If you want to go deep on the code, check our [issues](https://github.com/torrentpeer/torrentpier/issues)
and go from there. The documentation will be translated into english in the near future, currently russian is the main
language of it.

## Requirements

* Apache (2.4) / nginx
* MySQL / MariaDB / Percona
* PHP: 7.1 / 7.2 / 7.3
* PHP Extensions: bcmath, intl, tidy (optional), xml, xmlwriter

## Installation

For installation you need to follow a few simple steps:

1. Unpack to the server the contents of the downloaded folder
1. Install [Composer](https://getcomposer.org/) and run `composer install` on the downloaded directory
1. Create database and import dump located at **install/sql/mysql.sql**
1. Edit database configuration settings in the configuration file or a local copy (see below)
1. Edit domain name in the configuration file or a local copy (see below)
1. Edit domain ssl setting in the configuration file or a local copy (see below)
1. Edit this files:
  1. **favicon.png** (change on your own)
  1. **robots.txt** (change the addresses in lines **Host** and **Sitemap** on your own)
  1. **opensearch_desc.xml** (change the description and address on your own)
  1. **opensearch_desc_bt.xml** (change the description and address on your own)
1. Log in to the forum with admin/admin login/password and finish setting up via admin panel

## Access rights on folders and files

You must provide write permissions to the specified folders:

* `data/avatars`
* `data/torrent_files`
* `internal_data/ajax_html`
* `internal_data/atom`
* `internal_data/cache`
* `internal_data/log`
* `internal_data/triggers`
* `sitemap`

The specific settings depend on the server you are using, but in general case we recommend chmod 0755 for folders, and
chmod 0644 for files in them. If you are not sure, leave it as is.

## The recommended way to run cron.php

For significant tracker speed increase may be required to replace built-in cron.php by operating system daemon.

## Ocelot installation

We have built-in support for alternate compiled announcer — Ocelot. The configuration is in the file **
library/config.php**, the announcer is in the repository [torrentpier/ocelot](https://github.com/torrentpier/ocelot).

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull
requests to us. But we are always ready to renew your pull-request for compliance with these requirements. Just send it.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see
the [tags on this repository](https://github.com/torrentpeer/torrentpier/tags).

## License

This project is licensed under the MIT License - see the [LICENSE](https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE) file for details

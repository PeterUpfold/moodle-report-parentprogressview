Parent Progress View
====================

A 'report' module for Moodle to allow the viewing of documents and pupil data by authorised parent accounts.

This module is designed specifically to be used in combination with an installation of WordPress which holds the data in custom post types and using custom fields.
That WordPress install is expected to expose the data via the [WP REST API](https://developer.wordpress.org/rest-api/); this module uses the REST API to retrieve the data on behalf of the user and then present it to them.

Documentation regarding the correct configuration of the WordPress install to support this module is available separately.

Requirements
============

Requires Moodle 3.3 or later.

Assumptions
============

This module provides the user interface for parent accounts to use to view documents that pertain to their pupils. It assumes:

 * The separate WordPress install holding the data is appropriately secured to prevent inappropriate direct access to that data.
 * The separate WordPress install supports querying for data via the REST API using `meta_query` parameters.
 * The separate WordPress install returns custom field data in its JSON responses.
 * A separate system is in place for the management of parent accounts, including the assignment of the 'parent' role that controls access to data exposed via this interface.
 * For reasons of performance, access to documents is achieved through direct access to a target MariaDB database table. Documents are expected to be stored in a format consistent with the **tvs-mis-documents-to-moodle** WordPress plugin. Other data access is performed through the WP REST API as described.
   * Accordingly, a MariaDB database user account with limited privileges is configured for this access.

Rewrite Rules
=============

You must configure your web server to have a rewrite rule similar to the following to allow document access through the mobile app:

    rewrite ^/moodle/report/parentprogressview/mobile/document/([0-9]+)/([0-9a-f]+)/document.pdf$ /moodle/report/parentprogressview/mobile/document.php?id=$1&token=$2 last;

Limitations
===========

At this time, HTTP Basic Authentication is the only method supported for having this module authenticate with the WordPress REST API backend. It is essential therefore that HTTPS transport will always be used to the REST API and, additionally, that the REST API be hosted on a trusted system on a trusted network (the reference installation runs both components on the same machine, under separate Unix user accounts).

At this time, `meta_query` is used for querying the REST API backend, but improved performance could be achieved with the use of `tax_query`.

Licence
=======

[In common with the Moodle software from which it derives](https://docs.moodle.org/dev/License), Parent Progress View is available under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Please see `LICENSE.md` in this repository for full licence text.

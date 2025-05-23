# Alma Offline Circulation Tool

## Setup

- Copy `compose.override.yml-example` to `compose.override.yml`. Set up ports
  and LDAP variables as needed.
- Copy `src/include/configuration_sample.php` to
  `src/include/configuration.php` and adjust settings as needed. The defaults
  will work fine for development, but production should have contact
  information set up properly, and possibly database settings.
- Build the project each time code changes! This isn't mounting PHP directly
  into the running containers at the moment, other than the config file.
- Use docker / podman's compose to run the project. The "web" service is what's
  exposed to the public (or via another proxy), serving static files directly,
  and contacting the "app" service for any PHP assets.

We've made very deliberate choices to reduce security risks: "web" has no PHP
files, which means there's no chance of Apache accidentally serving up files
directly. All authentication is done by Apache using LDAP, so there's no chance
of hacking a PHP login page. Apache also has no direct connection to the
database: it has the static assets, LDAP configuration, and access to contact
the "app" service. Neither the "app" nor "db" services are exposed to the host,
which means you can never reach them from external systems.

This app has minimal security risks anyway, being locked down to campus *and*
protected by a login, but it's probably not a bad example for other projects.

## Background / Info

This tool is a set of PHP scripts that present an interface to a single-table
MySQL database, which tracks patrons and items while Alma is down. In short, it
is (as named) an offline circulation tool for Alma. It is worth noting that the
interface does nothing to protect any of the pages, so it is advisable to
restrict access to the server running this offline circulation tool.

While Alma already provides an offline circulation tool, that tool assumes the
entire network on your end is down. It generates a file on each and every
circulation computer, and every file has to be uploaded individually and in
careful order. If one computer checks an item in and another one checks the
same item out, collisions occur all the time when Alma is back online. Since
many colleges have dependable enough internal networks to at least assume that
much of the network is always up, often the offline tool is really only being
used because Alma is down on exLibris' end. Therefore, it is often beneficial
to utilize the local campus network to centralize these circulation records and
not have collisions. This also reduces the number of files that need to be
uploaded.

The main interface that circulation desk workers (so all of the staff and
student workers checking things in and out) care about is contained in
index.php. There is a region of the page for recording patron and item data for
check out, and another region for recording item data to check in. The "Add
Item" button in each region parses the data, formats it for storage, and adds
it to a list at the bottom of the page. The "Submit" button carries the data
through a form to the next page, which actually adds the records to the
database.

To extract the records, browse to the listrecords.php page. Because records are
meant to be modified and exported from here, it would be wise to specify which
individuals should access this page to prevent multiple users from colliding in
extracting the records. While no records truly get deleted from the database on
this page, it is tricky to extract just the records since the last upload once
they have been marked. When files are generated, the server will write two
files: a regular `*.dat` file used for the Alma upload when it is back online
and a `*.csv` file. The `*.csv` file is one of the primary benefits of this
tool, as it contains a superset of the `*.dat` file's data in a manner that is
easy for humans to read.

When uploading to Alma, there will almost certainly be lines it does not like.
For each line, add one to the line number[^1] and browse to that row in the
`*.csv` file. Unlike the `*.dat` file which only has the faulty barcodes
scanned into it, the `*.csv` file will have the attached patron names and item
titles/call numbers in the same row. This information eases troubleshooting for
barcode mismatches, patron id changes, and other problems that Alma likes to
complain about when uploading offline records.

## Additional Notes

One thing we learned the hard way from doing this: Alma uploads everything as
if it were handled at the location of the person doing the upload. Even though
a single file would be the easiest thing to upload, the option to create files
by location is there so staff at different locations can upload different files
at their own respective locations. If only a single file is uploaded, then
anything that belongs at an alternate location is set to be "In Transit"
instead of checked in. While mostly an issue for larger libraries with multiple
sites, this can technically also be used (if the code is modified accordingly)
to track other details like the kind of item, for anyone who feels this is a
useful thing to do. The 3 character location code is part of the `*.csv` file
along with all of the other data exported.



[^1]: Add one to the number because the `*.dat` file immediately starts with
data to upload, whereas the `*.csv` file uses the first row to describe what
each field is.

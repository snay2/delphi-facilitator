delphi-facilitator
==================

Simple app to streamline the [Delphi estimation process](http://en.wikipedia.org/wiki/Delphi_method). Provides a mobile-friendly UI, allowing team members to submit and review estimates quickly and easily.

This initial version is written in PHP and requires MySQL with the given schema.

A demo version is currently running at [delphi.scnay.com](http://delphi.scnay.com/).

Instructions for use
--------------------
1. Everyone opens the delphi-facilitator through a browser on his or her smartphone/tablet/computer.
2. The moderator taps "Create a new estimate session" and informs group members of the new estimate ID number. Each person enters this number in the "Estimate ID" box.
3. Each person submits an estimate using the desired units for the low and high ranges and taps "Submit my estimate".
4. Once everyone has submitted an estimate, the moderator (and everyone else) may tap "Show all results". This returns the anonymized results for every round of the current estimate session.
5. The group may review and discuss the results.
6. If another round is merited, the moderator taps "Start a new round for this estimate."
7. Everyone submits another estimate, exactly as before.
8. The group repeats steps 4-7 until consensus is reached.

Normalization
-------------
Normalization facilitates easy comparison of results with varied units.

Next to the Results heading is a link: "Normalize (h)". Tapping this link normalizes all the results to the unit indicated in the parentheses. The link cycles through all four available units in order (hours, days, weeks, and months). 

Installation
------------
If you want to run delphi-facilitator on your own server, you just need Apache, PHP, and MySQL. Install and configure
those. (On Ubuntu that's as simple as `apt-get install apache2 php5 mysql-server`.) Create a MySQL database called
`snay2_delphi` with the schema described in `snay2_delphi.sql`. Modify `db.php` to point to the MySQL server with the 
proper credentials. That's it.

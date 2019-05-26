#!/usr/bin/php
<?php

/*
 * This script sends a hash of your username and which campus you are on.
 * If the campus is not Fremont or Paris, your hostname is sent instead.
 * The google forms request also logs the timestamp. You can disable this by
 * setting SEND_USAGE_STATISTICS=0 in options-config.ini
 *
 * All collected data can be viewed here:
 * https://docs.google.com/spreadsheets/d/1_UxSfIVYFPQqCotnaEFNApwXchNvWLqANEum1tSvHPM/edit?usp=sharing
 */

$enabled = intval(trim(shell_exec("cat options-config.ini | grep 'SEND_USAGE_STATISTICS' | cut -d '=' -f 2")));

if (!($enabled))
	exit(0);

$hostname = shell_exec("hostname");
if ($hostname == NULL)
	$hostname = "Could%20not%20get%20host%20name";

//Attempt to resolve campus from hostname
if (strpos($hostname, "42.us.org") > 0)
	$location = "Fremont";
else if (strpos($hostname, "42.fr") > 0)
	$location = "Paris";
else
	$location = $hostname;

//Get a hash of username
$username = shell_exec("whoami");
if ($username == NULL)
	$username_hash = "Could%20not%20get%20username";
else
	$username_hash = sha1($username);

//Remove username and hostname data
unset($username);
unset($hostname);

//Submit google form with this data
$url = "https://docs.google.com/forms/d/e/1FAIpQLSdwJSs9uPo93TYhyHH7PLZacAntDB4HsHq1vI2Bntxa13xCbw/formResponse?usp=pp_url&entry.1758027586=$username_hash&entry.1838934565=$location&submit=Submit";
file_get_contents($url);

?>

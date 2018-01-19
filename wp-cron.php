<?php
/*
==================================================
WP-CRON
==================================================
https://developer.wordpress.org/plugins/cron/

Unlike real crons, wp-cron works only on page load. Scheduling errors can occur when a page load doesn't happen for 3+ hours after the task was scheduled to run.

GENERAL
_get_cron_array()               // Get the full list of scheduled events

SCHEDULE EVENTS
wp_schedule_event(
    $scheduled_timestamp,       // The timestamp (UTC) when the event should run for the first time
    $event_recurrence,          // Keyword matching a registered schedule (defined on the 'cron_schedules' filter)
    $event_name,                // Name of the event to trigger with do_action
    $event_args = []            // Data to pass to the callback, also used to further identify a particular event (there could be multiple events with the same name but different arguments)
)
wp_reschedule_event(
    $scheduled_timestamp,       // Updated time for its next run
    $event_recurrence,
    $event_name,
    $event_args = []
)
wp_schedule_single_event(       // Schedules an event to run only once
    $scheduled_timestamp,
    $event_name,
    $event_args = []
)
wp_next_scheduled(              // Returns the timestamp of the next scheduled run of a given event (also matches the args)
    $event_name,
    $event_args = []
)
wp_unschedule_event(            // Unschedule a particular event run (also matches the args)
    $scheduled_timestamp,       // Timestamp of the next scheduled run
    $event_name,
    $event_args = []
)
wp_clear_scheduled_hook(        // Unschedules all runs of a particular event (also matches the args)
    $event_name,
    $event_args = []
)
wp_unschedule_hook(             // Unschedules all runs of a particular event (regardless of the args)
    $event_name
)

REGISTER CRON SCHEDULES
wp_get_schedules()              // Get the info of all registered cron schedules (modified by the 'cron_schedules' filter)
wp_get_schedule(                // Returns the name of the cron schedule used to register a particular event (e.g. 'daily')
    $event_name,
    $event_args = []
)

FILTERS
cron_schedules                  // Manage the list of registered cron schedules

REAL CRONS
If you need a task to always run at an exact time or interval, you'll need to set up a real cron job:
1. Setup the cron task on the server, pointing to wp-cron.php
2. disable wp-cron by setting the constant 'DISABLE_WP_CRON' to 'true' in wp-config.php

UNIX commands
crontab -e                                          // lists all current crons
0 0 * * * wget http://YOUR_SITE_URL/wp-cron.php     // Run once every midnight
                                                    // minute[0-59], hour[0-23], day of month[1-31], month[1-12], day of week[0-6] (sunday is '0')

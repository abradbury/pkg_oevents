#!/usr/bin/env bash

set -e

echo
echo "Zipping OEvents module..."
zip -r mod_oevents_external.zip mod_oevents_external

echo
echo "Zipping OEvents component..."
zip -r com_oevents.zip com_oevents

echo
echo "Zipping OEvents library..."
zip -r lib_oevents.zip lib_oevents

echo
echo "Zipping OEvents updater plugin..."
zip -r plg_task_oevents_updater.zip plg_task_oevents_updater

echo
echo "Zipping OEvents package..."
zip -r pkg_oevents.zip {*.xml,*.zip,language}

echo
echo "Removing zip artifacts..."
rm mod_oevents_external.zip
rm com_oevents.zip
rm lib_oevents.zip
rm plg_task_oevents_updater.zip

echo
echo "All done"

#!/usr/bin/env bash

SALT=$(openssl rand -base64 18)
PSWD=$(openssl rand -base64 18)

sed -i '' "s/SALT_PLACEHOLDER/$SALT/; s/PASSWORD_PLACEHOLDER/$PSWD/" com_oevents/site/controller.php
sedResult=$?
if [ $sedResult -ne 0 ]; then
    echo ""
    >&2 echo "Failed to insert generated token values. This can sometimes happen on macOS."
    >&2 echo "Please try running this script again (sometimes several times) until it succeeds."
    echo ""

    exit $sedResult
fi

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
echo "Zipping OEvents package..."
zip -r pkg_oevents.zip {*.xml,*.zip,language}

echo
echo "Removing zip artifacts..."
rm mod_oevents_external.zip
rm com_oevents.zip
rm lib_oevents.zip

echo
echo "Use the following values when setting up your cURL job: "
echo "  salt:     $SALT"
echo "  password: $PSWD"
echo 
echo "All done"

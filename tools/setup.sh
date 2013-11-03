#!/bin/bash
#
# Configuration script to be run when a new client is first cloned

# Create a copy of the config file unless it already exists
if [ ! -e mule.conf ]
then
  cp mule.conf.sample mule.conf
  echo "*** Please remember to edit mule.conf according to your needs"
fi

# Make some directories world-writable
chmod 777 templates_c

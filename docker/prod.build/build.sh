#!/usr/bin/env sh

# https://gist.github.com/earthgecko/3089509
secret() {
  # bash generate random 32 character alphanumeric string (upper and lowercase) and 
  NEW_UUID=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)

  # bash generate random 32 character alphanumeric string (lowercase only)
  cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1

  # Random numbers in a range, more randomly distributed than $RANDOM which is not
  # very random in terms of distribution of numbers.

  # bash generate random number between 0 and 9
  cat /dev/urandom | tr -dc '0-9' | fold -w 256 | head -n 1 | head -c 1

  # bash generate random number between 0 and 99
  NUMBER=$(cat /dev/urandom | tr -dc '0-9' | fold -w 256 | head -n 1 | sed -e 's/^0*//' | head -c 2)
  if [ "$NUMBER" == "" ]; then
    NUMBER=0
  fi

  # bash generate random number between 0 and 999
  NUMBER=$(cat /dev/urandom | tr -dc '0-9' | fold -w 256 | head -n 1 | sed -e 's/^0*//' | head -c 3)
  if [ "$NUMBER" == "" ]; then
    NUMBER=0
  fi
}

set APP_SECRET=$(secret)
cd /home/clickbus-backend/service-restapi
rm -rf vendor
sed "s/__APP_SECRET__/$APP_SECRET/" < /home/clickbus-backend/.env > /home/clickbus-backend/service-restapi/.env

SYMFONY_ENV=prod composer install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

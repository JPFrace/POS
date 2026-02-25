#!/bin/bash

echo "Starting backend..."
(cd pos-api && php artisan serve) &

echo "Starting frontend..."
(cd pos-fe && npm run dev) &

wait

#RUN: sh run.sh

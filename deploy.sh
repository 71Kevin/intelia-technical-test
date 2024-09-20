#!/bin/bash

APP_NAME="intelia-technical-test"
HOST="kevin@127.0.0.1"
PORT=8000
TIMEZONE="America/Sao_Paulo"
IMAGE_ID="$(ssh "$HOST" docker images --filter=reference="$APP_NAME" --format "{{.ID}}")"

echo "$1 $2"

run_container() {
    docker run \
        --log-opt max-size=50m \
        --log-opt max-file=5 \
        -e TZ="$TIMEZONE" \
        "$ENV" \
        "$COND" \
        --name="$APP_NAME" \
        -p "$PORT":"$PORT" \
        -d \
        --restart=unless-stopped "$APP_NAME"
}

clear_image() {
    docker rmi "$IMAGE_ID"
}

if [ "$1" == "local" ]; then

    ENV="-e ENV_PROD=0"
    COND="--net='host'"

    if [ "$2" == "deploy" ]; then
        docker build -t "$APP_NAME" .
        docker stop "$APP_NAME"
        docker rm "$APP_NAME"
        run_container
    fi

    if [ "$2" == "logs" ]; then
        docker logs -f "$APP_NAME"
    fi

fi

if [ "$1" == "remote" ]; then

    ENV="-e ENV_PROD=1"
    COND="--net='host'"

    if [ "$2" == "deploy" ]; then
        ssh -tt "$HOST" "mkdir -p $APP_NAME && cd $APP_NAME && sudo chmod 777 * -R"
        rsync --progress -avz -e "ssh" . "$HOST:$APP_NAME"
        ssh -tt "$HOST" "cd $APP_NAME && docker build -t $APP_NAME ."
        ssh -tt "$HOST" "cd $APP_NAME && docker stop $APP_NAME"
        ssh -tt "$HOST" "cd $APP_NAME && docker rm $APP_NAME"
        ssh -tt "$HOST" "cd $APP_NAME && $(run_container)"
        ssh -tt "$HOST" "docker rmi $(echo "$IMAGE_ID")"
    fi

    if [ "$2" == "logs" ]; then
        ssh -tt "$HOST" "docker logs -f $APP_NAME"
    fi

fi
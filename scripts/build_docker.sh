#!/usr/bin/env bash
PROJECT_PATH=`pwd`

generate_docker_images() {
  # Generate the base image
  echo "Generate the base docker image for the project"
  docker build \
    -f ./docker/base/Dockerfile \
    -t clickbus_backend_base .

  docker network create --subnet=6.6.6.0/16 clickbus_backend_network

  case $ENV_APP in
    staging)
      ;;
    production)
      echo "Generate prod build docker image"
      
      echo "Generate prod build docker image"
        docker build \
          -f ./docker/prod.build/Dockerfile \
          -t clickbus_backend_prod_build .
      ;;
    *)
      # Generate the environment image
      echo "Generate development docker images"

      echo "Generate dev database docker image"
      docker build \
        -f ./docker/dev.database/Dockerfile \
        -t clickbus_backend_db .
      
      echo "Generate dev server docker image"
        docker build \
          -f ./docker/dev.server/Dockerfile \
          -t clickbus_backend_app .
      ;;
  esac
}

if [ -z "$ENV_APP" ]; then
  echo 'WARNING: The enviroment parameter "ENV_APP" not exists. This default value is ENV_APP="dev"'
  ENV_APP='dev'
fi

generate_docker_images
exit 0;
